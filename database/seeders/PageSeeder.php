<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['title' => 'About', 'slug' => 'about', 'meta_title' => 'About BantuKerja.online', 'meta_description' => 'Tentang BantuKerja.online.', 'content' => '<p>BantuKerja.online adalah website utilitas publik yang menyediakan tools gratis, template dokumen, dan artikel edukatif untuk membantu kebutuhan kerja, bisnis, dan administrasi harian.</p>'],
            ['title' => 'Contact', 'slug' => 'contact', 'meta_title' => 'Contact BantuKerja.online', 'meta_description' => 'Kontak BantuKerja.online.', 'content' => '<p>Untuk kerja sama, masukan, atau pelaporan masalah konten, silakan hubungi tim melalui email resmi yang akan dikelola dari panel admin.</p>'],
            ['title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'meta_title' => 'Privacy Policy', 'meta_description' => 'Kebijakan privasi BantuKerja.online.', 'content' => '<h2>Privasi pengguna</h2><p>Kami berupaya memproses data secara minimal dan tidak menyimpan isi invoice atau surat dari tool publik kecuali ada fitur akun di masa depan.</p><h2>Cookies dan analytics</h2><p>Website dapat menggunakan cookies, analytics, dan teknologi serupa untuk memahami penggunaan situs dan meningkatkan layanan.</p><h2>Iklan pihak ketiga</h2><p>Iklan dari pihak ketiga seperti Google AdSense dapat menggunakan cookies untuk menayangkan iklan yang relevan.</p>'],
            ['title' => 'Terms', 'slug' => 'terms', 'meta_title' => 'Terms of Use', 'meta_description' => 'Syarat penggunaan BantuKerja.online.', 'content' => '<p>Seluruh tools, template, dan artikel disediakan untuk tujuan informasi dan efisiensi kerja. Pengguna bertanggung jawab untuk meninjau ulang hasil akhir sebelum dipakai secara resmi.</p>'],
            ['title' => 'Disclaimer', 'slug' => 'disclaimer', 'meta_title' => 'Disclaimer', 'meta_description' => 'Disclaimer BantuKerja.online.', 'content' => '<p>Konten pada website ini bersifat informatif dan bukan pengganti nasihat hukum, perpajakan, atau konsultasi profesional lainnya.</p>'],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                array_merge($page, ['is_published' => true]),
            );
        }
    }
}
