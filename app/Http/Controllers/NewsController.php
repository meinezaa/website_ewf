<?php

namespace App\Http\Controllers;

use App\Services\NewsFeed;
use App\Services\NewsMakerFeed;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use UnexpectedValueException;

class NewsController extends Controller
{
    private const TOPIC_GROUPS = [
        'analysis-opinion' => 'Analisis & Opini',
        'global-economy' => 'Ekonomi Global',
        'market-analysis' => 'Analisis Pasar',
        'commodities' => 'Komoditas',
        'indices' => 'Indeks',
        'forex' => 'Forex',
        'crypto' => 'Crypto',
        'other' => 'Topik lainnya',
    ];

    private const CATEGORY_GROUPS = [
        'analysis-opinion' => 'analysis-opinion',
        'analisis-opini' => 'analysis-opinion',
        'global-economy' => 'global-economy',
        'global' => 'global-economy',
        'economy' => 'global-economy',
        'fiscal-monetary' => 'global-economy',
        'market-analysis' => 'market-analysis',
        'market-update' => 'market-analysis',
        'market-development' => 'market-analysis',
        'gold' => 'commodities',
        'oil' => 'commodities',
        'silver' => 'commodities',
        'commodity' => 'commodities',
        'nikkei' => 'indices',
        'hang-seng' => 'indices',
        'hangseng' => 'indices',
        'index' => 'indices',
        'indices' => 'indices',
        'audusd' => 'forex',
        'aud-usd' => 'forex',
        'eurusd' => 'forex',
        'eur-usd' => 'forex',
        'gbpusd' => 'forex',
        'gbp-usd' => 'forex',
        'usdjpy' => 'forex',
        'usd-jpy' => 'forex',
        'usdchf' => 'forex',
        'usd-chf' => 'forex',
        'us-dollar' => 'forex',
        'dollar' => 'forex',
        'forex' => 'forex',
        'currency' => 'forex',
        'currencies' => 'forex',
        'crypto' => 'crypto',
        'cryptocurrency' => 'crypto',
    ];

    public function __construct(private readonly NewsFeed $newsFeed, private readonly NewsMakerFeed $newsMakerFeed) {}

