# Arsitektur BantuKerja.online

## Ringkasan
- Stack utama: Laravel 13, Blade, Tailwind CSS 4, Filament 5, SQLite untuk lokal dan MySQL untuk production shared hosting.
- Pola aplikasi: server-rendered SEO-first website dengan admin panel terpisah di `/admin`.
- Fokus utama: halaman publik cepat, konten mudah dikelola, dan siap monetisasi AdSense tanpa hardcode.

## Lapisan Aplikasi
- `app/Http/Controllers`: menangani alur halaman publik, kalkulasi tool, sitemap, dan robots.
- `app/Http/Requests`: validasi form publik untuk tool interaktif.
- `app/Services`: logika SEO, kalkulasi tool, rendering template, sitemap, dan ad slot.
- `app/Models`: model Eloquent dengan relationship dan scope publikasi.
- `app/Filament/Resources`: CRUD admin untuk kategori, tool, template, artikel, halaman legal, setting, dan ad slot.
- `resources/views`: Blade layout, komponen SEO, breadcrumb, FAQ schema, halaman listing/detail, dan PDF invoice.

## Entitas Data
- `categories`: kategori lintas tipe `blog`, `tool`, `template`.
- `tools`: landing page tool dengan konten SEO dan FAQ.
- `document_templates`: template dokumen siap copy/download.
- `posts`: artikel edukatif SEO-friendly.
- `pages`: halaman statis/legal.
- `ad_slots`: pengelolaan slot iklan.
- `contact_messages`: inbox pesan dari halaman kontak publik.
- `faqs`: FAQ polymorphic untuk tool/template.
- `settings`: konfigurasi situs dan AdSense global.
- `users`: login admin/editor untuk Filament.

## Alur Konten
1. Admin mengelola konten dari Filament.
2. Konten dipublikasikan melalui status/switch `is_published` atau `status`.
3. Halaman publik mengambil data dengan scope `published()` dan `latestPublished()`.
4. SEO metadata dibangun melalui `SeoService`.
5. Sitemap dan robots dihasilkan server-side.

## Prinsip Implementasi
- Hindari logika bisnis di Blade.
- Semua halaman utama dirender server-side.
- Input publik dibatasi dengan Form Request dan throttle middleware.
- Form kontak memakai rate limit dan honeypot field sederhana untuk menekan spam bot.
- AdSense diatur lewat `settings` dan `ad_slots`, bukan hardcode.
- Resource sensitif seperti setting dan ad slot dibatasi untuk role `admin`.
- Desain tetap ringan agar cocok untuk cPanel/shared hosting.
