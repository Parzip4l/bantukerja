@props(['seo' => []])

@php
    $meta = array_merge([
        'title' => 'BantuKerja.online',
        'description' => 'Tools dan template gratis untuk kerja, bisnis, dan administrasi harian.',
        'canonical' => url()->current(),
        'og_type' => 'website',
        'og_image' => asset('images/og-default.svg'),
        'robots' => 'index,follow,max-image-preview:large',
    ], $seo);
@endphp

<title>{{ $meta['title'] }}</title>
<meta name="description" content="{{ $meta['description'] }}">
<meta name="robots" content="{{ $meta['robots'] }}">
<link rel="canonical" href="{{ $meta['canonical'] }}">
<meta property="og:title" content="{{ $meta['title'] }}">
<meta property="og:description" content="{{ $meta['description'] }}">
<meta property="og:url" content="{{ $meta['canonical'] }}">
<meta property="og:type" content="{{ $meta['og_type'] }}">
<meta property="og:image" content="{{ $meta['og_image'] }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $meta['title'] }}">
<meta name="twitter:description" content="{{ $meta['description'] }}">
<meta name="twitter:image" content="{{ $meta['og_image'] }}">
