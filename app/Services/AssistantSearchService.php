<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AssistantSearchService
{
    public function search(string $message): array
    {
        $normalizedMessage = $this->normalize($message);
        $tokens = $this->expandedTokens($message);
        $intent = $this->detectIntent($tokens);

        if ($faq = $this->matchFaqRule($normalizedMessage, $tokens)) {
            return [
                'reply' => $faq['reply'],
                'results' => $faq['results'] ?? [],
                'suggestions' => $faq['suggestions'] ?? $this->defaultSuggestions(),
                'query_category' => $faq['query_category'] ?? $intent,
                'result_count' => count($faq['results'] ?? []),
            ];
        }

        if ($intent === 'general' && $this->isOutOfScope($tokens)) {
            return [
                'reply' => 'Saya hanya membantu menemukan tools, template, artikel, dan informasi di BantuKerja.online. Untuk kebutuhan lain, silakan gunakan kata kunci seperti invoice, CV, THR, slip gaji, laporan kerja, atau template dokumen.',
                'results' => $this->fallbackLinks(),
                'suggestions' => $this->defaultSuggestions(),
                'query_category' => 'general',
                'result_count' => 0,
            ];
        }

        $results = $this->searchIndex($normalizedMessage, $tokens, $intent);

        if ($results->isEmpty()) {
            $outOfScope = $this->isOutOfScope($tokens);

            return [
                'reply' => $outOfScope
                    ? 'Saya hanya membantu menemukan tools, template, artikel, dan informasi di BantuKerja.online. Untuk kebutuhan lain, silakan gunakan kata kunci seperti invoice, CV, THR, slip gaji, laporan kerja, atau template dokumen.'
                    : 'Maaf, saya belum menemukan hasil yang cocok. Coba gunakan kata kunci lain seperti invoice, CV, THR, slip gaji, surat lamaran, laporan kerja, atau lihat semua tools di halaman Tools.',
                'results' => $this->fallbackLinks(),
                'suggestions' => $this->defaultSuggestions(),
                'query_category' => $intent,
                'result_count' => 0,
            ];
        }

        $topTypes = $results->pluck('type')->unique()->values()->all();

        return [
            'reply' => $this->replyForIntent($intent, $results),
            'results' => $results->take(5)->map(fn (array $item) => [
                'title' => $item['title'],
                'type' => $item['type_label'],
                'description' => $item['description'],
                'url' => $item['url'],
            ])->values()->all(),
            'suggestions' => $this->suggestionsForIntent($intent),
            'query_category' => $intent,
            'result_count' => $results->count(),
            'result_types' => $topTypes,
        ];
    }

    public function defaultSuggestions(): array
    {
        return ['CV ATS', 'Invoice', 'Kwitansi', 'THR', 'Slip Gaji', 'Laporan Kerja', 'Template', 'Contact'];
    }

    protected function searchIndex(string $normalizedMessage, array $tokens, string $intent): Collection
    {
        return $this->buildIndex()
            ->map(function (array $item) use ($normalizedMessage, $tokens, $intent): array {
                $score = 0;
                $itemTitle = Str::lower($item['title']);
                $itemSlug = Str::lower($item['slug']);
                $itemDescription = Str::lower($item['description']);
                $itemCategory = Str::lower($item['category'] ?? '');
                $itemKeywords = collect($item['keywords'])->map(fn (string $keyword) => Str::lower($keyword))->all();

                if ($itemTitle === $normalizedMessage) {
                    $score += 50;
                } elseif (str_contains($itemTitle, $normalizedMessage)) {
                    $score += 30;
                }

                if (str_contains($itemSlug, str_replace(' ', '-', $normalizedMessage))) {
                    $score += 40;
                }

                foreach ($tokens as $token) {
                    if (in_array($token, $itemKeywords, true)) {
                        $score += 25;
                    } elseif (str_contains($itemDescription, $token)) {
                        $score += 10;
                    } elseif (str_contains($itemTitle, $token)) {
                        $score += 18;
                    }
                }

                if ($intent !== 'general' && ($item['intent'] ?? null) === $intent) {
                    $score += 10;
                }

                if ($itemCategory !== '' && collect($tokens)->contains(fn (string $token): bool => str_contains($itemCategory, $token))) {
                    $score += 15;
                }

                $item['score'] = $score;

                return $item;
            })
            ->filter(fn (array $item): bool => $item['score'] > 0)
            ->sortByDesc('score')
            ->take(5)
            ->values();
    }

    protected function buildIndex(): Collection
    {
        $cacheKey = 'assistant_search_index';
        $items = Cache::get($cacheKey);

        if (! $this->isValidCachedIndex($items)) {
            $items = $this->freshIndexItems();
            Cache::put($cacheKey, $items, now()->addMinutes(30));
        }

        return collect($items);
    }

    protected function freshIndexItems(): array
    {
            $tools = Tool::query()
                ->with(['category', 'faqs'])
                ->published()
                ->get()
                ->map(fn (Tool $tool): array => $this->normalizeToolItem($tool));

            $templates = DocumentTemplate::query()
                ->with(['category', 'faqs'])
                ->published()
                ->get()
                ->map(fn (DocumentTemplate $template): array => $this->normalizeTemplateItem($template));

            $posts = Post::query()
                ->with(['category', 'faqs'])
                ->published()
                ->get()
                ->map(fn (Post $post): array => $this->normalizePostItem($post));

            $pages = Page::query()
                ->published()
                ->whereIn('slug', ['about', 'contact', 'privacy-policy', 'terms', 'disclaimer'])
                ->get()
                ->map(fn (Page $page): array => $this->normalizePageItem($page));

        return $tools->merge($templates)->merge($posts)->merge($pages)->values()->all();
    }

    protected function isValidCachedIndex(mixed $items): bool
    {
        if (! is_array($items)) {
            return false;
        }

        foreach ($items as $item) {
            if (! is_array($item) || ! isset($item['title'], $item['type'], $item['url'])) {
                return false;
            }
        }

        return true;
    }

    protected function normalizeToolItem(Tool $tool): array
    {
        return [
            'title' => $tool->title,
            'slug' => $tool->slug,
            'type' => 'tool',
            'type_label' => 'Tool',
            'category' => $tool->category?->name ?? '',
            'description' => $tool->short_description ?? '',
            'url' => route('tools.show', $tool->slug),
            'keywords' => $this->keywordsForItem($tool->title, $tool->slug, $tool->short_description, $tool->category?->name, $tool->faqs->pluck('question')->implode(' ')),
            'intent' => $this->detectIntent($this->expandedTokens($tool->title.' '.$tool->slug.' '.$tool->short_description)),
        ];
    }

    protected function normalizeTemplateItem(DocumentTemplate $template): array
    {
        return [
            'title' => $template->title,
            'slug' => $template->slug,
            'type' => 'template',
            'type_label' => 'Template',
            'category' => $template->category?->name ?? '',
            'description' => $template->short_description ?? '',
            'url' => route('templates.show', $template->slug),
            'keywords' => $this->keywordsForItem($template->title, $template->slug, $template->short_description, $template->category?->name, $template->faqs->pluck('question')->implode(' ')),
            'intent' => $this->detectIntent($this->expandedTokens($template->title.' '.$template->slug.' '.$template->short_description)),
        ];
    }

    protected function normalizePostItem(Post $post): array
    {
        return [
            'title' => $post->title,
            'slug' => $post->slug,
            'type' => 'article',
            'type_label' => 'Artikel',
            'category' => $post->category?->name ?? '',
            'description' => $post->excerpt ?? Str::limit(strip_tags($post->content), 160),
            'url' => route('blog.show', $post->slug),
            'keywords' => $this->keywordsForItem($post->title, $post->slug, $post->excerpt, $post->category?->name, Str::limit(strip_tags($post->content), 400)),
            'intent' => $this->detectIntent($this->expandedTokens($post->title.' '.$post->slug.' '.$post->excerpt)),
        ];
    }

    protected function normalizePageItem(Page $page): array
    {
        $route = match ($page->slug) {
            'about' => route('pages.about'),
            'contact' => route('pages.contact'),
            'privacy-policy' => route('pages.privacy-policy'),
            'terms' => route('pages.terms'),
            'disclaimer' => route('pages.disclaimer'),
            default => route('pages.show', $page->slug),
        };

        return [
            'title' => $page->title,
            'slug' => $page->slug,
            'type' => 'page',
            'type_label' => 'Halaman',
            'category' => 'Informasi',
            'description' => Str::limit(strip_tags($page->content), 160),
            'url' => $route,
            'keywords' => $this->keywordsForItem($page->title, $page->slug, Str::limit(strip_tags($page->content), 300), 'informasi website', ''),
            'intent' => $page->slug === 'contact' ? 'contact' : ($page->slug === 'privacy-policy' ? 'privacy' : 'general'),
        ];
    }

    protected function keywordsForItem(?string ...$sources): array
    {
        $text = collect($sources)->filter()->implode(' ');
        $base = collect($this->expandedTokens($text));

        return $base
            ->merge($this->manualKeywordExpansion($base->all()))
            ->unique()
            ->values()
            ->all();
    }

    protected function normalize(string $value): string
    {
        return Str::of(Str::lower($value))
            ->replaceMatches('/[^a-z0-9\s\-]/', ' ')
            ->replaceMatches('/\s+/', ' ')
            ->trim()
            ->toString();
    }

    protected function tokens(string $value): array
    {
        $tokens = preg_split('/\s+/', $this->normalize($value)) ?: [];

        return collect($tokens)
            ->map(fn (string $token): string => trim($token))
            ->filter(fn (string $token): bool => $token !== '' && ! in_array($token, $this->stopwords(), true))
            ->values()
            ->all();
    }

    protected function expandedTokens(string $value): array
    {
        $tokens = collect($this->tokens($value));

        return $tokens
            ->merge($this->manualKeywordExpansion($tokens->all()))
            ->unique()
            ->values()
            ->all();
    }

    protected function manualKeywordExpansion(array $tokens): array
    {
        $synonyms = [
            'cv' => ['resume', 'daftar riwayat hidup', 'ats', 'lamaran'],
            'resume' => ['cv', 'daftar riwayat hidup'],
            'ats' => ['cv ats', 'ats friendly', 'resume'],
            'invoice' => ['tagihan', 'faktur', 'billing'],
            'kwitansi' => ['kuitansi', 'bukti pembayaran', 'receipt'],
            'kuitansi' => ['kwitansi', 'bukti pembayaran'],
            'thr' => ['tunjangan hari raya'],
            'gaji' => ['payroll', 'salary', 'upah', 'slip gaji'],
            'slip' => ['slip gaji', 'payslip'],
            'lamaran' => ['surat lamaran', 'cover letter', 'kerja'],
            'resign' => ['pengunduran diri'],
            'laporan' => ['laporan kerja', 'daily report', 'laporan harian', 'report kerja'],
            'notulen' => ['minutes of meeting', 'mom'],
            'quotation' => ['penawaran harga'],
            'sop' => ['standard operating procedure'],
            'contact' => ['kontak', 'hubungi', 'kerja sama', 'sponsorship'],
            'kontak' => ['contact', 'hubungi', 'kerja sama'],
            'surat' => ['lamaran', 'izin', 'dokumen'],
        ];

        return collect($tokens)
            ->flatMap(fn (string $token): array => $synonyms[$token] ?? [])
            ->map(fn (string $token): string => Str::lower($token))
            ->values()
            ->all();
    }

    protected function detectIntent(array $tokens): string
    {
        $joined = implode(' ', $tokens);

        return match (true) {
            $this->containsAny($joined, ['cv', 'resume', 'ats', 'lamaran', 'cover letter', 'linkedin', 'interview']) => 'career',
            $this->containsAny($joined, ['invoice', 'kwitansi', 'kuitansi', 'quotation', 'pembayaran', 'tagihan', 'faktur']) => 'business',
            $this->containsAny($joined, ['thr', 'gaji', 'slip gaji', 'lembur', 'payroll', 'salary', 'upah', 'hr']) => 'hr',
            $this->containsAny($joined, ['laporan', 'notulen', 'meeting', 'sop', 'kerja harian', 'daily report']) => 'productivity',
            $this->containsAny($joined, ['contact', 'kontak', 'hubungi', 'kerja sama', 'sponsorship']) => 'contact',
            $this->containsAny($joined, ['privacy', 'data', 'disimpan', 'aman', 'password']) => 'privacy',
            default => 'general',
        };
    }

    protected function matchFaqRule(string $normalizedMessage, array $tokens): ?array
    {
        $joined = implode(' ', $tokens);

        if ($this->containsAny($joined, ['gratis']) && $this->containsAny($joined, ['tool', 'tools', 'template', 'fitur', 'gratis'])) {
            return [
                'reply' => 'Ya, banyak tools dasar di Bantu Kerja bisa digunakan gratis untuk kebutuhan kerja, bisnis, dan administrasi harian.',
                'results' => $this->fallbackLinks(type: 'tool'),
                'query_category' => 'general',
            ];
        }

        if ($this->containsAny($joined, ['login', 'akun'])) {
            return [
                'reply' => 'Untuk tools publik, banyak fitur bisa langsung digunakan tanpa login.',
                'results' => $this->fallbackLinks(type: 'tool'),
                'query_category' => 'general',
            ];
        }

        if ($this->containsAny($joined, ['data', 'disimpan', 'aman', 'privacy'])) {
            return [
                'reply' => 'Input pada tools publik digunakan untuk menghasilkan preview/output dan tidak disimpan permanen kecuali ada fitur khusus yang meminta persetujuan pengguna. Hindari memasukkan data yang terlalu sensitif jika tidak diperlukan.',
                'results' => [[
                    'title' => 'Privacy Policy',
                    'type' => 'Halaman',
                    'description' => 'Kebijakan privasi Bantu Kerja.',
                    'url' => route('pages.privacy-policy'),
                ]],
                'query_category' => 'privacy',
            ];
        }

        if ($this->containsAny($joined, ['cara', 'gunakan', 'menggunakan', 'pakai']) && $this->containsAny($joined, ['tool', 'tools', 'website', 'template'])) {
            return [
                'reply' => 'Pilih tools, isi form yang tersedia, lihat preview hasil, lalu copy, print, atau download jika fitur tersedia.',
                'results' => $this->fallbackLinks(type: 'tool'),
                'query_category' => 'general',
            ];
        }

        if ($this->containsAny($joined, ['contact', 'kontak', 'hubungi'])) {
            return [
                'reply' => 'Kamu bisa menghubungi Bantu Kerja melalui halaman Contact.',
                'results' => [[
                    'title' => 'Hubungi Bantu Kerja',
                    'type' => 'Halaman',
                    'description' => 'Halaman contact untuk kerja sama, bug report, koreksi konten, dan request fitur.',
                    'url' => route('pages.contact'),
                ]],
                'query_category' => 'contact',
            ];
        }

        if ($this->containsAny($joined, ['kerja sama', 'sponsorship', 'sponsor'])) {
            return [
                'reply' => 'Untuk kerja sama, sponsorship, koreksi konten, atau permintaan fitur baru, silakan hubungi melalui halaman Contact.',
                'results' => [[
                    'title' => 'Hubungi Bantu Kerja',
                    'type' => 'Halaman',
                    'description' => 'Hubungi tim Bantu Kerja untuk kerja sama dan pertanyaan umum.',
                    'url' => route('pages.contact'),
                ]],
                'query_category' => 'contact',
            ];
        }

        return null;
    }

    protected function isOutOfScope(array $tokens): bool
    {
        if ($tokens === []) {
            return false;
        }

        $siteTerms = collect($this->defaultSuggestions())
            ->flatMap(fn (string $item): array => $this->expandedTokens($item))
            ->merge(['blog', 'artikel', 'tool', 'template', 'kontak', 'contact', 'privacy', 'bantukerja'])
            ->unique()
            ->values()
            ->all();

        return collect($tokens)->intersect($siteTerms)->isEmpty();
    }

    protected function fallbackLinks(?string $type = null): array
    {
        $links = [
            ['title' => 'Semua Tools', 'type' => 'Halaman', 'description' => 'Lihat semua tools publik Bantu Kerja.', 'url' => route('tools.index')],
            ['title' => 'Template Dokumen', 'type' => 'Halaman', 'description' => 'Jelajahi template dokumen yang siap dipakai.', 'url' => route('templates.index')],
            ['title' => 'Artikel Blog', 'type' => 'Halaman', 'description' => 'Baca panduan kerja dan administrasi.', 'url' => route('blog.index')],
            ['title' => 'Contact', 'type' => 'Halaman', 'description' => 'Hubungi tim Bantu Kerja.', 'url' => route('pages.contact')],
        ];

        if ($type === 'tool') {
            return array_slice($links, 0, 3);
        }

        return $links;
    }

    protected function replyForIntent(string $intent, Collection $results): string
    {
        return match ($intent) {
            'career' => 'Saya menemukan beberapa halaman yang cocok untuk kebutuhan karier Anda.',
            'business' => 'Saya menemukan tools dan halaman yang relevan untuk kebutuhan bisnis atau administrasi transaksi.',
            'hr' => 'Saya menemukan beberapa tools dan artikel yang cocok untuk kebutuhan HR, gaji, atau THR.',
            'productivity' => 'Saya menemukan halaman yang relevan untuk laporan kerja, SOP, atau kebutuhan operasional.',
            'contact' => 'Saya menemukan halaman yang relevan untuk menghubungi Bantu Kerja.',
            'privacy' => 'Saya menemukan informasi yang relevan tentang penggunaan data dan halaman legal Bantu Kerja.',
            default => 'Saya menemukan beberapa halaman yang mungkin paling relevan untuk pertanyaan Anda.',
        };
    }

    protected function suggestionsForIntent(string $intent): array
    {
        return match ($intent) {
            'career' => ['CV ATS', 'Surat Lamaran', 'Interview', 'LinkedIn', 'ATS Checker'],
            'business' => ['Invoice', 'Kwitansi', 'Quotation', 'Template', 'Contact'],
            'hr' => ['THR', 'Slip Gaji', 'Gaji', 'Lembur', 'Laporan Kerja'],
            'productivity' => ['Laporan Kerja', 'SOP', 'Notulen', 'Template', 'Contact'],
            'contact' => ['Contact', 'Template', 'Invoice', 'CV ATS'],
            default => $this->defaultSuggestions(),
        };
    }

    protected function stopwords(): array
    {
        return [
            'yang', 'untuk', 'buat', 'bikin', 'cara', 'ada', 'di', 'dimana', 'mana', 'saya', 'mau', 'ingin',
            'dengan', 'dan', 'atau', 'ke', 'dari', 'apakah', 'tools', 'tool', 'template', 'artikel', 'nya',
            'apa', 'itu', 'kah', 'bantu', 'kerja', 'website', 'tolong', 'dong',
        ];
    }

    protected function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($haystack, Str::lower($needle))) {
                return true;
            }
        }

        return false;
    }
}
