<?php

namespace Tests\Feature;

use Tests\TestCase;

class FaqPageTest extends TestCase
{
    public function test_faq_page_renders_all_questions_and_interactive_controls(): void
    {
        $response = $this->get(route('faq'));
        $content = $response->getContent();

        $response->assertOk()
            ->assertSeeText('Pusat Bantuan & FAQ')
            ->assertSeeText('Apa itu PT Equityworld Futures?')
            ->assertSeeText('Bagaimana tahapan seleksi peserta magang dan PKL?')
            ->assertSeeText('Apakah peserta mendapatkan sertifikat setelah menyelesaikan program?')
            ->assertSee('id="faq-search"', false)
            ->assertSee('data-filter="magang"', false)
            ->assertSee('<link rel="stylesheet"', false)
            ->assertSee('id="faq-expand"', false)
            ->assertSeeText('Buka semua jawaban')
            ->assertDontSee('M12 5v14M5 12h14', false)
            ->assertSee('class="site-header"', false)
            ->assertSee('class="site-footer"', false);

        $this->assertSame(26, substr_count($content, '<details class="faq-item"'));
    }

    public function test_primary_pages_opt_into_navigation_transitions_in_the_document_head(): void
    {
        foreach (['home', 'tentang.kami', 'kegiatan', 'karir', 'faq'] as $routeName) {
            $content = $this->get(route($routeName))->assertOk()->getContent();
            $headEnd = strpos($content, '</head>');
            $head = substr($content, 0, $headEnd === false ? 0 : $headEnd);

            $this->assertNotFalse($headEnd);
            $this->assertStringContainsString('@view-transition', $head);
            $this->assertStringContainsString('::view-transition-old(root)', $head);
            $this->assertStringContainsString('@keyframes page-fade-out', $head);
            $this->assertStringContainsString('::view-transition-group(site-header)', $head);
        }
    }
}
