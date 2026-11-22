<?php

namespace App\Services;

use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use UnexpectedValueException;

class NewsMakerFeed
{
    private const PAGE_URL = 'https://newsmaker.id/id/news';

    /**
     * @return array<int, array<string, mixed>>
     */
    public function latest(int $limit = 30): array
    {
        try {
            $items = Cache::remember('newsmaker.news.latest.v1', now()->addMinutes(5), function (): array {
                $response = Http::accept('text/html')
                    ->timeout(20)
                    ->retry(1, 300)
                    ->get(self::PAGE_URL);

                $response->throw();

                return $this->parse($response->body());
            });

            return array_slice($items, 0, max(0, $limit));
        } catch (ConnectionException|RequestException|\RuntimeException $exception) {
            Log::warning('Unable to load NewsMaker headlines.', [
                'message' => $exception->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parse(string $html): array
    {
        $document = new \DOMDocument('1.0', 'UTF-8');
        $previousErrors = libxml_use_internal_errors(true);

        try {
            $loaded = $document->loadHTML($html, LIBXML_NONET | LIBXML_HTML_NODEFDTD);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousErrors);
        }

        if (! $loaded) {
            throw new UnexpectedValueException('The NewsMaker page could not be parsed.');
        }

        $items = [];

        foreach ($document->getElementsByTagName('a') as $link) {
            $href = $link->getAttribute('href');
            $linkText = preg_replace('/\s+/u', ' ', trim($link->textContent)) ?? '';

            if (! str_starts_with($href, '/id/news/') || mb_strtoupper($linkText) !== 'BACA SELENGKAPNYA') {
                continue;
            }

            $card = $this->findArticleCard($link);

            if (! $card) {
                continue;
            }

            $title = trim($card->getElementsByTagName('h3')->item(0)?->textContent ?? '');
            $slug = basename(parse_url($href, PHP_URL_PATH) ?: '');

            if ($title === '' || $slug === '') {
                continue;
            }

            $category = 'Berita';
            $excerpt = '';
            $publishedAt = null;

            foreach ($card->getElementsByTagName('p') as $paragraph) {
                $text = preg_replace('/\s+/u', ' ', trim($paragraph->textContent)) ?? '';

                if ($text === '') {
                    continue;
                }

                if (preg_match('/^\d{1,2}\s+[A-Za-z]{3}\s+\d{4}\s+-\s+\d{2}\.\d{2}$/', $text) === 1) {
                    $publishedAt = $this->parseDate($text);
                    continue;
                }

                if ($category === 'Berita') {
                    $category = $text;
                } elseif ($excerpt === '') {
                    $excerpt = $text;
                }
            }

            $image = $card->getElementsByTagName('img')->item(0)?->getAttribute('src');

            $items[] = [
                'id' => 'newsmaker:'.$slug,
                'title' => $title,
                'category' => $category,
                'source' => 'NewsMaker',
                'published_at' => $publishedAt,
                'excerpt' => $excerpt,
                'image' => $this->safeImageUrl($image),
                'url' => 'https://newsmaker.id'. $href,
            ];
        }

        return $items;
    }

    private function findArticleCard(\DOMNode $link): ?\DOMElement
    {
        $node = $link->parentNode;

        for ($depth = 0; $node instanceof \DOMElement && $depth < 8; $depth++, $node = $node->parentNode) {
            if ($node->getElementsByTagName('h3')->length > 0 && $node->getElementsByTagName('img')->length > 0) {
                return $node;
            }
        }

        return null;
    }

    private function parseDate(string $date): ?string
    {
        $parsed = DateTimeImmutable::createFromFormat('!d M Y - H.i', $date, new DateTimeZone('Asia/Jakarta'));

        return $parsed ? $parsed->format(DATE_ATOM) : null;
    }

    private function safeImageUrl(?string $url): ?string
    {
        if (! $url || filter_var($url, FILTER_VALIDATE_URL) === false) {
            return null;
        }

        return parse_url($url, PHP_URL_SCHEME) === 'https'
            && in_array(parse_url($url, PHP_URL_HOST), ['portalnews.newsmaker.id', 'newsmaker.id'], true)
            ? $url
            : null;
    }
}
