<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DocumentTemplate;
use App\Models\Post;
use App\Models\Tool;
use App\Services\SeoService;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(SeoService $seoService): View
    {
        $allTools = Tool::query()
            ->with('category')
            ->published()
            ->get();

        $allTemplates = DocumentTemplate::query()
            ->with('category')
            ->published()
            ->get();

        $allPosts = Post::query()
            ->with('category')
            ->published()
            ->get();

        $toolLookup = $allTools->keyBy('slug');
        $templateLookup = $allTemplates->keyBy('slug');

        $popularToolSlugs = [
            'generator-invoice',
            'generator-kwitansi',
            'generator-cv-ats',
            'kalkulator-thr',
            'generator-surat-lamaran-kerja',
            'simulasi-pertanyaan-interview',
            'interview-answer-star',
            'ats-cv-checker',
            'generator-quotation',
            'generator-sop',
            'generator-job-description',
            'generator-laporan-kerja-harian',
            'kalkulator-gaji-bersih',
        ];

        $popularTools = collect($popularToolSlugs)
            ->map(fn (string $slug) => $toolLookup->get($slug))
            ->filter()
            ->merge(
                $allTools->where('is_featured', true)->filter(fn (Tool $tool) => ! in_array($tool->slug, $popularToolSlugs, true))
            )
            ->take(8)
            ->values();

        $popularTemplates = $allTemplates
            ->where('is_featured', true)
            ->sortByDesc('published_at')
            ->take(6)
            ->values();

        $latestPosts = $allPosts
            ->sortByDesc('published_at')
            ->take(6)
            ->values();

        $searchIndex = $this->buildSearchIndex($allTools, $allTemplates, $allPosts);
        $personaSections = $this->buildPersonaSections($toolLookup, $templateLookup);
        $careerSpotlight = $this->careerTools($toolLookup)->take(6)->values();

        return view('home.index', [
            'seo' => $seoService->defaults([
                'title' => 'Bantu Kerja - Tools dan Template Gratis untuk Kerja, Bisnis, dan Administrasi',
                'description' => 'Gunakan tools dan template gratis untuk membuat invoice, kwitansi, CV ATS, surat kerja, slip gaji, kalkulator THR, dan dokumen administrasi harian.',
            ]),
            'toolCategories' => Category::active()->where('type', 'tool')->orderBy('name')->get(),
            'templateCategories' => Category::active()->where('type', 'template')->orderBy('name')->get(),
            'latestPosts' => $latestPosts,
            'popularTools' => $popularTools,
            'popularTemplates' => $popularTemplates,
            'searchIndex' => $searchIndex,
            'personaSections' => $personaSections,
            'careerSpotlight' => $careerSpotlight,
            'stats' => [
                ['label' => 'Tools Gratis', 'value' => number_format($allTools->count())],
                ['label' => 'Template Dokumen', 'value' => number_format($allTemplates->count())],
                ['label' => 'Artikel Panduan', 'value' => number_format($allPosts->count())],
            ],
            'heroHighlights' => $popularTools->take(3),
        ]);
    }

    public function careerInterview(SeoService $seoService): View
    {
        $allTools = Tool::query()
            ->with('category')
            ->published()
            ->get();

        $allTemplates = DocumentTemplate::query()
            ->with('category')
            ->published()
            ->get();

        $allPosts = Post::query()
            ->with('category')
            ->published()
            ->get();

        $toolLookup = $allTools->keyBy('slug');
        $templateLookup = $allTemplates->keyBy('slug');

        $careerTools = $this->careerTools($toolLookup);
        $careerTemplates = collect([
            $templateLookup->get('cv-lamaran-kerja'),
            $templateLookup->get('surat-lamaran-kerja'),
        ])->filter()->values();

        $careerArticles = $allPosts
            ->filter(function (Post $post): bool {
                $haystack = str($post->title.' '.$post->excerpt)->lower()->toString();

                return str_contains($haystack, 'cv')
                    || str_contains($haystack, 'lamaran')
                    || str_contains($haystack, 'interview')
                    || str_contains($haystack, 'karier')
                    || str_contains($haystack, 'linkedin');
            })
            ->take(6)
            ->values();

        if ($careerArticles->isEmpty()) {
            $careerArticles = $allPosts->take(3)->values();
        }

        return view('home.career-interview', [
            'seo' => $seoService->defaults([
                'title' => 'Karier & Interview - Tools Gratis Bantu Kerja',
                'description' => 'Siapkan CV ATS, surat lamaran, latihan interview, LinkedIn, ATS checker, dan job matching dalam satu cluster karier yang lebih rapi.',
            ]),
            'careerTools' => $careerTools,
            'careerTemplates' => $careerTemplates,
            'careerArticles' => $careerArticles,
        ]);
    }

    protected function buildSearchIndex(Collection $tools, Collection $templates, Collection $posts): Collection
    {
        $toolItems = $tools->map(fn (Tool $tool) => [
            'title' => $tool->title,
            'description' => $tool->short_description,
            'url' => route('tools.show', $tool->slug),
            'type' => 'Tool',
        ]);

        $templateItems = $templates->map(fn (DocumentTemplate $template) => [
            'title' => $template->title,
            'description' => $template->short_description,
            'url' => route('templates.show', $template->slug),
            'type' => 'Template',
        ]);

        $postItems = $posts->take(24)->map(fn (Post $post) => [
            'title' => $post->title,
            'description' => $post->excerpt,
            'url' => route('blog.show', $post->slug),
            'type' => 'Artikel',
        ]);

        return $toolItems
            ->merge($templateItems)
            ->merge($postItems)
            ->values();
    }

    protected function buildPersonaSections(Collection $toolLookup, Collection $templateLookup): Collection
    {
        $makeToolItem = fn (string $slug): ?array => $toolLookup->has($slug)
            ? [
                'title' => $toolLookup[$slug]->title,
                'url' => route('tools.show', $toolLookup[$slug]->slug),
                'kind' => 'tool',
            ]
            : null;

        $makeTemplateItem = fn (string $slug): ?array => $templateLookup->has($slug)
            ? [
                'title' => $templateLookup[$slug]->title,
                'url' => route('templates.show', $templateLookup[$slug]->slug),
                'kind' => 'template',
            ]
            : null;

        return collect([
            [
                'title' => 'Untuk Pencari Kerja',
                'description' => 'Buat dokumen karier seperti CV ATS, surat lamaran, dan dokumen pendukung lainnya.',
                'cta_label' => 'Mulai gunakan',
                'items' => collect([
                    $makeToolItem('generator-cv-ats'),
                    $makeToolItem('generator-surat-lamaran-kerja'),
                    $makeToolItem('simulasi-pertanyaan-interview'),
                    $makeToolItem('ats-cv-checker'),
                    $makeTemplateItem('cv-lamaran-kerja'),
                    $makeTemplateItem('surat-lamaran-kerja'),
                ])->filter()->values(),
            ],
            [
                'title' => 'Untuk Freelancer & UMKM',
                'description' => 'Buat dokumen transaksi dan penawaran agar bisnis terlihat lebih profesional.',
                'cta_label' => 'Lihat tools terkait',
                'items' => collect([
                    $makeToolItem('generator-invoice'),
                    $makeToolItem('generator-kwitansi'),
                    $makeToolItem('generator-quotation'),
                    $makeTemplateItem('invoice-sederhana'),
                ])->filter()->values(),
            ],
            [
                'title' => 'Untuk HR & Admin',
                'description' => 'Bantu pekerjaan administrasi karyawan, payroll sederhana, dan dokumen internal.',
                'cta_label' => 'Lihat tools terkait',
                'items' => collect([
                    $makeToolItem('kalkulator-thr'),
                    $makeToolItem('kalkulator-gaji-bersih'),
                    $makeToolItem('kalkulator-lembur'),
                    $makeToolItem('generator-job-description'),
                    $makeToolItem('generator-laporan-kerja-harian'),
                ])->filter()->values(),
            ],
            [
                'title' => 'Untuk Operasional & Bisnis',
                'description' => 'Siapkan dokumen kerja, SOP, checklist, dan kebutuhan administrasi operasional.',
                'cta_label' => 'Mulai gunakan',
                'items' => collect([
                    $makeToolItem('generator-sop'),
                    $makeToolItem('generator-berita-acara'),
                    $makeTemplateItem('berita-acara'),
                    [
                        'title' => 'Lihat Template Dokumen',
                        'url' => route('templates.index'),
                        'kind' => 'template',
                    ],
                ])->filter()->values(),
            ],
        ])->map(function (array $section): array {
            $items = collect($section['items'])->filter()->values();
            $section['items'] = $items;
            $section['cta_url'] = $items->first()['url'] ?? route('tools.index');

            return $section;
        })->filter(fn (array $section) => collect($section['items'])->isNotEmpty())->values();
    }

    protected function careerTools(Collection $toolLookup): Collection
    {
        return collect([
            $toolLookup->get('generator-cv-ats'),
            $toolLookup->get('generator-surat-lamaran-kerja'),
            $toolLookup->get('simulasi-pertanyaan-interview'),
            $toolLookup->get('interview-answer-star'),
            $toolLookup->get('linkedin-headline-about-generator'),
            $toolLookup->get('job-description-matcher'),
            $toolLookup->get('ats-cv-checker'),
            $toolLookup->get('generator-job-description'),
        ])->filter()->values();
    }
}
