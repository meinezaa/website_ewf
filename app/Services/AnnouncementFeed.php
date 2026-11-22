<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use UnexpectedValueException;

class AnnouncementFeed
{
    private const API_URL = 'https://www.equityworld-futures.com/api/berita';

    private const SITE_URL = 'https://www.equityworld-futures.com';

    private const IMAGE_URL = 'https://ewf-admin.newsmaker.id/img/berita';

    private const CACHE_KEY = 'equityworld.announcements.v2';

    private const STALE_CACHE_KEY = 'equityworld.announcements.stale.v1';

    private const CONTENT_TAGS = [
        'a', 'b', 'blockquote', 'br', 'caption', 'col', 'colgroup', 'div', 'em',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'i', 'img', 'li', 'ol',
        'p', 'span', 'strong', 'table', 'tbody', 'td', 'tfoot', 'th', 'thead',
        'tr', 'u', 'ul',
    ];

    private const VOID_CONTENT_TAGS = ['br', 'col', 'hr', 'img'];

    private const DROP_CONTENT_TAGS = ['audio', 'embed', 'form', 'iframe', 'object', 'script', 'style', 'svg', 'video'];

    /**
     * @throws ConnectionException
     * @throws RequestException
     * @throws UnexpectedValueException
     */
    public function latest(int $limit = 6): array
    {
        return array_slice($this->all(), 0, max(0, $limit));
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     * @throws UnexpectedValueException
     */
    public function findBySlug(string $slug): ?array
    {
        foreach ($this->all() as $announcement) {
            if ($announcement['slug'] === $slug) {
                return $announcement;
            }
        }

        return null;
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     * @throws UnexpectedValueException
     */
    private function all(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addMinutes(10), function (): array {
            try {
                $response = Http::acceptJson()
                    ->timeout(25)
                    ->retry(1, 300)
                    ->get(self::API_URL);

                $response->throw();
                $payload = $response->json();

                if (! is_array($payload) || ! array_is_list($payload)) {
                    throw new UnexpectedValueException('The Equityworld announcement feed returned an invalid response.');
                }

                $announcements = [];

                foreach ($payload as $item) {
                    if (! is_array($item)) {
                        continue;
                    }

                    $announcement = $this->formatAnnouncement($item);

                    if ($announcement !== null) {
                        $announcements[] = $announcement;
                    }
                }

                usort(
                    $announcements,
                    static fn (array $left, array $right): int => strcmp($right['published_at'] ?? '', $left['published_at'] ?? ''),
                );

                Cache::put(self::STALE_CACHE_KEY, $announcements, now()->addDays(2));

                return $announcements;
            } catch (ConnectionException|RequestException|UnexpectedValueException $exception) {
                $staleAnnouncements = Cache::get(self::STALE_CACHE_KEY);

                if (is_array($staleAnnouncements)) {
                    return $staleAnnouncements;
                }

                throw $exception;
            }
        });
    }

    private function formatAnnouncement(array $item): ?array
    {
        $id = (int) ($item['id'] ?? 0);
        $title = trim((string) ($item['judul'] ?? ''));
        $slug = trim((string) ($item['slug'] ?? ''));
        $publishedAt = $item['created_at'] ?? null;

        if ($id < 1 || $title === '' || $slug === '') {
            return null;
        }

        if (! is_string($publishedAt) || strtotime($publishedAt) === false) {
            $publishedAt = null;
        }

        $content = (string) ($item['isi'] ?? $item['content'] ?? '');

        return [
            'id' => $id,
            'slug' => $slug,
            'title' => $title,
            'category' => trim((string) ($item['kategori'] ?? 'Pengumuman')) ?: 'Pengumuman',
            'published_at' => $publishedAt,
            'excerpt' => $this->excerpt($content),
            'content' => $this->sanitizeContent($content),
            'image' => $this->imageUrl($item['image'] ?? $item['image_url'] ?? null),
            'url' => route('pengumuman.detail', ['slug' => $slug]),
        ];
    }

    private function excerpt(string $content): string
    {
        $content = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', ' ', $content) ?? '';
        $content = preg_replace('#</?(?:p|div|h[1-6]|li|tr|br)\b[^>]*>#i', ' ', $content) ?? '';
        $content = html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $content = str_replace("\u{00A0}", ' ', $content);
        $content = preg_replace('/\s+/u', ' ', trim($content)) ?? trim($content);

        return Str::limit($content, 180);
    }

    private function sanitizeContent(string $content): string
    {
        if (trim($content) === '') {
            return '';
        }

        $document = new \DOMDocument('1.0', 'UTF-8');
        $previousErrors = libxml_use_internal_errors(true);

        try {
            $loaded = $document->loadHTML(
                '<!DOCTYPE html><html><body><div id="announcement-content">'.$content.'</div></body></html>',
                LIBXML_NONET | LIBXML_HTML_NODEFDTD,
            );
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousErrors);
        }

        $wrapper = $loaded ? $document->getElementById('announcement-content') : null;

        if (! $wrapper instanceof \DOMElement) {
            throw new UnexpectedValueException('The Equityworld announcement content could not be parsed.');
        }

        $safeContent = '';

        foreach ($wrapper->childNodes as $child) {
            $safeContent .= $this->sanitizeNode($child);
        }

        return $safeContent;
    }

