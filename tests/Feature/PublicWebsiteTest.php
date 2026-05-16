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
}
