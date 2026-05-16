<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicWebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

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
}
