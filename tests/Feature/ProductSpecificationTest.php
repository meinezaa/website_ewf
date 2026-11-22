<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ProductSpecificationTest extends TestCase
{
    public function test_homepage_product_links_open_the_products_and_matching_specifications(): void
    {
        Http::fake([
            'www.equityworld-futures.com/api/portalnews*' => Http::response([
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0,
                'data' => [],
            ]),
            'www.equityworld-futures.com/api/berita' => Http::response([]),
        ]);

        $response = $this->get(route('home'))->assertOk();

        $response->assertSee('href="' . route('produk') . '"', false);
        $response->assertSee(asset('images/ewf_logo 1.png'))
            ->assertSee('href="' . route('home') . '#tentang"', false)
            ->assertSee('href="' . route('berita') . '"', false)
            ->assertSee('href="' . route('home') . '#karir"', false);

        foreach (['gol250', 'gol', 'ole', 'gu10f', 'eu1010', 'uc10f'] as $productId) {
            $response->assertSee(
                'href="' . route('produk.spesifikasi', ['product' => $productId]) . '"',
                false,
            );
        }

        $response->assertSee('href="' . route('live-quotes') . '"', false)
            ->assertDontSeeText('DX1010_BBJ')
            ->assertDontSeeText('EU1 010_BBJ')
            ->assertDontSee('Produk JFX')
            ->assertDontSee('Produk SPA')
            ->assertSee('<svg class="menu-dropdown-icon"', false);

        foreach (config('products') as $product) {
            if ($product['market'] === 'Sistem Perdagangan Alternatif (SPA)') {
                $response->assertSeeText($product['code']);
            }
        }
    }

    public function test_product_listing_uses_local_specification_routes(): void
    {
        $specificationRoute = str_replace(
            '/',
            '\\/',
            route('produk.spesifikasi', ['product' => '__PRODUCT__']),
        );

        $this->get(route('produk'))
            ->assertOk()
            ->assertSee($specificationRoute, false)
            ->assertSee("id:'gol250'", false)
            ->assertSee(asset('images/ewf_logo 1.png'));
    }

    public function test_each_product_has_a_specification_page(): void
    {
        foreach (config('products') as $id => $product) {
            $this->get(route('produk.spesifikasi', ['product' => $id]))
                ->assertOk()
                ->assertSeeText($product['name'])
                ->assertSee(asset('images/' . $product['image']));
        }
    }

    public function test_unknown_product_specification_returns_not_found(): void
    {
        $this->get(route('produk.spesifikasi', ['product' => 'unknown-product']))
            ->assertNotFound();
    }

    public function test_live_quotes_page_shows_a_tradingview_reference_for_every_product(): void
    {
        $response = $this->get(route('live-quotes'))->assertOk();

        foreach (config('products') as $product) {
            $response->assertSeeText($product['code'])
                ->assertSeeText($product['quote_reference'])
                ->assertSeeText($product['quote_symbol']);
        }

        $this->assertSame(
            count(array_filter(
                config('products'),
                fn ($product): bool => $product['quote_embed'] ?? true,
            )),
            substr_count($response->getContent(), 'embed-widget-single-quote.js'),
        );
        $response->assertSeeText('Kutipan FCPO tidak tersedia untuk ditampilkan di widget TradingView.')
            ->assertSeeText('OANDA:JP225USD')
            ->assertDontSeeText('TVC:NI225');
        $response->assertSeeText('bukan harga bid/ask atau kuotasi transaksi kontrak EWF');
        $response->assertSeeText('Live Chart Pasar')
            ->assertSeeText('Emas (XAU/USD)')
            ->assertSeeText('Nikkei 225')
            ->assertSeeText('Hang Seng')
            ->assertSee('aria-label="Chart sebelumnya"', false)
            ->assertSee('aria-label="Chart berikutnya"', false)
            ->assertSee('scroll-snap-type:x mandatory', false)
            ->assertSee('https://www.tradingview.com/widgetembed/?symbol=OANDA%3AXAUUSD', false)
            ->assertSee('https://www.tradingview.com/widgetembed/?symbol=OANDA%3AJP225USD', false)
            ->assertSee('https://www.tradingview.com/widgetembed/?symbol=HSI%3AHSI', false);
        $this->assertSame(3, substr_count($response->getContent(), 'title="Chart '));
    }
}
