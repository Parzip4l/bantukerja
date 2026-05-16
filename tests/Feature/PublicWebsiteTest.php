<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\GeneratorDownloadLog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicWebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.internal_publisher.token', 'testing-internal-publisher-token');

        $this->seed();
    }

    public function test_public_routes_are_accessible(): void
    {
        $routes = [
            '/',
            '/tools',
            '/tools/kalkulator-thr',
            '/tools/kalkulator-fee-konsultan-pajak',
            '/tools/kalkulator-fee-accounting-service',
            '/tools/kalkulator-fee-audit',
            '/tools/generator-invoice',
            '/tools/generator-surat-izin',
            '/tools/generator-kwitansi',
            '/tools/generator-berita-acara',
            '/tools/generator-cv-ats',
            '/template',
            '/template/surat-resign',
            '/blog',
            '/blog/cara-menghitung-thr-karyawan',
            '/about',
            '/contact',
            '/privacy-policy',
            '/terms',
            '/disclaimer',
            '/sitemap.xml',
            '/robots.txt',
            '/admin/login',
            '/template/surat-resign/download.doc',
        ];

        foreach ($routes as $route) {
            $this->get($route)->assertOk();
        }
    }

    public function test_thr_calculator_returns_result_in_session(): void
    {
        $response = $this->from('/tools/kalkulator-thr')->post('/tools/kalkulator-thr/calculate', [
            'monthly_salary' => 7000000,
            'months_worked' => 6,
            'employee_status' => 'Karyawan tetap',
        ]);

        $response->assertRedirect('/tools/kalkulator-thr');

        $this->followRedirects($response)->assertSee('Estimasi THR');
    }

    public function test_tax_consultant_fee_calculator_can_preview_result(): void
    {
        $response = $this->from('/tools/kalkulator-fee-konsultan-pajak')->post('/tools/kalkulator-fee-konsultan-pajak/calculate', [
            'transaction_value' => 250000000,
            'company_revenue' => 400000000,
            'basis_type' => 'revenue',
        ]);

        $response->assertRedirect('/tools/kalkulator-fee-konsultan-pajak');

        $this->followRedirects($response)
            ->assertSee('Estimasi fee 5%')
            ->assertSee('Revenue perusahaan');
    }

    public function test_invoice_generator_shows_template_cards(): void
    {
        $this->get('/tools/generator-invoice')
            ->assertOk()
            ->assertSee('Pilih Template Desain Invoice')
            ->assertSee('Classic')
            ->assertSee('Modern');
    }

    public function test_letter_generator_can_preview_document_template(): void
    {
        $response = $this->from('/tools/generator-surat-izin')->post('/tools/generator-surat-izin/preview', [
            'template_slug' => 'letter-formal',
            'name' => 'Rizky Firmansyah',
            'position' => 'Staff Operasional',
            'company' => 'PT Bantu Kerja Sejahtera',
            'date' => '2026-05-16',
            'city' => 'Jakarta',
            'recipient' => 'HRD',
            'reason' => 'Perlu izin satu hari untuk keperluan keluarga yang tidak dapat ditinggalkan.',
        ]);

        $response->assertRedirect('/tools/generator-surat-izin');

        $this->followRedirects($response)
            ->assertSee('Preview Surat')
            ->assertSee('Surat Izin Kerja')
            ->assertSee('Rizky Firmansyah');
    }

    public function test_receipt_generator_can_preview_and_log_template_usage(): void
    {
        $response = $this->from('/tools/generator-kwitansi')->post('/tools/generator-kwitansi/preview', [
            'template_slug' => 'receipt-classic',
            'receipt_number' => 'KW-2026-019',
            'payer_name' => 'PT Cipta Solusi Digital',
            'receiver_name' => 'BantuKerja Studio',
            'amount' => 1750000,
            'receipt_date' => '2026-05-16',
            'city' => 'Jakarta',
            'payment_method' => 'Transfer bank',
            'description' => 'Pelunasan jasa pembukuan bulan April 2026.',
            'notes' => 'Pembayaran tahap akhir.',
        ]);

        $response->assertRedirect('/tools/generator-kwitansi');

        $this->followRedirects($response)
            ->assertSee('Preview Kwitansi')
            ->assertSee('PT Cipta Solusi Digital')
            ->assertSee('BantuKerja Studio');

        $this->assertDatabaseHas(GeneratorDownloadLog::class, [
            'generator_type' => 'receipt',
            'template_slug' => 'receipt-classic',
            'action' => 'preview',
        ]);
    }

    public function test_minutes_generator_can_preview_document_template(): void
    {
        $response = $this->from('/tools/generator-berita-acara')->post('/tools/generator-berita-acara/preview', [
            'template_slug' => 'minutes-formal',
            'title' => 'Berita Acara Serah Terima Dokumen',
            'document_number' => 'BAST/05/2026',
            'event_date' => '2026-05-16',
            'location' => 'Jakarta Selatan',
            'opening' => 'Pada hari ini telah dilakukan serah terima dokumen antara para pihak di bawah ini.',
            'first_party_name' => 'Rina Maharani',
            'first_party_role' => 'Manager Operasional',
            'second_party_name' => 'Dimas Pratama',
            'second_party_role' => 'Supervisor Administrasi',
            'content' => "Pihak pertama menyerahkan dokumen operasional.\nPihak kedua menerima seluruh dokumen dalam keadaan lengkap dan baik.",
            'closing' => 'Demikian berita acara ini dibuat untuk digunakan sebagaimana mestinya.',
        ]);

        $response->assertRedirect('/tools/generator-berita-acara');

        $this->followRedirects($response)
            ->assertSee('Preview Berita Acara')
            ->assertSee('Berita Acara Serah Terima Dokumen')
            ->assertSee('Rina Maharani');
    }

    public function test_admin_user_can_access_filament_resources(): void
    {
        $admin = User::where('email', 'admin@bantukerja.online')->firstOrFail();

        $this->actingAs($admin)
            ->get('/admin/tools')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/posts/create')
            ->assertOk();
    }

    public function test_contact_form_submission_is_stored(): void
    {
        $response = $this->from('/contact')->post('/contact', [
            'name' => 'Rizky Ramadhan',
            'email' => 'rizky@example.com',
            'subject' => 'Kerja sama konten',
            'message' => 'Halo tim, saya ingin mengusulkan kerja sama konten untuk topik template bisnis.',
            'website' => '',
        ]);

        $response->assertRedirect('/contact#contact-form');

        $this->assertDatabaseHas(ContactMessage::class, [
            'email' => 'rizky@example.com',
            'subject' => 'Kerja sama konten',
            'status' => 'new',
        ]);
    }

    public function test_editor_cannot_access_sensitive_admin_resources(): void
    {
        $editor = User::factory()->create([
            'role' => 'editor',
        ]);

        $this->actingAs($editor)
            ->get('/admin/settings')
            ->assertForbidden();

        $this->actingAs($editor)
            ->get('/admin/ad-slots')
            ->assertForbidden();
    }

    public function test_cv_ats_generator_can_preview_result(): void
    {
        $response = $this->from('/tools/generator-cv-ats')->post('/tools/generator-cv-ats/calculate', [
            'full_name' => 'Ayu Maharani',
            'professional_title' => 'Digital Marketing Specialist',
            'email' => 'ayu@example.com',
            'phone' => '08123456789',
            'city' => 'Jakarta',
            'linkedin' => 'https://www.linkedin.com/in/ayu-maharani',
            'portfolio' => 'https://portfolio.example.com',
            'summary' => 'Profesional pemasaran digital dengan pengalaman mengelola campaign berbayar, SEO, dan optimasi funnel untuk meningkatkan lead berkualitas.',
            'skills' => 'SEO, Google Ads, Meta Ads, Copywriting',
            'languages' => 'Indonesia, English',
            'certifications' => "Google Ads Search Certification\nMeta Certified Digital Marketing Associate",
            'achievements' => "Meningkatkan lead 40% dalam 6 bulan\nMenurunkan CPL 22%",
            'work_experiences' => [
                [
                    'job_title' => 'Digital Marketing Specialist',
                    'company' => 'PT Maju Bersama',
                    'location' => 'Jakarta',
                    'start_date' => '2022-01-01',
                    'end_date' => '2024-12-01',
                    'description' => "Mengelola campaign Google Ads\nOptimasi landing page\nMembuat laporan mingguan",
                ],
            ],
            'educations' => [
                [
                    'degree' => 'S1 Ilmu Komunikasi',
                    'institution' => 'Universitas Indonesia',
                    'location' => 'Depok',
                    'start_year' => 2017,
                    'end_year' => 2021,
                    'description' => 'Fokus pada komunikasi pemasaran dan riset konsumen.',
                ],
            ],
        ]);

        $response->assertRedirect('/tools/generator-cv-ats');

        $this->followRedirects($response)->assertSee('Download PDF');
    }

    public function test_related_content_sections_show_relevant_links(): void
    {
        $this->get('/blog/cara-menghitung-thr-karyawan')
            ->assertOk()
            ->assertSee('Kalkulator THR')
            ->assertSee('CV Sederhana');

        $this->get('/tools/generator-invoice')
            ->assertOk()
            ->assertSee('Cara membuat invoice sederhana agar tagihan terlihat profesional')
            ->assertSee('Invoice Sederhana');

        $this->get('/template/surat-resign')
            ->assertOk()
            ->assertSee('Contoh surat resign profesional yang tetap menjaga hubungan baik')
            ->assertSee('Generator Surat Izin Kerja');
    }

    public function test_internal_post_publisher_requires_valid_token(): void
    {
        $this->postJson('/api/internal/posts/from-ai', [
            'category_slug' => 'dokumen-kerja',
            'title' => 'Artikel internal tanpa token',
            'excerpt' => 'Percobaan publish tanpa token yang valid.',
            'content' => '<p>Konten</p>',
        ])->assertForbidden();
    }

    public function test_internal_post_publisher_can_create_post_and_faqs(): void
    {
        $response = $this->withHeaders([
            'X-BK-Internal-Token' => 'testing-internal-publisher-token',
        ])->postJson('/api/internal/posts/from-ai', [
            'category_slug' => 'dokumen-kerja',
            'title' => 'Cara menyusun SOP administrasi agar tim lebih konsisten',
            'excerpt' => 'Panduan praktis menyusun SOP administrasi agar pekerjaan lebih konsisten dan mudah diaudit.',
            'content' => '<h2>Pendahuluan</h2><p>Konten artikel lengkap.</p>',
            'meta_title' => 'Cara menyusun SOP administrasi agar tim lebih konsisten',
            'meta_description' => 'Pelajari cara menyusun SOP administrasi yang rapi, jelas, dan mudah diikuti oleh tim.',
            'status' => 'published',
            'faqs' => [
                [
                    'question' => 'Apa manfaat SOP administrasi?',
                    'answer' => 'SOP membantu tim bekerja lebih konsisten, rapi, dan mudah dievaluasi.',
                ],
                [
                    'question' => 'Apakah SOP harus panjang?',
                    'answer' => 'Tidak. Yang penting jelas, spesifik, dan mudah dipahami oleh tim yang menjalankannya.',
                ],
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Cara menyusun SOP administrasi agar tim lebih konsisten')
            ->assertJsonPath('data.status', 'published')
            ->assertJsonPath('data.faqs_count', 2);

        $this->assertDatabaseHas('posts', [
            'title' => 'Cara menyusun SOP administrasi agar tim lebih konsisten',
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('faqs', [
            'faqable_type' => Post::class,
            'question' => 'Apa manfaat SOP administrasi?',
            'is_active' => true,
        ]);
    }
}