    public function index(Request $request): View
    {
        $page = max(1, $request->integer('page', 1));
        $selectedCategory = $request->query('category', '');
        $requestedGroup = $request->query('group', '');
        abort_unless(is_string($selectedCategory) && is_string($requestedGroup), 404);
        $error = null;
        $topNews = null;

        $items = [];

        try {
            $catalog = $this->newsFeed->catalog();
            $items = array_map(
                fn (array $item): array => [
                    ...$item,
                    'url' => route('berita.detail', ['article' => $item['id']]),
                ],
                $catalog['items'],
            );
        } catch (ConnectionException|RequestException $exception) {
            Log::warning('Unable to load Equityworld news.', [
                'page' => $page,
                'category' => $selectedCategory,
                'message' => $exception->getMessage(),
            ]);
            $error = 'Berita belum dapat dimuat. Silakan coba beberapa saat lagi.';
        } catch (UnexpectedValueException $exception) {
            Log::warning('Equityworld news API returned an invalid response.', [
                'page' => $page,
                'category' => $selectedCategory,
                'message' => $exception->getMessage(),
            ]);
            $error = 'Format data berita sedang bermasalah. Silakan coba beberapa saat lagi.';
        }

        $items = array_merge($items, $this->newsMakerFeed->latest(120));
        usort(
            $items,
            static fn (array $left, array $right): int => strcmp((string) ($right['published_at'] ?? ''), (string) ($left['published_at'] ?? '')),
        );
        $items = $this->newsFeed->distinctFormattedNewsItems($items);
        $categoryOptions = [];

        foreach ($items as $item) {
            $slug = $this->newsFeed->categorySlug($item['category']);
            $categoryOptions[$slug] = $item['category'];
        }

        asort($categoryOptions, SORT_NATURAL | SORT_FLAG_CASE);
        abort_unless($selectedCategory === '' || array_key_exists($selectedCategory, $categoryOptions), 404);

        $categoryCounts = [];
        $categoryGroups = [];

        foreach ($items as $item) {
            $slug = $this->newsFeed->categorySlug($item['category']);
            $categoryCounts[$slug] = ($categoryCounts[$slug] ?? 0) + 1;
            $categoryGroups[$slug] = self::CATEGORY_GROUPS[$slug] ?? 'other';
        }

        $topicGroups = [];

        foreach (self::TOPIC_GROUPS as $groupKey => $groupLabel) {
            $groupCategories = array_values(array_filter(
                array_keys($categoryOptions),
                fn (string $slug): bool => ($categoryGroups[$slug] ?? 'other') === $groupKey,
            ));

            if ($groupCategories === []) {
                continue;
            }

            $topicGroups[$groupKey] = [
                'label' => $groupLabel,
                'categories' => $groupCategories,
                'count' => array_sum(array_map(fn (string $slug): int => $categoryCounts[$slug] ?? 0, $groupCategories)),
            ];
        }

        $selectedGroup = $selectedCategory !== ''
            ? ($categoryGroups[$selectedCategory] ?? 'other')
            : $requestedGroup;
        abort_unless($selectedGroup === '' || array_key_exists($selectedGroup, $topicGroups), 404);

        if ($selectedCategory !== '') {
            $items = array_values(array_filter(
                $items,
                fn (array $item): bool => $this->newsFeed->categorySlug($item['category']) === $selectedCategory,
            ));
        } elseif ($selectedGroup !== '') {
            $groupCategorySlugs = $topicGroups[$selectedGroup]['categories'];
            $items = array_values(array_filter(
                $items,
                fn (array $item): bool => in_array($this->newsFeed->categorySlug($item['category']), $groupCategorySlugs, true),
            ));
        }

        $topNews = $this->newsFeed->topNews($items);

        if ($topNews !== null) {
            $topNews['url'] ??= route('berita.detail', ['article' => $topNews['id']]);
            $items = array_values(array_filter(
                $items,
                fn (array $item): bool => $item['id'] !== $topNews['id'],
            ));
        }

        $totalNews = count($items) + ($topNews === null ? 0 : 1);
        $perPage = 10;
        $lastPage = max(1, (int) ceil(count($items) / $perPage));
        $page = min($page, $lastPage);
        $news = array_slice($items, ($page - 1) * $perPage, $perPage);
        $error = $totalNews === 0 ? $error : null;

        return view('berita', [
            'news' => $news,
            'topNews' => $topNews,
            'categories' => $categoryOptions,
            'categoryCounts' => $categoryCounts,
            'topicGroups' => $topicGroups,
            'selectedCategory' => $selectedCategory,
            'selectedGroup' => $selectedGroup,
            'currentPage' => $page,
            'lastPage' => $lastPage,
            'totalNews' => $totalNews,
            'error' => $error,
        ]);
    }

    public function show(string $article): View
    {
        try {
            $payload = $this->newsFeed->article($article);
        } catch (RequestException $exception) {
            if ($exception->response->status() === 404) {
                abort(404);
            }

            Log::warning('Unable to load Equityworld news article.', [
                'article' => $article,
                'message' => $exception->getMessage(),
            ]);

            return view('berita-detail', ['article' => null, 'error' => true]);
        } catch (ConnectionException|UnexpectedValueException $exception) {
            Log::warning('Unable to load Equityworld news article.', [
                'article' => $article,
                'message' => $exception->getMessage(),
            ]);

            return view('berita-detail', ['article' => null, 'error' => true]);
        }

        return view('berita-detail', [
            'article' => $this->newsFeed->formatArticle($payload),
            'error' => false,
        ]);
    }
}
