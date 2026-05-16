<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug, SeoService $seoService): View|RedirectResponse
    {
        $legalRoutes = [
            'about' => 'pages.about',
            'contact' => 'pages.contact',
            'privacy-policy' => 'pages.privacy-policy',
            'terms' => 'pages.terms',
            'disclaimer' => 'pages.disclaimer',
        ];

        if (request()->routeIs('pages.show') && array_key_exists($slug, $legalRoutes)) {
            return redirect()->route($legalRoutes[$slug], status: 301);
        }

        $page = Page::query()->published()->where('slug', $slug)->firstOrFail();

        return view('pages.show', [
            'page' => $page,
            'seo' => $seoService->forModel($page, ['canonical' => url()->current()]),
            'showContactForm' => $page->slug === 'contact',
        ]);
    }
}
