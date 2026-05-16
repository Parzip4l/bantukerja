<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Tool;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
    public function run(): void
    {
        $tools = [
            [
                'category_slug' => 'kalkulator-kerja',
                'title' => 'Kalkulator THR',
                'slug' => 'kalkulator-thr',
                'short_description' => 'Hitung estimasi THR karyawan berdasarkan gaji bulanan dan masa kerja secara cepat.',
                'tool_type' => 'calculator',
                'meta_title' => 'Kalkulator THR Gratis Online',
                'meta_description' => 'Hitung THR karyawan secara cepat dengan formula sederhana sesuai masa kerja.',
                'body' => '<h2>Cara menggunakan kalkulator THR</h2><p>Masukkan gaji bulanan dan lama bekerja dalam bulan. Sistem akan menghitung estimasi THR penuh atau proporsional sesuai input Anda.</p><h2>Catatan penting</h2><p>Hasil perhitungan ini adalah estimasi praktis untuk edukasi. Kebijakan final tetap mengikuti aturan perusahaan dan regulasi yang berlaku.</p>',
                'faqs' => [
                    ['question' => 'Apakah hasil kalkulator ini resmi?', 'answer' => 'Tidak. Hasilnya adalah estimasi praktis untuk membantu memahami perhitungan dasar THR.'],
                    ['question' => 'Bagaimana jika masa kerja belum 12 bulan?', 'answer' => 'THR dihitung proporsional: masa kerja dibagi 12 lalu dikalikan gaji bulanan.'],
                ],
            ],
            [
                'category_slug' => 'kalkulator-kerja',
                'title' => 'Kalkulator Gaji Bersih',
                'slug' => 'kalkulator-gaji-bersih',
                'short_description' => 'Hitung estimasi gaji bersih bulanan dari gaji pokok, tunjangan, dan potongan.',
                'tool_type' => 'calculator',
                'meta_title' => 'Kalkulator Gaji Bersih Online',
                'meta_description' => 'Hitung estimasi gaji bersih dari gaji pokok, tunjangan, BPJS, pajak, dan potongan lain.',
                'body' => '<h2>Apa yang dihitung?</h2><p>Tool ini menghitung total pendapatan, total potongan, lalu menghasilkan estimasi take home pay.</p><h2>Kapan tool ini berguna?</h2><p>Cocok dipakai saat menilai penawaran kerja, membandingkan komponen payroll, atau memeriksa simulasi gaji bulanan.</p>',
                'faqs' => [
                    ['question' => 'Apakah tool ini menghitung pajak secara detail?', 'answer' => 'Belum. Tool ini menerima input pajak sebagai nilai manual agar tetap ringan dan cepat.'],
                ],
            ],
            [
                'category_slug' => 'kalkulator-kerja',
                'title' => 'Kalkulator Lembur Sederhana',
                'slug' => 'kalkulator-lembur',
                'short_description' => 'Estimasi upah lembur berdasarkan upah bulanan, jumlah jam lembur, dan tipe hari.',
                'tool_type' => 'calculator',
                'meta_title' => 'Kalkulator Lembur Sederhana',
                'meta_description' => 'Hitung estimasi upah lembur sederhana untuk hari kerja atau hari libur.',
                'body' => '<h2>Untuk siapa tool ini dibuat?</h2><p>Tool ini cocok untuk karyawan yang ingin punya gambaran cepat tentang estimasi lembur sebelum mengecek slip gaji resmi.</p><h2>Rumus yang dipakai</h2><p>Perhitungan berbasis estimasi upah per jam dari upah bulanan dibagi 173, lalu dikalikan jam lembur dan multiplier sederhana.</p>',
                'faqs' => [
                    ['question' => 'Apakah rumusnya sama untuk semua perusahaan?', 'answer' => 'Tidak selalu. Setiap perusahaan bisa punya komponen dan aturan lembur yang berbeda.'],
                ],
            ],
            [
                'category_slug' => 'generator-dokumen',
                'title' => 'Generator Invoice',
                'slug' => 'generator-invoice',
                'short_description' => 'Buat invoice sederhana lengkap dengan preview total, tombol print, dan download PDF.',
                'tool_type' => 'generator',
                'meta_title' => 'Generator Invoice Gratis',
                'meta_description' => 'Buat invoice sederhana online tanpa login, lengkap dengan preview dan PDF.',
                'body' => '<h2>Kenapa invoice generator ini praktis?</h2><p>Anda bisa membuat invoice tanpa login dan tanpa menyimpan data sensitif di server. Form cukup diisi sekali lalu langsung preview dan unduh.</p><h2>Tips penggunaan</h2><p>Gunakan nomor invoice yang konsisten, isi item secara jelas, dan tambahkan pajak atau diskon bila diperlukan.</p>',
                'faqs' => [
                    ['question' => 'Apakah data invoice disimpan?', 'answer' => 'Tidak. Data invoice hanya diproses untuk preview dan file hasil unduhan pada sesi aktif.'],
                ],
            ],
            [
                'category_slug' => 'generator-dokumen',
                'title' => 'Generator Surat Izin Kerja',
                'slug' => 'generator-surat-izin',
                'short_description' => 'Buat surat izin kerja sederhana yang siap disalin atau diunduh dalam format teks.',
                'tool_type' => 'generator',
                'meta_title' => 'Generator Surat Izin Kerja',
                'meta_description' => 'Buat surat izin kerja cepat dan rapi untuk kebutuhan kantor atau administrasi harian.',
                'body' => '<h2>Kapan surat izin ini digunakan?</h2><p>Surat izin kerja membantu menyampaikan ketidakhadiran atau kebutuhan izin tertentu secara lebih profesional dan terdokumentasi.</p><h2>Apa yang dihasilkan?</h2><p>Tool menghasilkan teks surat yang bisa langsung disalin, disesuaikan, atau diunduh dalam format TXT.</p>',
                'faqs' => [
                    ['question' => 'Apakah format surat bisa diedit?', 'answer' => 'Bisa. Anda dapat menyalin hasil surat lalu menyesuaikannya dengan kebutuhan perusahaan.'],
                ],
            ],
        ];

        foreach ($tools as $toolData) {
            $faqs = $toolData['faqs'];
            $categorySlug = $toolData['category_slug'];
            unset($toolData['faqs']);
            unset($toolData['category_slug']);

            $tool = Tool::updateOrCreate(
                ['slug' => $toolData['slug']],
                array_merge($toolData, [
                    'category_id' => Category::where('slug', $categorySlug)->value('id'),
                    'is_featured' => true,
                    'is_published' => true,
                    'published_at' => now()->subDays(15),
                ]),
            );

            foreach ($faqs as $index => $faq) {
                Faq::updateOrCreate(
                    [
                        'faqable_type' => Tool::class,
                        'faqable_id' => $tool->id,
                        'question' => $faq['question'],
                    ],
                    [
                        'answer' => $faq['answer'],
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