    private function sanitizeNode(\DOMNode $node): string
    {
        if ($node instanceof \DOMText) {
            return htmlspecialchars($node->nodeValue ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }

        if (! $node instanceof \DOMElement) {
            return '';
        }

        $tag = strtolower($node->tagName);

        if (in_array($tag, self::DROP_CONTENT_TAGS, true)) {
            return '';
        }

        $children = '';

        foreach ($node->childNodes as $child) {
            $children .= $this->sanitizeNode($child);
        }

        if (! in_array($tag, self::CONTENT_TAGS, true)) {
            return $children;
        }

        $attributes = $this->safeContentAttributes($tag, $node);

        if (in_array($tag, self::VOID_CONTENT_TAGS, true)) {
            return '<'.$tag.$attributes.'>';
        }

        return '<'.$tag.$attributes.'>'.$children.'</'.$tag.'>';
    }

    private function safeContentAttributes(string $tag, \DOMElement $element): string
    {
        $attributes = [];

        if ($element->hasAttribute('style')) {
            $style = $this->safeContentStyle($element->getAttribute('style'));

            if ($style !== '') {
                $attributes['style'] = $style;
            }
        }

        if ($tag === 'table' && preg_match('/^[01]$/', $element->getAttribute('border')) === 1) {
            $attributes['border'] = $element->getAttribute('border');
        }

        if (in_array($tag, ['td', 'th'], true)) {
            foreach (['colspan', 'rowspan'] as $name) {
                $value = filter_var($element->getAttribute($name), FILTER_VALIDATE_INT);

                if ($value !== false && $value >= 1 && $value <= 100) {
                    $attributes[$name] = (string) $value;
                }
            }
        }

        if ($tag === 'a') {
            $href = $element->getAttribute('href');

            if ($this->isSafeLink($href)) {
                $attributes['href'] = $href;

                if (preg_match('/^https?:\/\//i', $href) === 1) {
                    $attributes['target'] = '_blank';
                    $attributes['rel'] = 'noopener noreferrer';
                }
            }
        }

        if ($tag === 'img') {
            $src = $this->safeImageSource($element->getAttribute('src'));

            if ($src !== null) {
                $attributes['src'] = $src;
            } else {
                return '';
            }

            $attributes['alt'] = $element->getAttribute('alt');
        }

        $serialized = '';

        foreach ($attributes as $name => $value) {
            $serialized .= ' '.$name.'="'.htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'"';
        }

        return $serialized;
    }

    private function safeContentStyle(string $style): string
    {
        $safeDeclarations = [];

        foreach (explode(';', $style) as $declaration) {
            if (! str_contains($declaration, ':')) {
                continue;
            }

            [$property, $value] = array_map('trim', explode(':', $declaration, 2));
            $property = strtolower($property);
            $value = strtolower($value);

            if ($property === 'text-align' && in_array($value, ['center', 'justify', 'left', 'right'], true)) {
                $safeDeclarations[] = $property.': '.$value;
            } elseif ($property === 'width' && preg_match('/^\d+(?:\.\d+)?(?:%|px)?$/', $value) === 1) {
                $safeDeclarations[] = $property.': '.$value;
            } elseif ($property === 'border-collapse' && in_array($value, ['collapse', 'separate'], true)) {
                $safeDeclarations[] = $property.': '.$value;
            }
        }

        return implode('; ', $safeDeclarations);
    }

    private function isSafeLink(string $url): bool
    {
        if (str_starts_with($url, '/') && ! str_starts_with($url, '//') && ! str_contains($url, '\\')) {
            return true;
        }

        return filter_var($url, FILTER_VALIDATE_URL) !== false
            && in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)
            && parse_url($url, PHP_URL_USER) === null
            && parse_url($url, PHP_URL_PASS) === null;
    }

    private function safeImageSource(string $src): ?string
    {
        if (str_starts_with($src, '/img/berita/')) {
            $path = substr($src, strlen('/img/berita/'));

            if ($path !== '' && ! str_contains($path, '..')) {
                return self::IMAGE_URL.'/'.implode('/', array_map('rawurlencode', explode('/', $path)));
            }
        }

        if (
            filter_var($src, FILTER_VALIDATE_URL) !== false
            && parse_url($src, PHP_URL_SCHEME) === 'https'
            && in_array(parse_url($src, PHP_URL_HOST), ['ewf-admin.newsmaker.id', 'www.equityworld-futures.com'], true)
        ) {
            return $src;
        }

        return null;
    }

    private function imageUrl(mixed $image): ?string
    {
        if (! is_string($image) || trim($image) === '') {
            return null;
        }

        $image = trim($image);

        if (str_starts_with($image, '/img/berita/')) {
            $path = substr($image, strlen('/img/berita/'));

            if ($path === '' || str_contains($path, '..')) {
                return null;
            }

            return self::IMAGE_URL.'/'.implode('/', array_map('rawurlencode', explode('/', $path)));
        }

        if (filter_var($image, FILTER_VALIDATE_URL)) {
            $host = parse_url($image, PHP_URL_HOST);

            return parse_url($image, PHP_URL_SCHEME) === 'https' && $host === 'ewf-admin.newsmaker.id'
                ? $image
                : null;
        }

        $path = ltrim($image, '/');

        if ($path === '' || str_contains($path, '..')) {
            return null;
        }

        $path = implode('/', array_map('rawurlencode', explode('/', $path)));

        return self::IMAGE_URL.'/'.$path;
    }
}
