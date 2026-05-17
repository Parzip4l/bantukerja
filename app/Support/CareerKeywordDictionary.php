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
                'star' => [
                    'question' => 'Ceritakan saat Anda harus merapikan proses administrasi yang sempat berantakan.',
                    'experience_type' => 'pengalaman-kerja',
                    'situation' => 'Tim operasional mengalami keterlambatan rekap dokumen karena format data dari beberapa PIC tidak konsisten.',
                    'task' => 'Saya diminta merapikan alur rekap dan memastikan dokumen harian tetap bisa selesai tepat waktu.',
                    'action' => 'Saya membuat format rekap yang seragam, menyusun checklist kelengkapan data, lalu melakukan follow up rutin ke PIC yang belum melengkapi dokumen.',
                    'result' => 'Proses rekap menjadi lebih cepat, kesalahan input berkurang, dan tim lebih mudah memantau dokumen yang masih pending.',
                    'highlight_skills' => 'Administrasi, ketelitian, koordinasi, Microsoft Excel',
                ],
            ],
            'customer-service' => [
                'label' => 'Customer Service',
                'category' => 'customer-service',
                'skills' => ['Komunikasi', 'Complaint Handling', 'Follow Up'],
                'industry' => 'Layanan pelanggan',
                'career_target' => 'bertumbuh di area customer experience dan service operations',
                'experience' => 'Berpengalaman menangani pelanggan, follow up keluhan, dan menjaga kualitas layanan.',
                'matcher_profile' => 'Profil cocok untuk komunikasi pelanggan, penyelesaian masalah, dan monitoring SLA layanan.',
                'star' => [
                    'question' => 'Ceritakan saat Anda menangani keluhan pelanggan yang cukup menantang.',
                    'experience_type' => 'pengalaman-kerja',
                    'situation' => 'Seorang pelanggan menyampaikan keluhan karena status pesanan belum diperbarui cukup lama dan mulai merasa kecewa.',
                    'task' => 'Saya harus menenangkan pelanggan, memastikan akar masalahnya, dan memberi kejelasan tindak lanjut secepat mungkin.',
                    'action' => 'Saya mendengarkan keluhan dengan tenang, mengecek detail pesanan ke sistem dan tim terkait, lalu memberi update yang jelas beserta estimasi penyelesaian.',
                    'result' => 'Pelanggan merasa lebih tenang, masalah bisa ditindaklanjuti dengan cepat, dan komunikasi setelah itu menjadi lebih lancar.',
                    'highlight_skills' => 'Komunikasi, complaint handling, empati, follow up',
                ],
            ],
            'sales-executive' => [
                'label' => 'Sales Executive',
                'category' => 'sales',
                'skills' => ['Prospecting', 'Negotiation', 'Closing'],
                'industry' => 'Penjualan dan bisnis',
                'career_target' => 'meningkatkan performa penjualan dan relasi klien',
                'experience' => 'Terbiasa membangun pipeline, presentasi penawaran, dan follow up prospek hingga closing.',
                'matcher_profile' => 'Profil cocok untuk target penjualan, relasi klien, dan pengembangan pipeline.',
                'star' => [
                    'question' => 'Ceritakan saat Anda berhasil meyakinkan calon klien yang awalnya ragu.',
                    'experience_type' => 'pengalaman-kerja',
                    'situation' => 'Saya menghadapi prospek yang tertarik dengan produk kami, tetapi masih ragu karena membandingkan beberapa vendor lain.',
                    'task' => 'Saya perlu memahami keberatan mereka dan menunjukkan nilai solusi kami tanpa terkesan memaksa.',
                    'action' => 'Saya menggali kebutuhan utama klien, menyesuaikan presentasi dengan pain point mereka, lalu melakukan follow up dengan penawaran yang lebih relevan.',
                    'result' => 'Percakapan menjadi lebih positif dan prospek akhirnya melanjutkan diskusi ke tahap penawaran yang lebih serius.',
                    'highlight_skills' => 'Negosiasi, komunikasi, presentasi, closing',
                ],
            ],
            'finance-staff' => [
                'label' => 'Finance Staff',
                'category' => 'finance',
                'skills' => ['Excel', 'Rekonsiliasi', 'Invoice'],
                'industry' => 'Keuangan perusahaan',
                'career_target' => 'berkembang di area finance operations dan reporting',
                'experience' => 'Terbiasa mengolah data transaksi, invoice, dan laporan pembayaran.',
                'matcher_profile' => 'Profil cocok untuk invoice, rekonsiliasi, pelaporan keuangan, dan kontrol data transaksi.',
                'star' => [
                    'question' => 'Ceritakan saat Anda menemukan selisih data keuangan atau pembayaran.',
                    'experience_type' => 'pengalaman-kerja',
                    'situation' => 'Saat proses rekonsiliasi, saya menemukan ada selisih antara data pembayaran dan catatan invoice vendor.',
                    'task' => 'Saya perlu memastikan penyebab selisih tersebut agar laporan tetap akurat sebelum ditutup.',
                    'action' => 'Saya menelusuri dokumen pendukung, mencocokkan mutasi pembayaran dengan invoice terkait, lalu mengonfirmasi data yang belum sinkron ke pihak terkait.',
                    'result' => 'Selisih dapat dijelaskan dengan jelas dan laporan keuangan bisa diselesaikan dengan data yang lebih rapi dan akurat.',
                    'highlight_skills' => 'Rekonsiliasi, Excel, ketelitian, administrasi keuangan',
                ],
            ],
            'hr-staff' => [
                'label' => 'HR Staff',
                'category' => 'hr',
                'skills' => ['Recruitment', 'Onboarding', 'Administrasi Karyawan'],
                'industry' => 'Human resources',
                'career_target' => 'mengembangkan peran di HR operations dan talent support',
                'experience' => 'Terlibat dalam proses rekrutmen, administrasi karyawan, dan koordinasi onboarding.',
                'matcher_profile' => 'Profil cocok untuk rekrutmen, HR admin, onboarding, dan koordinasi people operations.',
                'star' => [
                    'question' => 'Ceritakan saat Anda membantu proses rekrutmen atau onboarding berjalan lebih rapi.',
                    'experience_type' => 'pengalaman-kerja',
                    'situation' => 'Tim HR sedang menangani beberapa kebutuhan hiring sekaligus dan alur administrasi kandidat mulai tidak terpantau rapi.',
                    'task' => 'Saya diminta membantu menjaga agar dokumen kandidat dan jadwal proses tetap terkoordinasi.',
                    'action' => 'Saya membuat tracker kandidat, memperbarui status secara berkala, dan memastikan dokumen onboarding disiapkan lebih awal untuk kandidat terpilih.',
                    'result' => 'Proses koordinasi menjadi lebih terstruktur dan tim HR lebih mudah memantau progres tiap kandidat maupun kebutuhan onboarding.',
                    'highlight_skills' => 'Recruitment, koordinasi, administrasi karyawan, ketelitian',
                ],
            ],
            'it-support' => [
                'label' => 'IT Support',
                'category' => 'it-support',
                'skills' => ['Troubleshooting', 'Helpdesk', 'Jaringan Dasar'],
                'industry' => 'Teknologi informasi',
                'career_target' => 'bertumbuh di area IT support dan infrastructure operations',
                'experience' => 'Menangani kendala perangkat, software, dan support user harian.',
                'matcher_profile' => 'Profil cocok untuk helpdesk, troubleshooting, instalasi perangkat, dan eskalasi issue teknis.',
                'star' => [
                    'question' => 'Ceritakan saat Anda menangani gangguan teknis yang menghambat pekerjaan user.',
                    'experience_type' => 'pengalaman-kerja',
                    'situation' => 'Beberapa user melaporkan kendala pada perangkat kerja yang membuat aktivitas operasional mereka tertunda.',
                    'task' => 'Saya harus memastikan gangguan cepat teridentifikasi dan ditangani agar user bisa kembali bekerja.',
                    'action' => 'Saya melakukan pengecekan awal, mengelompokkan prioritas issue, melakukan troubleshooting di sisi perangkat dan software, lalu memberi update ke user terkait progres penyelesaian.',
                    'result' => 'Gangguan dapat ditangani lebih terarah dan user mendapatkan kepastian tindak lanjut tanpa harus menunggu terlalu lama.',
                    'highlight_skills' => 'Troubleshooting, helpdesk, komunikasi user, problem solving',
                ],
            ],
            'digital-marketing' => [
                'label' => 'Digital Marketing',
                'category' => 'digital-marketing',
                'skills' => ['SEO', 'Content Strategy', 'Analytics'],
                'industry' => 'Pemasaran digital',
                'career_target' => 'bertumbuh di area performance marketing dan content growth',
                'experience' => 'Terbiasa mengelola campaign, konten digital, dan evaluasi performa channel marketing.',
                'matcher_profile' => 'Profil cocok untuk campaign digital, SEO, content, ads, dan analisis performa.',
                'star' => [
                    'question' => 'Ceritakan saat Anda memperbaiki performa campaign atau konten digital.',
                    'experience_type' => 'pengalaman-kerja',
                    'situation' => 'Salah satu campaign digital yang saya tangani belum mencapai performa yang diharapkan di awal periode berjalan.',
                    'task' => 'Saya perlu mengevaluasi penyebabnya dan mencari perbaikan yang paling realistis dalam waktu singkat.',
                    'action' => 'Saya meninjau data performa, membandingkan materi iklan dan audience yang digunakan, lalu menyesuaikan copy, visual, dan distribusi channel yang lebih potensial.',
                    'result' => 'Campaign menjadi lebih terarah dan hasil evaluasinya bisa dipakai sebagai dasar optimasi untuk periode berikutnya.',
                    'highlight_skills' => 'Analytics, SEO, content strategy, problem solving',
                ],
            ],
            'backend-developer' => [
                'label' => 'Backend Developer',
                'category' => 'backend-developer',
                'skills' => ['PHP', 'Laravel', 'SQL'],
                'industry' => 'Software development',
                'career_target' => 'membangun backend system yang stabil dan scalable',
                'experience' => 'Terlibat dalam pengembangan API, business logic, dan optimasi database.',
                'matcher_profile' => 'Profil cocok untuk backend development, API, database, debugging, dan Laravel.',
                'star' => [
                    'question' => 'Ceritakan saat Anda menyelesaikan masalah teknis di backend atau API.',
                    'experience_type' => 'pengalaman-kerja',
                    'situation' => 'Saat pengembangan fitur, saya menemukan kendala pada alur backend yang menyebabkan proses data berjalan tidak stabil.',
                    'task' => 'Saya bertanggung jawab menelusuri sumber masalah dan memastikan fitur bisa berjalan lebih andal.',
                    'action' => 'Saya melakukan debugging pada logic yang terkait, meninjau query dan struktur data, lalu memperbaiki alur proses agar lebih konsisten dan mudah dipantau.',
                    'result' => 'Fitur dapat berjalan lebih stabil dan tim memiliki dasar yang lebih jelas untuk melanjutkan pengembangan berikutnya.',
                    'highlight_skills' => 'PHP, Laravel, SQL, debugging, API',
                ],
            ],
            'frontend-developer' => [
                'label' => 'Frontend Developer',
                'category' => 'frontend-developer',
                'skills' => ['JavaScript', 'React', 'Responsive UI'],
                'industry' => 'Software development',
                'career_target' => 'mengembangkan pengalaman pengguna melalui frontend yang rapi dan cepat',
                'experience' => 'Mengerjakan antarmuka web, komponen reusable, dan responsive layout.',
                'matcher_profile' => 'Profil cocok untuk frontend, React/Vue, UI implementation, dan responsive interface.',
                'star' => [
                    'question' => 'Ceritakan saat Anda memperbaiki tampilan atau pengalaman pengguna di frontend.',
                    'experience_type' => 'pengalaman-kerja',
                    'situation' => 'Saya menangani halaman yang secara visual kurang rapi dan cukup menyulitkan user di perangkat mobile.',
                    'task' => 'Saya perlu merapikan tampilan sekaligus menjaga agar implementasinya tetap konsisten dengan komponen yang ada.',
                    'action' => 'Saya meninjau struktur layout, memperbaiki hierarchy visual, menyesuaikan spacing dan responsive behavior, lalu menguji ulang hasilnya di beberapa ukuran layar.',
                    'result' => 'Tampilan menjadi lebih jelas dipakai dan pengalaman pengguna terasa lebih nyaman di desktop maupun mobile.',
                    'highlight_skills' => 'JavaScript, React, responsive UI, attention to detail',
                ],
            ],
            'project-manager' => [
                'label' => 'Project Manager',
                'category' => 'project-manager',
                'skills' => ['Timeline Management', 'Stakeholder Coordination', 'Reporting'],
                'industry' => 'Manajemen proyek',
                'career_target' => 'menangani proyek lintas tim dengan eksekusi yang lebih matang',
                'experience' => 'Terbiasa memantau timeline, koordinasi lintas fungsi, dan update progress proyek.',
                'matcher_profile' => 'Profil cocok untuk koordinasi stakeholder, pelaporan, risk tracking, dan project delivery.',
                'star' => [
                    'question' => 'Ceritakan saat Anda menjaga proyek tetap berjalan meski ada hambatan.',
                    'experience_type' => 'pengalaman-kerja',
                    'situation' => 'Dalam satu proyek lintas tim, ada beberapa dependensi yang berisiko menggeser timeline utama.',
                    'task' => 'Saya perlu menjaga koordinasi antar pihak dan memastikan risiko itu tidak berkembang tanpa kontrol.',
                    'action' => 'Saya memetakan prioritas, mengatur komunikasi rutin dengan stakeholder utama, dan menyiapkan tindak lanjut yang jelas untuk tiap blocker.',
                    'result' => 'Tim memiliki visibilitas yang lebih baik terhadap progres proyek dan hambatan dapat ditangani lebih cepat sebelum berdampak lebih besar.',
                    'highlight_skills' => 'Stakeholder coordination, reporting, timeline management, problem solving',
                ],
            ],
            'operations-manager' => [
                'label' => 'Operations Manager',
                'category' => 'operations',
                'skills' => ['SOP', 'Monitoring', 'Coordination'],
                'industry' => 'Operasional bisnis',
                'career_target' => 'memimpin proses operasional yang lebih efisien dan terukur',
                'experience' => 'Menangani pengawasan proses kerja, evaluasi operasional, dan koordinasi perbaikan.',
                'matcher_profile' => 'Profil cocok untuk monitoring operasional, SOP, koordinasi tim, dan follow up tindak lanjut.',
                'star' => [
                    'question' => 'Ceritakan saat Anda memperbaiki proses operasional yang kurang efisien.',
                    'experience_type' => 'pengalaman-kerja',
                    'situation' => 'Saya melihat ada proses operasional harian yang memakan waktu terlalu lama dan sering menimbulkan follow up berulang.',
                    'task' => 'Saya perlu membantu tim membuat alur kerja yang lebih tertata tanpa mengganggu proses berjalan.',
                    'action' => 'Saya memetakan titik bottleneck, berdiskusi dengan PIC terkait, lalu menyusun langkah kerja dan monitoring yang lebih sederhana untuk dijalankan tim.',
                    'result' => 'Proses kerja menjadi lebih rapi, koordinasi lebih jelas, dan tindak lanjut dapat dipantau dengan lebih konsisten.',
                    'highlight_skills' => 'SOP, monitoring, koordinasi, evaluasi proses',
                ],
            ],
            'fresh-graduate' => [
                'label' => 'Fresh Graduate',
                'category' => 'fresh-graduate',
                'skills' => ['Komunikasi', 'Belajar Cepat', 'Kerja Tim'],
                'industry' => 'Karier awal',
                'career_target' => 'memulai karier profesional dan berkembang di peran yang relevan',
                'experience' => 'Aktif di organisasi, project kampus, atau magang yang membentuk dasar cara kerja profesional.',
                'matcher_profile' => 'Profil cocok untuk peran entry level yang menilai potensi, inisiatif belajar, dan kemampuan adaptasi.',
                'star' => [
                    'question' => 'Ceritakan pengalaman saat Anda menyelesaikan tantangan dalam organisasi, magang, atau project kampus.',
                    'experience_type' => 'project-kuliah',
                    'situation' => 'Dalam salah satu project kampus, tim saya menghadapi pembagian tugas yang belum rapi sehingga progres sempat melambat.',
                    'task' => 'Saya berinisiatif membantu tim agar pekerjaan bisa kembali terarah dan target tetap tercapai.',
                    'action' => 'Saya membantu menyusun pembagian tugas yang lebih jelas, mengingatkan timeline pengerjaan, dan memastikan komunikasi tim tetap berjalan.',
                    'result' => 'Project dapat selesai dengan lebih terstruktur dan saya belajar banyak tentang koordinasi, tanggung jawab, dan kerja tim.',
                    'highlight_skills' => 'Kerja tim, komunikasi, inisiatif, belajar cepat',
                ],
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
