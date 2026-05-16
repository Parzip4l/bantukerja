<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DocumentTemplate;
use App\Models\Faq;
use Illuminate\Database\Seeder;

class DocumentTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            ['title' => 'Surat Resign', 'slug' => 'surat-resign', 'category_slug' => 'surat-kerja', 'short_description' => 'Contoh surat resign profesional yang sopan dan ringkas.', 'meta_title' => 'Template Surat Resign Profesional', 'meta_description' => 'Template surat resign profesional yang siap disalin dan diedit.', 'content' => '<h2>Contoh Surat Resign</h2><p>Yth. HRD / Atasan,</p><p>Melalui surat ini saya mengajukan pengunduran diri dari posisi saya terhitung sejak ...</p><p>Terima kasih atas kesempatan dan kerja sama yang telah diberikan.</p>'],
            ['title' => 'Surat Kuasa', 'slug' => 'surat-kuasa', 'category_slug' => 'bisnis-legal-ringan', 'short_description' => 'Template surat kuasa sederhana untuk kebutuhan administrasi umum.', 'meta_title' => 'Template Surat Kuasa Sederhana', 'meta_description' => 'Surat kuasa sederhana yang bisa disesuaikan untuk berbagai keperluan.', 'content' => '<h2>Contoh Surat Kuasa</h2><p>Yang bertanda tangan di bawah ini memberikan kuasa kepada ... untuk mewakili kepentingan ...</p>'],
            ['title' => 'Berita Acara Serah Terima', 'slug' => 'berita-acara', 'category_slug' => 'bisnis-legal-ringan', 'short_description' => 'Template berita acara serah terima barang atau dokumen.', 'meta_title' => 'Template Berita Acara Serah Terima', 'meta_description' => 'Contoh berita acara serah terima barang atau dokumen.', 'content' => '<h2>Berita Acara Serah Terima</h2><p>Pada hari ini telah dilakukan serah terima antara pihak pertama dan pihak kedua atas barang/dokumen berikut ...</p>'],
            ['title' => 'Surat Izin Kerja', 'slug' => 'surat-izin-kerja', 'category_slug' => 'surat-kerja', 'short_description' => 'Template surat izin kerja untuk kebutuhan kantor dan operasional.', 'meta_title' => 'Template Surat Izin Kerja', 'meta_description' => 'Template surat izin kerja yang rapi dan siap pakai.', 'content' => '<h2>Surat Izin Kerja</h2><p>Dengan ini saya mengajukan izin kerja pada tanggal ... karena ...</p>'],
            ['title' => 'Surat Lamaran Kerja', 'slug' => 'surat-lamaran-kerja', 'category_slug' => 'karier-lamaran', 'short_description' => 'Template surat lamaran kerja formal dan mudah disesuaikan.', 'meta_title' => 'Template Surat Lamaran Kerja', 'meta_description' => 'Contoh surat lamaran kerja formal dan profesional.', 'content' => '<h2>Surat Lamaran Kerja</h2><p>Dengan hormat, berdasarkan informasi lowongan yang saya peroleh, saya bermaksud melamar posisi ...</p>'],
            ['title' => 'CV Sederhana', 'slug' => 'cv-lamaran-kerja', 'category_slug' => 'karier-lamaran', 'short_description' => 'Contoh struktur CV sederhana untuk fresh graduate dan profesional awal.', 'meta_title' => 'Template CV Sederhana', 'meta_description' => 'Template CV sederhana dan mudah dibaca recruiter.', 'content' => '<h2>Curriculum Vitae</h2><p>Nama, kontak, ringkasan profil, pengalaman kerja, pendidikan, dan keterampilan utama.</p>'],
            ['title' => 'Invoice Sederhana', 'slug' => 'invoice-sederhana', 'category_slug' => 'bisnis-legal-ringan', 'short_description' => 'Template invoice sederhana untuk freelancer dan usaha kecil.', 'meta_title' => 'Template Invoice Sederhana', 'meta_description' => 'Template invoice sederhana yang bisa dipakai untuk tagihan jasa atau produk.', 'content' => '<h2>Invoice</h2><p>Nomor invoice, tanggal, data pelanggan, rincian item, subtotal, pajak, dan total pembayaran.</p>'],
            ['title' => 'MoU Sederhana', 'slug' => 'mou-sederhana', 'category_slug' => 'bisnis-legal-ringan', 'short_description' => 'Template nota kesepahaman sederhana untuk kerja sama awal.', 'meta_title' => 'Template MoU Sederhana', 'meta_description' => 'Contoh MoU sederhana untuk kerja sama bisnis awal.', 'content' => '<h2>Memorandum of Understanding</h2><p>Dokumen ini memuat ruang lingkup kerja sama, peran para pihak, dan jangka waktu kesepahaman.</p>'],
            ['title' => 'Surat Pernyataan', 'slug' => 'surat-pernyataan', 'category_slug' => 'bisnis-legal-ringan', 'short_description' => 'Template surat pernyataan umum yang fleksibel.', 'meta_title' => 'Template Surat Pernyataan', 'meta_description' => 'Contoh surat pernyataan umum untuk kebutuhan administrasi.', 'content' => '<h2>Surat Pernyataan</h2><p>Saya yang bertanda tangan di bawah ini menyatakan bahwa ...</p>'],
            ['title' => 'Surat Permohonan', 'slug' => 'surat-permohonan', 'category_slug' => 'surat-kerja', 'short_description' => 'Template surat permohonan resmi untuk keperluan kerja dan administrasi.', 'meta_title' => 'Template Surat Permohonan', 'meta_description' => 'Contoh surat permohonan resmi yang mudah disesuaikan.', 'content' => '<h2>Surat Permohonan</h2><p>Dengan hormat, melalui surat ini kami mengajukan permohonan ...</p>'],
        ];

        foreach ($templates as $index => $templateData) {
            $categorySlug = $templateData['category_slug'];
            unset($templateData['category_slug']);

            $template = DocumentTemplate::updateOrCreate(
                ['slug' => $templateData['slug']],
                array_merge($templateData, [
                    'category_id' => Category::where('slug', $categorySlug)->value('id'),
                    'is_featured' => $index < 6,
                    'is_published' => true,
                    'published_at' => now()->subDays(20 - $index),
                ]),
            );

            Faq::updateOrCreate(
                [
                    'faqable_type' => DocumentTemplate::class,
                    'faqable_id' => $template->id,
                    'question' => 'Apakah template ini boleh diedit?',
                ],
                [
                    'answer' => 'Ya. Template ini dibuat sebagai draft awal yang dapat Anda sesuaikan dengan situasi dan kebutuhan masing-masing.',
                    'sort_order' => 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
