<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use UnexpectedValueException;

class NewsFeed
{
    private const API_URL = 'https://www.equityworld-futures.com/api/portalnews';

    private const CATALOG_PAGE_SIZE = 50;

    private const TOP_NEWS_TITLE_SIGNALS = [
        [5, '/\b(?:war|conflict|attack|invasion|iran|israel|ukraine|strait of hormuz|geopolitic\w*)\b/i'],
        [5, '/\b(?:tariffs?|trade war|election|government shutdown|sanctions?)\b/i'],
        [4, '/\b(?:fed|federal reserve|fomc|powell|interest rates?|rate cuts?|rate hikes?|central banks?|hawkish|dovish|monetary policy)\b/i'],
        [4, '/\b(?:inflation|cpi|pce|nonfarm|nfp|payrolls?|jobs report|unemployment|stagflation|gdp|recession)\b/i'],
        [3, '/\b(?:record (?:high|low)|all[- ]time high|surges?|soars?|plunges?|slumps?|crashes?|rallies|sell[- ]off|tumbles?|tanks?|sharp drop|strong rebound)\b/i'],
    ];

    /**
     * @throws ConnectionException
     * @throws RequestException
     * @throws UnexpectedValueException
     */
    public function page(int $page, int $perPage = 10): array
    {
        return Cache::remember("equityworld.news.page.{$page}.{$perPage}", now()->addMinutes(5), function () use ($page, $perPage): array {
            $response = Http::acceptJson()
                ->timeout(10)
                ->get(self::API_URL, [
                    'page' => $page,
                    'per_page' => $perPage,
                    'sort_by' => 'created_at',
                    'order' => 'desc',
                ]);

            $response->throw();
            $data = $response->json();

            if (! is_array($data) || ! is_array($data['data'] ?? null)) {
                throw new UnexpectedValueException('The news API returned an invalid response.');
            }

            return $data;
        });
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     * @throws UnexpectedValueException
     */
    public function catalog(): array
    {
        return Cache::remember('equityworld.news.catalog.v3', now()->addMinutes(5), function (): array {
            $firstPage = $this->fetchPage(1, self::CATALOG_PAGE_SIZE);
            $lastPage = max(1, (int) ($firstPage['last_page'] ?? 1));
            $items = array_values(array_filter($firstPage['data'], 'is_array'));

            for ($page = 2; $page <= $lastPage; $page++) {
                $payload = $this->fetchPage($page, self::CATALOG_PAGE_SIZE);
                $pageItems = array_values(array_filter($payload['data'], 'is_array'));

                // The source can report more pages than it actually returns.
                if ($pageItems === []) {
                    break;
                }

                array_push($items, ...$pageItems);
            }

            $news = [];

            foreach ($items as $item) {
                $catalogItem = $this->catalogItem($item);

                if ($catalogItem['id'] > 0) {
                    $news[$catalogItem['id']] = $catalogItem;
                }
            }

            return ['items' => array_values($news)];
        });
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     * @throws UnexpectedValueException
     */
    public function article(string $id): array
    {
        return Cache::remember("equityworld.news.article.{$id}", now()->addMinutes(5), function () use ($id): array {
            $response = Http::acceptJson()
                ->timeout(10)
                ->get(self::API_URL.'/'.$id);

            $response->throw();
            $data = $response->json();

            if (! is_array($data) || ! is_array($data['data'] ?? null)) {
                throw new UnexpectedValueException('The news API returned an invalid article.');
            }

            return $data['data'];
        });
    }

    public function formatNewsItem(array $item): array
    {
        $article = $this->formatArticle($item);

        return [
            ...$this->catalogItem($item),
            'url' => route('berita.detail', ['article' => $article['id']]),
        ];
    }

    /**
     * Format a page of news while excluding repeated stories, regardless of category.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    public function distinctNewsItems(array $items, int $limit = 3): array
    {
        if ($limit < 1) {
            return [];
        }

        $formattedItems = [];
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $formattedItems[] = $this->formatNewsItem($item);
        }

        return $this->distinctFormattedNewsItems($formattedItems, $limit);
    }

    /**
     * Remove repeated stories from already formatted items, regardless of category or source.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    public function distinctFormattedNewsItems(array $items, int $limit = PHP_INT_MAX): array
    {
        if ($limit < 1) {
            return [];
        }

        $distinct = [];

        foreach ($items as $formatted) {
            if (! is_array($formatted) || ! isset($formatted['title'])) {
                continue;
            }

            $title = $this->normalizeStoryText($formatted['title']);
            $excerpt = $this->normalizeStoryText((string) ($formatted['excerpt'] ?? ''));
            $isDuplicate = false;

            foreach ($distinct as $existing) {
                if (isset($formatted['id'], $existing['id']) && $formatted['id'] === $existing['id']) {
                    $isDuplicate = true;
                    break;
                }

                $existingTitle = $this->normalizeStoryText($existing['title']);
                $existingExcerpt = $this->normalizeStoryText((string) ($existing['excerpt'] ?? ''));

                if (
                    $title === $existingTitle
                    || ($this->titlesMayMatch($title, $existingTitle) && $this->textSimilarity($title, $existingTitle) >= 88)
                ) {
                    $isDuplicate = true;
                    break;
                }

                if ($excerpt !== '' && $excerpt === $existingExcerpt) {
                    $isDuplicate = true;
                    break;
                }
            }

            if (! $isDuplicate) {
                $distinct[] = $formatted;
            }

            if (count($distinct) >= max(0, $limit)) {
                break;
            }
        }

        return $distinct;
    }

    private function normalizeStoryText(string $text): string
    {
        $text = Str::ascii(mb_strtolower($text));
        $text = str_replace('&', ' and ', $text);
        $text = preg_replace('/[^a-z0-9]+/', ' ', $text) ?? '';

        return trim(preg_replace('/\s+/', ' ', $text) ?? '');
    }

    private function textSimilarity(string $left, string $right): float
    {
        if ($left === '' || $right === '') {
            return 0;
        }

        similar_text($left, $right, $percent);

        return $percent;
    }

    private function titlesMayMatch(string $left, string $right): bool
    {
        $leftWords = array_values(array_unique(explode(' ', $left)));
        $rightWords = array_values(array_unique(explode(' ', $right)));
        $sharedWords = count(array_intersect($leftWords, $rightWords));
        $minimumWords = min(count($leftWords), count($rightWords));

        return $sharedWords >= min(3, $minimumWords)
            && $sharedWords / max(count($leftWords), count($rightWords)) >= 0.65;
    }

    public function categorySlug(string $category): string
    {
        return Str::slug($category);
    }

    public function topNews(array $items): ?array
    {
        $topNews = null;
        $topScore = 0;
        $topPublishedAt = 0;

        foreach ($items as $item) {
            $title = (string) ($item['title'] ?? '');
            $score = 0;

            foreach (self::TOP_NEWS_TITLE_SIGNALS as [$weight, $pattern]) {
                if (preg_match($pattern, $title) === 1) {
                    $score += $weight;
                }
            }

            $publishedAt = strtotime((string) ($item['published_at'] ?? '')) ?: 0;

            if ($score > $topScore || ($score > 0 && $score === $topScore && $publishedAt > $topPublishedAt)) {
                $topNews = $item;
                $topScore = $score;
                $topPublishedAt = $publishedAt;
            }
        }

        return $topNews;
    }

    private function fetchPage(int $page, int $perPage): array
    {
        $response = Http::acceptJson()
            ->timeout(15)
            ->retry(2, 200)
            ->get(self::API_URL, $this->pageQuery($page, $perPage));

        $response->throw();

        return $this->decodePage($response->json());
    }

    private function pageQuery(int $page, int $perPage): array
    {
        return [
            'page' => $page,
            'per_page' => $perPage,
            'sort_by' => 'created_at',
            'order' => 'desc',
        ];
    }

    private function decodePage(mixed $data): array
    {
        if (! is_array($data) || ! is_array($data['data'] ?? null)) {
            throw new UnexpectedValueException('The news API returned an invalid response.');
        }

        return $data;
    }

    private function catalogItem(array $item): array
    {
        $article = $this->formatArticle($item);

        return [
            'id' => $article['id'],
            'title' => $article['title'],
            'category' => $article['category'],
            'published_at' => $article['published_at'],
            'excerpt' => Str::limit($article['summary'] ?: implode(' ', $article['paragraphs']), 190),
            'image' => $article['image'],
        ];
    }

    public function formatArticle(array $item): array
    {
        $images = is_array($item['images'] ?? null) ? $item['images'] : [];
        $image = $item['image'] ?? $images[3] ?? $images[0] ?? null;
        $image = is_string($image) && filter_var($image, FILTER_VALIDATE_URL) ? $image : null;

        $content = (string) ($item['content'] ?? '');
        $content = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', ' ', $content) ?? '';
        $content = preg_replace('#</(p|div|h[1-6]|li|br)\s*>#i', "\n\n", $content) ?? '';
        $content = html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $content = preg_replace('/[ \t]+/u', ' ', $content) ?? '';
        $paragraphs = array_values(array_filter(
            array_map('trim', preg_split('/\R{2,}/u', $content) ?: []),
            static fn (string $paragraph): bool => $paragraph !== '',
        ));

        return [
            'id' => (int) ($item['id'] ?? 0),
            'title' => (string) ($item['titles']['ewf'] ?? $item['title'] ?? 'Berita pasar terbaru'),
            'category' => (string) ($item['kategori']['name'] ?? $item['category'] ?? 'Berita'),
            'published_at' => $item['created_at'] ?? null,
            'summary' => trim(strip_tags((string) ($item['summary'] ?? ''))),
            'paragraphs' => $paragraphs,
            'image' => $image,
        ];
    }
}
