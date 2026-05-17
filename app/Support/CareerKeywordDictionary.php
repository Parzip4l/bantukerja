<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CareerKeywordDictionary
{
    public static function categories(): array
    {
        return [
            'admin' => ['administrasi', 'data entry', 'dokumen', 'arsip', 'excel', 'google sheets', 'laporan', 'filing', 'surat menyurat'],
            'customer-service' => ['komunikasi', 'pelanggan', 'complaint handling', 'follow up', 'service', 'chat', 'telepon', 'problem solving', 'crm'],
            'sales' => ['target', 'prospecting', 'negosiasi', 'closing', 'customer', 'pipeline', 'follow up', 'revenue', 'presentasi'],
            'finance' => ['accounting', 'invoice', 'rekonsiliasi', 'laporan keuangan', 'pajak', 'excel', 'kas', 'pembayaran', 'budgeting'],
            'hr' => ['rekrutmen', 'payroll', 'absensi', 'training', 'onboarding', 'employee relation', 'administrasi karyawan', 'interview', 'kpi'],
            'it-support' => ['troubleshooting', 'hardware', 'software', 'jaringan', 'ticketing', 'helpdesk', 'windows', 'printer', 'instalasi'],
            'digital-marketing' => ['seo', 'social media', 'content', 'ads', 'campaign', 'analytics', 'copywriting', 'meta ads', 'google ads'],
            'backend-developer' => ['php', 'laravel', 'sql', 'api', 'git', 'debugging', 'database', 'rest api', 'backend'],
            'frontend-developer' => ['javascript', 'react', 'vue', 'html', 'css', 'ui', 'responsive', 'git', 'frontend'],
            'project-manager' => ['timeline', 'stakeholder', 'risk', 'issue', 'coordination', 'reporting', 'agile', 'scrum', 'budget'],
            'operations' => ['operasional', 'sop', 'monitoring', 'vendor', 'quality control', 'laporan', 'koordinasi', 'checklist', 'follow up'],
            'fresh-graduate' => ['organisasi', 'magang', 'project', 'presentasi', 'komunikasi', 'belajar cepat', 'adaptasi', 'inisiatif'],
            'developer' => ['php', 'laravel', 'javascript', 'react', 'vue', 'sql', 'api', 'git', 'debugging', 'database'],
            'lainnya' => [],
        ];
    }

    public static function positionPresets(): array
    {
        return [
            'admin-operasional' => [
                'label' => 'Admin Operasional',
                'category' => 'admin',
                'skills' => ['Microsoft Excel', 'Administrasi', 'Pelaporan'],
                'industry' => 'Operasional bisnis',
                'career_target' => 'mengembangkan peran administrasi dan operasional yang lebih strategis',
                'experience' => 'Terbiasa menangani rekap data, dokumen operasional, dan koordinasi tindak lanjut harian.',
                'matcher_profile' => 'Profil cocok untuk administrasi, pengolahan data, dan pelaporan operasional harian.',
            ],
            'customer-service' => [
                'label' => 'Customer Service',
                'category' => 'customer-service',
                'skills' => ['Komunikasi', 'Complaint Handling', 'Follow Up'],
                'industry' => 'Layanan pelanggan',
                'career_target' => 'bertumbuh di area customer experience dan service operations',
                'experience' => 'Berpengalaman menangani pelanggan, follow up keluhan, dan menjaga kualitas layanan.',
                'matcher_profile' => 'Profil cocok untuk komunikasi pelanggan, penyelesaian masalah, dan monitoring SLA layanan.',
            ],
            'sales-executive' => [
                'label' => 'Sales Executive',
                'category' => 'sales',
                'skills' => ['Prospecting', 'Negotiation', 'Closing'],
                'industry' => 'Penjualan dan bisnis',
                'career_target' => 'meningkatkan performa penjualan dan relasi klien',
                'experience' => 'Terbiasa membangun pipeline, presentasi penawaran, dan follow up prospek hingga closing.',
                'matcher_profile' => 'Profil cocok untuk target penjualan, relasi klien, dan pengembangan pipeline.',
            ],
            'finance-staff' => [
                'label' => 'Finance Staff',
                'category' => 'finance',
                'skills' => ['Excel', 'Rekonsiliasi', 'Invoice'],
                'industry' => 'Keuangan perusahaan',
                'career_target' => 'berkembang di area finance operations dan reporting',
                'experience' => 'Terbiasa mengolah data transaksi, invoice, dan laporan pembayaran.',
                'matcher_profile' => 'Profil cocok untuk invoice, rekonsiliasi, pelaporan keuangan, dan kontrol data transaksi.',
            ],
            'hr-staff' => [
                'label' => 'HR Staff',
                'category' => 'hr',
                'skills' => ['Recruitment', 'Onboarding', 'Administrasi Karyawan'],
                'industry' => 'Human resources',
                'career_target' => 'mengembangkan peran di HR operations dan talent support',
                'experience' => 'Terlibat dalam proses rekrutmen, administrasi karyawan, dan koordinasi onboarding.',
                'matcher_profile' => 'Profil cocok untuk rekrutmen, HR admin, onboarding, dan koordinasi people operations.',
            ],
            'it-support' => [
                'label' => 'IT Support',
                'category' => 'it-support',
                'skills' => ['Troubleshooting', 'Helpdesk', 'Jaringan Dasar'],
                'industry' => 'Teknologi informasi',
                'career_target' => 'bertumbuh di area IT support dan infrastructure operations',
                'experience' => 'Menangani kendala perangkat, software, dan support user harian.',
                'matcher_profile' => 'Profil cocok untuk helpdesk, troubleshooting, instalasi perangkat, dan eskalasi issue teknis.',
            ],
            'digital-marketing' => [
                'label' => 'Digital Marketing',
                'category' => 'digital-marketing',
                'skills' => ['SEO', 'Content Strategy', 'Analytics'],
                'industry' => 'Pemasaran digital',
                'career_target' => 'bertumbuh di area performance marketing dan content growth',
                'experience' => 'Terbiasa mengelola campaign, konten digital, dan evaluasi performa channel marketing.',
                'matcher_profile' => 'Profil cocok untuk campaign digital, SEO, content, ads, dan analisis performa.',
            ],
            'backend-developer' => [
                'label' => 'Backend Developer',
                'category' => 'backend-developer',
                'skills' => ['PHP', 'Laravel', 'SQL'],
                'industry' => 'Software development',
                'career_target' => 'membangun backend system yang stabil dan scalable',
                'experience' => 'Terlibat dalam pengembangan API, business logic, dan optimasi database.',
                'matcher_profile' => 'Profil cocok untuk backend development, API, database, debugging, dan Laravel.',
            ],
            'frontend-developer' => [
                'label' => 'Frontend Developer',
                'category' => 'frontend-developer',
                'skills' => ['JavaScript', 'React', 'Responsive UI'],
                'industry' => 'Software development',
                'career_target' => 'mengembangkan pengalaman pengguna melalui frontend yang rapi dan cepat',
                'experience' => 'Mengerjakan antarmuka web, komponen reusable, dan responsive layout.',
                'matcher_profile' => 'Profil cocok untuk frontend, React/Vue, UI implementation, dan responsive interface.',
            ],
            'project-manager' => [
                'label' => 'Project Manager',
                'category' => 'project-manager',
                'skills' => ['Timeline Management', 'Stakeholder Coordination', 'Reporting'],
                'industry' => 'Manajemen proyek',
                'career_target' => 'menangani proyek lintas tim dengan eksekusi yang lebih matang',
                'experience' => 'Terbiasa memantau timeline, koordinasi lintas fungsi, dan update progress proyek.',
                'matcher_profile' => 'Profil cocok untuk koordinasi stakeholder, pelaporan, risk tracking, dan project delivery.',
            ],
            'operations-manager' => [
                'label' => 'Operations Manager',
                'category' => 'operations',
                'skills' => ['SOP', 'Monitoring', 'Coordination'],
                'industry' => 'Operasional bisnis',
                'career_target' => 'memimpin proses operasional yang lebih efisien dan terukur',
                'experience' => 'Menangani pengawasan proses kerja, evaluasi operasional, dan koordinasi perbaikan.',
                'matcher_profile' => 'Profil cocok untuk monitoring operasional, SOP, koordinasi tim, dan follow up tindak lanjut.',
            ],
            'fresh-graduate' => [
                'label' => 'Fresh Graduate',
                'category' => 'fresh-graduate',
                'skills' => ['Komunikasi', 'Belajar Cepat', 'Kerja Tim'],
                'industry' => 'Karier awal',
                'career_target' => 'memulai karier profesional dan berkembang di peran yang relevan',
                'experience' => 'Aktif di organisasi, project kampus, atau magang yang membentuk dasar cara kerja profesional.',
                'matcher_profile' => 'Profil cocok untuk peran entry level yang menilai potensi, inisiatif belajar, dan kemampuan adaptasi.',
            ],
        ];
    }

    public static function labelMap(): array
    {
        return [
            'admin' => 'Admin',
            'customer-service' => 'Customer Service',
            'sales' => 'Sales',
            'finance' => 'Finance',
            'hr' => 'HR',
            'it-support' => 'IT Support',
            'digital-marketing' => 'Digital Marketing',
            'backend-developer' => 'Backend Developer',
            'frontend-developer' => 'Frontend Developer',
            'project-manager' => 'Project Manager',
            'operations' => 'Operations',
            'fresh-graduate' => 'Fresh Graduate',
            'lainnya' => 'Lainnya',
        ];
    }

    public static function stopwords(): array
    {
        return [
            'dan', 'atau', 'yang', 'untuk', 'dengan', 'pada', 'dari', 'di', 'ke', 'agar', 'serta', 'kami', 'anda',
            'the', 'and', 'for', 'with', 'from', 'this', 'that', 'will', 'have', 'has', 'job', 'position', 'role',
            'adalah', 'sebagai', 'dalam', 'lebih', 'akan', 'bisa', 'dapat', 'sudah', 'minimal', 'tahun', 'pengalaman',
        ];
    }

    public static function keywordsForCategory(?string $category): array
    {
        $normalized = Str::of((string) $category)->lower()->slug('-')->toString();

        return self::categories()[$normalized] ?? [];
    }

    public static function recommendKeywords(string $position): array
    {
        $category = self::guessCategory($position);
        $keywords = self::keywordsForCategory($category);

        return collect($keywords)->take(8)->values()->all();
    }

    public static function guessCategory(?string $value): string
    {
        $normalized = Str::of((string) $value)->lower()->toString();

        foreach (self::labelMap() as $slug => $label) {
            if ($slug === 'lainnya') {
                continue;
            }

            if (str_contains($normalized, Str::lower($label))) {
                return $slug;
            }
        }

        $fallbackMatches = [
            'developer' => ['developer', 'programmer', 'engineer'],
            'admin' => ['admin', 'administrasi'],
            'customer-service' => ['customer service', 'cs', 'pelanggan'],
            'sales' => ['sales', 'business development', 'account executive'],
            'finance' => ['finance', 'accounting', 'akuntan'],
            'hr' => ['hr', 'recruiter', 'talent'],
            'it-support' => ['it support', 'helpdesk', 'teknisi'],
            'digital-marketing' => ['marketing', 'seo', 'content'],
            'project-manager' => ['project manager', 'pm', 'scrum'],
            'operations' => ['operasional', 'operations'],
            'fresh-graduate' => ['fresh graduate', 'intern', 'magang'],
        ];

        foreach ($fallbackMatches as $slug => $aliases) {
            foreach ($aliases as $alias) {
                if (str_contains($normalized, $alias)) {
                    return $slug;
                }
            }
        }

        return 'lainnya';
    }

    public static function extractKeywords(string $text, ?string $category = null): array
    {
        $normalized = Str::of(Str::lower($text))
            ->replaceMatches('/[^a-z0-9\s\-\+\.#]/', ' ')
            ->replaceMatches('/\s+/', ' ')
            ->trim()
            ->toString();

        $keywords = collect(preg_split('/\s+/', $normalized) ?: [])
            ->map(fn (string $word): string => trim($word))
            ->filter(fn (string $word): bool => strlen($word) >= 3 && ! in_array($word, self::stopwords(), true));

        $dictionary = collect(self::keywordsForCategory($category))
            ->merge(self::categories()['developer'])
            ->merge(['excel', 'google sheets', 'word', 'powerpoint', 'sql', 'laravel', 'php', 'javascript', 'react', 'vue', 'seo', 'crm', 'erp'])
            ->map(fn (string $item): string => Str::lower($item));

        $matchedPhrases = $dictionary
            ->filter(fn (string $item): bool => str_contains($normalized, $item))
            ->values();

        return $keywords
            ->merge($matchedPhrases)
            ->unique()
            ->values()
            ->all();
    }

    public static function intersect(array $first, array $second): array
    {
        return Collection::make($first)
            ->map(fn (string $item): string => Str::lower(trim($item)))
            ->intersect(
                Collection::make($second)->map(fn (string $item): string => Str::lower(trim($item)))
            )
            ->unique()
            ->values()
            ->all();
    }
}
