<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Payroll & HR', 'slug' => 'payroll-hr', 'type' => 'blog', 'description' => 'Topik THR, gaji, dan lembur.', 'meta_title' => 'Kategori Payroll & HR', 'meta_description' => 'Artikel seputar THR, penggajian, dan HR.'],
            ['name' => 'Dokumen Kerja', 'slug' => 'dokumen-kerja', 'type' => 'blog', 'description' => 'Panduan surat dan dokumen profesional.', 'meta_title' => 'Kategori Dokumen Kerja', 'meta_description' => 'Panduan membuat surat, CV, dan dokumen kerja.'],
            ['name' => 'Administrasi Bisnis', 'slug' => 'administrasi-bisnis', 'type' => 'blog', 'description' => 'Invoice, kwitansi, dan administrasi usaha.', 'meta_title' => 'Kategori Administrasi Bisnis', 'meta_description' => 'Artikel administrasi bisnis dan operasional harian.'],
            ['name' => 'Kalkulator Kerja', 'slug' => 'kalkulator-kerja', 'type' => 'tool', 'description' => 'Kalkulator THR, gaji, dan lembur.', 'meta_title' => 'Tools Kalkulator Kerja', 'meta_description' => 'Kumpulan kalkulator kerja dan payroll.'],
            ['name' => 'Generator Dokumen', 'slug' => 'generator-dokumen', 'type' => 'tool', 'description' => 'Generator invoice dan surat.', 'meta_title' => 'Tools Generator Dokumen', 'meta_description' => 'Generator dokumen gratis untuk kebutuhan harian.'],
            ['name' => 'Surat Kerja', 'slug' => 'surat-kerja', 'type' => 'template', 'description' => 'Template surat untuk kebutuhan kerja.', 'meta_title' => 'Template Surat Kerja', 'meta_description' => 'Template surat kerja dan administrasi.'],
            ['name' => 'Karier & Lamaran', 'slug' => 'karier-lamaran', 'type' => 'template', 'description' => 'Template CV dan lamaran kerja.', 'meta_title' => 'Template Karier', 'meta_description' => 'Template CV, lamaran kerja, dan dokumen karier.'],
            ['name' => 'Bisnis & Legal Ringan', 'slug' => 'bisnis-legal-ringan', 'type' => 'template', 'description' => 'Template invoice, MoU, dan surat pernyataan.', 'meta_title' => 'Template Bisnis', 'meta_description' => 'Template dokumen bisnis dan legal ringan.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                array_merge($category, ['is_active' => true]),
            );
        }
    }
}
