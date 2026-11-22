<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NewsPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_news_page_displays_live_feed_content_with_local_detail_links(): void
    {
        Http::fake([
            'www.equityworld-futures.com/api/portalnews*' => Http::response([
                'current_page' => 1,
                'last_page' => 1,
                'total' => 2,
                'data' => [
                    $this->newsItem('Market Update Featured', 'featured-market-story'),
                    array_replace($this->newsItem('Market Update Story', 'market-story'), [
                        'id' => 124085,
                        'kategori' => ['name' => 'Gold'],
                    ]),
                ],
            ]),
        ]);

        $this->get(route('berita'))
            ->assertOk()
            ->assertSeeText('Market Update Featured')
            ->assertSeeText('Market Update Story')
            ->assertSee('href="'.route('berita', ['category' => 'market-update']).'"', false)
            ->assertSee('href="'.route('berita', ['category' => 'gold']).'"', false)
            ->assertSee('href="'.route('berita.detail', ['article' => 124084]).'"', false)
            ->assertSee('Menampilkan semua 2 berita', false);
    }

    public function test_top_news_is_selected_from_headline_signals_and_excluded_from_latest_stories(): void
    {
        Http::fake([
            'www.equityworld-futures.com/api/portalnews*' => Http::response([
                'current_page' => 1,
                'last_page' => 1,
                'total' => 3,
                'data' => [
                    array_replace($this->newsItem('Euro Holds Steady as Traders Await Data', 'latest-euro'), [
                        'id' => 124084,
                        'created_at' => '2026-11-11T04:00:00.000Z',
                    ]),
                    array_replace($this->newsItem('Fed Cuts Rates as Inflation Surges', 'top-fed-inflation'), [
                        'id' => 124085,
                        'kategori' => ['name' => 'Fiscal & Monetary'],
                        'created_at' => '2026-11-10T04:00:00.000Z',
                    ]),
                    array_replace($this->newsItem('Gold Prices Remain Steady', 'steady-gold'), [
                        'id' => 124086,
                        'kategori' => ['name' => 'Gold'],
                        'created_at' => '2026-11-09T04:00:00.000Z',
                    ]),
                ],
            ]),
        ]);

        $response = $this->get(route('berita'));
        $response->assertOk()
            ->assertSeeText('TOP NEWS')
            ->assertSeeText('Fed Cuts Rates as Inflation Surges')
            ->assertSeeText('Euro Holds Steady as Traders Await Data')
            ->assertSeeText('Gold Prices Remain Steady')
            ->assertSee('href="'.route('berita.detail', ['article' => 124085]).'"', false);

        $this->assertSame(2, substr_count($response->getContent(), 'Fed Cuts Rates as Inflation Surges'));
    }

    public function test_homepage_displays_latest_news_and_official_announcements(): void
    {
        Http::fake([
            'www.equityworld-futures.com/api/portalnews*' => Http::response([
                'current_page' => 1,
                'last_page' => 1,
                'total' => 3,
                'data' => [
                    $this->newsItem('Latest Homepage Story', 'latest-homepage-story'),
                    $this->newsItem('Second Homepage Story', 'second-homepage-story'),
                    $this->newsItem('Third Homepage Story', 'third-homepage-story'),
                ],
            ]),
            'www.equityworld-futures.com/api/berita' => Http::response([
                $this->announcementItem(43, 'Pemberitahuan Perubahan Jam Perdagangan', 'pemberitahuan-perubahan-jam-perdagangan', '2026-10-29T08:48:13.000000Z', 'latest-announcement.jpg', '<p>Diberitahukan,&nbsp; penyesuaian jam perdagangan.</p>'),
                $this->announcementItem(42, 'Pemberitahuan Jadwal Libur Market Hong Kong', 'pemberitahuan-jadwal-libur-market-hong-kong', '2026-09-22T03:49:49.000000Z', 'hong-kong.jpg', '<p>Jadwal libur market Hong Kong.</p>'),
                $this->announcementItem(41, 'PENGUMUMAN ROLL OVER HKK50_BBJ/HKK5U_BBJ TGL 28 SEPTEMBER 2026', 'roll-over-hkk', '2026-09-22T02:02:28.000000Z', 'rollover-hkk.jpg', '<p>Kontrak HKK50 rollover.</p>'),
                $this->announcementItem(40, 'PENGUMUMAN ROLL OVER BCO 10_BBJ/ BCO 5U_BBJ TGL 25 SEPTEMBER 2026', 'roll-over-bco', '2026-09-22T02:01:48.000000Z', 'rollover-bco.jpg', '<p>Kontrak BCO rollover.</p>'),
                $this->announcementItem(39, 'PENGUMUMAN ROLL OVER JPK50_BBJ/JPK5U_BBJ TGL 8 September 2026', 'roll-over-jpk', '2026-08-31T06:29:40.000000Z', 'rollover-jpk.jpg', '<p>Kontrak JPK rollover.</p>'),
                $this->announcementItem(38, 'Pemberitahuan Jadwal Early Closing Market 7 September 2026', 'early-closing', '2026-08-28T04:59:16.000000Z', 'early-closing.jpg', '<p>Jadwal early closing market.</p>'),
                $this->announcementItem(37, 'Older Announcement', 'older-announcement', '2026-08-19T06:27:41.000000Z', 'older.jpg', '<p>Pengumuman lama.</p>'),
            ]),
        ]);

        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertSeeText('Latest Homepage Story')
            ->assertSeeText('Second Homepage Story')
            ->assertSeeText('Third Homepage Story')
            ->assertSeeText('Pengumuman Terbaru')
            ->assertSeeText('Pemberitahuan Perubahan Jam Perdagangan')
            ->assertSeeText('PENGUMUMAN ROLL OVER BCO 10_BBJ/ BCO 5U_BBJ TGL 25 SEPTEMBER 2026')
            ->assertSeeText('Diberitahukan, penyesuaian jam perdagangan.')
            ->assertSee('class="announcement-heading"', false)
            ->assertSee('href="'.route('pengumuman.detail', ['slug' => 'roll-over-bco']).'"', false)
            ->assertSee('src="https://ewf-admin.newsmaker.id/img/berita/latest-announcement.jpg"', false)
            ->assertDontSee('Lihat Semua Pengumuman')
            ->assertDontSeeText('Older Announcement')
            ->assertSee('href="'.route('berita.detail', ['article' => 124084]).'"', false);

        Http::assertSent(fn ($request): bool => str_contains($request->url(), 'per_page=12'));
        $this->assertSame(6, substr_count($response->getContent(), 'class="news-card announcement-card"'));
    }

    public function test_homepage_shows_a_fallback_link_when_official_announcements_are_unavailable(): void
    {
        Http::fake([
            'www.equityworld-futures.com/api/portalnews*' => Http::response([
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0,
                'data' => [],
            ]),
            'www.equityworld-futures.com/api/berita' => Http::response([], 503),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeText('Pengumuman terbaru belum dapat dimuat')
            ->assertSee('href="https://www.equityworld-futures.com/umum/informasi"', false);
    }

    public function test_announcement_detail_displays_official_content_and_sanitizes_unsafe_markup(): void
    {
        Http::fake([
            'www.equityworld-futures.com/api/berita' => Http::response([
                $this->announcementItem(
                    7,
                    'Pemberitahuan Jam Perdagangan',
                    'pemberitahuan-jam-perdagangan',
                    '2026-10-29T08:48:13.000000Z',
                    'announcement-detail.jpg',
                    '<p onclick="alert(1)">Jadwal mulai <em>2 November 2026</em>.</p><table style="border-collapse: collapse; width: 46%;" border="1"><tbody><tr><th>Market</th><th>Jam</th></tr><tr><td>GOLD</td><td>06:00 WIB</td></tr></tbody></table><script>alert("unsafe")</script>',
                ),
            ]),
        ]);

        $this->get(route('pengumuman.detail', ['slug' => 'pemberitahuan-jam-perdagangan']))
            ->assertOk()
            ->assertSeeText('Detail Informasi')
            ->assertSeeText('Pemberitahuan Jam Perdagangan')
            ->assertSeeText('Jadwal mulai')
            ->assertSeeText('2 November 2026')
            ->assertSeeText('06:00 WIB')
            ->assertSee('<table', false)
            ->assertSee('<em>2 November 2026</em>', false)
            ->assertDontSee('onclick=', false)
            ->assertDontSee('alert("unsafe")', false)
            ->assertSee('href="'.route('home').'#pengumuman-bursa"', false);
    }

    public function test_unknown_announcement_slug_returns_not_found(): void
    {
        Http::fake([
            'www.equityworld-futures.com/api/berita' => Http::response([]),
        ]);

        $this->get(route('pengumuman.detail', ['slug' => 'missing-announcement']))
            ->assertNotFound();
    }

    public function test_category_filter_has_complete_results_and_persists_across_pages(): void
    {
        Http::fake(function ($request) {
            parse_str(parse_url($request->url(), PHP_URL_QUERY) ?? '', $query);

            if (($query['page'] ?? null) === '1') {
                return Http::response([
                    'current_page' => 1,
                    'last_page' => 2,
                    'total' => 23,
                    'data' => [
                        ...$this->categoryItems('Oil', 1, 10),
                        array_replace($this->newsItem('Market Update Topic Story', 'market-update-topic-story'), [
                            'id' => 200,
                            'kategori' => ['name' => 'Market Update'],
                        ]),
                    ],
                ]);
            }

            return Http::response([
                'current_page' => 2,
                'last_page' => 2,
                'total' => 23,
                'data' => $this->categoryItems('Gold', 1, 12),
            ]);
        });

        $pageOne = $this->get(route('berita', ['category' => 'gold']))
            ->assertOk()
            ->assertSeeText('Gold Topic Story 1')
            ->assertSeeText('Gold Topic Story 10')
            ->assertSee('href="'.e(route('berita', ['page' => 2, 'category' => 'gold'])).'"', false)
            ->assertSee('href="'.route('berita', ['category' => 'oil']).'"', false);

        $pageOne->assertSee('aria-current="page"', false);

        $this->get(route('berita', ['category' => 'gold', 'page' => 2]))
            ->assertOk()
            ->assertSeeText('Gold Topic Story 11')
            ->assertSeeText('Gold Topic Story 12')
            ->assertDontSee('Gold Topic Story 1</h3>', false)
            ->assertSee('href="'.e(route('berita', ['page' => 1, 'category' => 'gold'])).'"', false)
            ->assertSee('href="'.route('berita', ['category' => 'gold']).'"', false);

        Http::assertSentCount(2);
        Http::assertSent(fn ($request): bool => str_contains($request->url(), 'per_page=50'));
    }

    public function test_news_page_shows_an_explicit_message_when_the_source_is_unavailable(): void
    {
        Http::fake([
            'www.equityworld-futures.com/api/portalnews*' => Http::response([], 503),
        ]);

        $this->get(route('berita'))
            ->assertOk()
            ->assertSeeText('Berita belum dapat dimuat. Silakan coba beberapa saat lagi.');
    }

    public function test_news_detail_displays_the_full_article_without_leaving_the_site(): void
    {
        Http::fake([
            'www.equityworld-futures.com/api/portalnews/124084' => Http::response([
                'data' => array_replace($this->newsItem('Full Market Article', 'full-market-article'), [
                    'content' => '<p>Paragraf berita pertama.</p><p>Paragraf berita kedua.</p>',
                ]),
            ]),
        ]);

        $this->get(route('berita.detail', ['article' => 124084]))
            ->assertOk()
            ->assertSeeText('Full Market Article')
            ->assertSeeText('Paragraf berita pertama.')
            ->assertSeeText('Paragraf berita kedua.')
            ->assertSee(route('berita'), false)
            ->assertDontSee('target="_blank"', false);

        Http::assertSent(fn ($request): bool => $request->url() === 'https://www.equityworld-futures.com/api/portalnews/124084');
    }

    public function test_news_detail_escapes_unsafe_article_markup(): void
    {
        Http::fake([
            'www.equityworld-futures.com/api/portalnews/124084' => Http::response([
                'data' => array_replace($this->newsItem('Safe Article', 'safe-article'), [
                    'content' => '<p>Safe text</p><script>alert("unsafe")</script><p>More text</p>',
                ]),
            ]),
        ]);

        $this->get(route('berita.detail', ['article' => 124084]))
            ->assertOk()
            ->assertSeeText('Safe text')
            ->assertSeeText('More text')
            ->assertDontSee('alert("unsafe")', false);
    }

    public function test_non_numeric_news_detail_identifiers_return_not_found(): void
    {
        $this->get('/berita/not-a-number')->assertNotFound();
    }

    private function newsItem(string $title, string $slug): array
    {
        return [
            'id' => 124084,
            'title' => $title,
            'titles' => ['ewf' => $title],
            'slug' => $slug,
            'content' => '<p>Ringkasan berita pasar.</p>',
            'created_at' => '2026-11-11T01:22:00.000Z',
            'kategori' => ['name' => 'Market Update'],
            'images' => [],
            'image' => null,
        ];
    }

    private function announcementItem(int $id, string $title, string $slug, string $publishedAt, string $image, string $content): array
    {
        return [
            'id' => $id,
            'slug' => $slug,
            'judul' => $title,
            'created_at' => $publishedAt,
            'isi' => $content,
            'image' => $image,
        ];
    }

    private function categoryItems(string $category, int $firstNumber, int $count): array
    {
        return array_map(function (int $number) use ($category): array {
            return array_replace(
                $this->newsItem("{$category} Topic Story {$number}", "topic-story-{$number}"),
                [
                    'id' => ($category === 'Gold' ? 2000 : 1000) + $number,
                    'kategori' => ['name' => $category],
                ],
            );
        }, range($firstNumber, $firstNumber + $count - 1));
    }
}
