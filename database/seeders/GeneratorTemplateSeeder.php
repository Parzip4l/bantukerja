<?php

namespace Database\Seeders;

use App\Models\GeneratorTemplate;
use Illuminate\Database\Seeder;

class GeneratorTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Classic',
                'slug' => 'invoice-classic',
                'generator_type' => 'invoice',
                'description' => 'Tampilan invoice formal dengan struktur tabel yang jelas dan mudah dicetak.',
                'view_path' => 'generators.invoice.templates.classic',
                'paper_size' => 'a4',
                'orientation' => 'portrait',
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Modern',
                'slug' => 'invoice-modern',
                'generator_type' => 'invoice',
                'description' => 'Invoice visual dengan aksen navy dan oranye untuk bisnis yang ingin tampil lebih kuat.',
                'view_path' => 'generators.invoice.templates.modern',
                'paper_size' => 'a4',
                'orientation' => 'portrait',
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Minimal',
                'slug' => 'invoice-minimal',
                'generator_type' => 'invoice',
                'description' => 'Desain hitam putih yang ringan dan cocok untuk freelancer atau usaha kecil.',
                'view_path' => 'generators.invoice.templates.minimal',
                'paper_size' => 'a4',
                'orientation' => 'portrait',
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Professional',
                'slug' => 'invoice-professional',
                'generator_type' => 'invoice',
                'description' => 'Invoice rapi dan detail untuk kebutuhan bisnis yang ingin terlihat lebih mapan.',
                'view_path' => 'generators.invoice.templates.professional',
                'paper_size' => 'a4',
                'orientation' => 'portrait',
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'Formal',
                'slug' => 'letter-formal',
                'generator_type' => 'letter',
                'description' => 'Format surat formal Indonesia dengan struktur pembuka, isi, dan penutup yang rapi.',
                'view_path' => 'generators.letter.templates.formal',
                'paper_size' => 'a4',
                'orientation' => 'portrait',
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Simple',
                'slug' => 'letter-simple',
                'generator_type' => 'letter',
                'description' => 'Surat ringkas, bersih, dan mudah dibaca untuk kebutuhan cepat.',
                'view_path' => 'generators.letter.templates.simple',
                'paper_size' => 'a4',
                'orientation' => 'portrait',
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Corporate',
                'slug' => 'letter-corporate',
                'generator_type' => 'letter',
                'description' => 'Tampilan surat kantor yang lebih profesional untuk kebutuhan administrasi perusahaan.',
                'view_path' => 'generators.letter.templates.corporate',
                'paper_size' => 'a4',
                'orientation' => 'portrait',
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Classic',
                'slug' => 'receipt-classic',
                'generator_type' => 'receipt',
                'description' => 'Template kwitansi klasik yang familiar dan mudah digunakan.',
                'view_path' => 'generators.receipt.templates.classic',
                'paper_size' => 'a4',
                'orientation' => 'portrait',
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Minimal',
                'slug' => 'receipt-minimal',
                'generator_type' => 'receipt',
                'description' => 'Kwitansi minimalis untuk hasil yang lebih bersih dan cepat dicetak.',
                'view_path' => 'generators.receipt.templates.minimal',
                'paper_size' => 'a4',
                'orientation' => 'portrait',
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Formal',
                'slug' => 'minutes-formal',
                'generator_type' => 'minutes',
                'description' => 'Template berita acara formal untuk kebutuhan serah terima atau rapat.',
                'view_path' => 'generators.minutes.templates.formal',
                'paper_size' => 'a4',
                'orientation' => 'portrait',
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Professional',
                'slug' => 'minutes-professional',
                'generator_type' => 'minutes',
                'description' => 'Berita acara rapi dengan struktur yang lebih profesional dan siap presentasi.',
                'view_path' => 'generators.minutes.templates.professional',
                'paper_size' => 'a4',
                'orientation' => 'portrait',
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 2,
            ],
        ];

        foreach ($templates as $template) {
            GeneratorTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template,
            );
        }
    }
}
