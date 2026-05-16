<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DocumentTemplate;
use App\Models\Post;
use App\Models\Tool;
use App\Services\SeoService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(SeoService $seoService): View
    {
        return view('home.index', [
            'seo' => $seoService->defaults([
                'title' => 'BantuKerja.online | Tools dan template gratis untuk kerja dan bisnis',
                'description' => 'Website utilitas publik berisi tools gratis, template dokumen siap pakai, dan artikel edukatif SEO-friendly untuk kerja, bisnis, dan administrasi.',
            ]),
            'toolCategories' => Category::active()->where('type', 'tool')->orderBy('name')->get(),
            'templateCategories' => Category::active()->where('type', 'template')->orderBy('name')->get(),
            'latestPosts' => Post::query()->with('category')->latestPublished()->limit(6)->get(),
            'popularTools' => Tool::query()->with('category')->published()->featured()->latestPublished()->limit(6)->get(),
            'popularTemplates' => DocumentTemplate::query()->with('category')->published()->featured()->latestPublished()->limit(6)->get(),
        ]);
    }
}
