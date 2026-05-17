<?php

namespace App\Services\CareerTools;

use App\Support\CareerKeywordDictionary;
use App\Support\DocumentFormatter;
use Illuminate\Support\Collection;

class InterviewQuestionService
{
    public function generate(array $payload): array
    {
        $category = $payload['position_category'] ?? CareerKeywordDictionary::guessCategory($payload['position_applied'] ?? '');
        $questionCount = (int) ($payload['question_count'] ?? 10);
        $types = $this->resolveInterviewTypes($payload['interview_type'] ?? 'campuran');
        $experienceLevel = $payload['experience_level'] ?? 'fresh-graduate';
        $includeTips = ($payload['include_tips'] ?? 'ya') === 'ya';

        $questions = collect();

        foreach ($types as $type) {
            $questions = $questions->merge($this->questionBank($category, $type, $experienceLevel));
        }

        $questions = $questions
            ->unique('question')
            ->take($questionCount)
            ->values()
            ->map(function (array $item, int $index) use ($includeTips): array {
                $item['number'] = $index + 1;
                $item['tip'] = $includeTips ? $item['tip'] : null;

                return $item;
            });

        return [
            'position_applied' => DocumentFormatter::sanitizeText($payload['position_applied'] ?? ''),
            'position_category_label' => CareerKeywordDictionary::labelMap()[$category] ?? 'Lainnya',
            'experience_level_label' => $this->experienceLevelLabel($experienceLevel),
            'interview_type_label' => $this->interviewTypeLabel($payload['interview_type'] ?? 'campuran'),
            'questions' => $questions->all(),
            'preparation_checklist' => $this->preparationChecklist($payload['interview_type'] ?? 'campuran', $experienceLevel),
            'without_ai_note' => 'Tools ini menyusun pertanyaan dari bank pertanyaan lokal dan rule-based logic, tanpa AI API.',
        ];
    }

    protected function resolveInterviewTypes(string $type): array
    {
        return match ($type) {
            'hr-interview' => ['hr', 'behavioral'],
            'user-interview' => ['user', 'behavioral'],
            'technical-interview' => ['technical', 'user'],
            'final-interview' => ['hr', 'user', 'behavioral'],
            default => ['hr', 'user', 'technical', 'behavioral'],
        };
    }

    protected function questionBank(string $category, string $type, string $experienceLevel): Collection
    {
        $base = [
            'hr' => [
                ['category' => 'HR', 'question' => 'Ceritakan tentang diri Anda dan alasan Anda melamar posisi ini.', 'tip' => 'Fokus pada latar belakang, kekuatan utama, dan alasan relevan dengan posisi.'],
                ['category' => 'HR', 'question' => 'Apa yang Anda ketahui tentang perusahaan ini?', 'tip' => 'Sebutkan 2-3 hal spesifik tentang bisnis atau budaya kerjanya.'],
                ['category' => 'HR', 'question' => 'Kenapa kami harus memilih Anda dibanding kandidat lain?', 'tip' => 'Hubungkan ke skill, sikap kerja, dan pengalaman yang paling relevan.'],
            ],
            'user' => [
                ['category' => 'User', 'question' => 'Bagaimana cara Anda mengatur prioritas ketika ada beberapa tugas datang bersamaan?', 'tip' => 'Jelaskan cara menyusun prioritas dan komunikasi dengan tim.'],
                ['category' => 'User', 'question' => 'Ceritakan contoh situasi ketika Anda harus bekerja sama dengan tim untuk menyelesaikan target.', 'tip' => 'Gunakan contoh nyata dengan konteks, peran, dan hasil.'],
                ['category' => 'User', 'question' => 'Apa pendekatan Anda ketika menerima arahan yang berubah di tengah pekerjaan?', 'tip' => 'Tunjukkan fleksibilitas tanpa kehilangan struktur kerja.'],
            ],
            'technical' => [
                ['category' => 'Technical', 'question' => 'Tools atau metode kerja apa yang paling sering Anda gunakan untuk peran ini?', 'tip' => 'Sebutkan tools yang benar-benar Anda kuasai dan konteks penggunaannya.'],
                ['category' => 'Technical', 'question' => 'Bagaimana Anda memastikan hasil kerja tetap rapi dan minim kesalahan?', 'tip' => 'Jelaskan quality check atau workflow yang biasa Anda pakai.'],
            ],
            'behavioral' => [
                ['category' => 'Behavioral', 'question' => 'Ceritakan saat Anda menghadapi kendala kerja dan bagaimana Anda menanganinya.', 'tip' => 'Gunakan pola STAR agar jawaban lebih jelas.'],
                ['category' => 'Behavioral', 'question' => 'Pernahkah Anda menerima feedback yang sulit? Apa yang Anda lakukan setelah itu?', 'tip' => 'Tunjukkan sikap terbuka dan langkah perbaikan yang konkret.'],
            ],
        ];

        $categorySpecific = [
            'admin' => [
                'technical' => [
                    ['category' => 'Technical', 'question' => 'Bagaimana cara Anda menjaga akurasi data saat mengerjakan rekap atau laporan administrasi?', 'tip' => 'Sebutkan pengecekan silang, template, atau penggunaan Excel/Sheets.'],
                    ['category' => 'Technical', 'question' => 'Fitur Excel atau Google Sheets apa yang paling sering Anda gunakan?', 'tip' => 'Pilih fitur yang memang pernah dipakai seperti formula, filter, pivot, atau lookup.'],
                ],
            ],
            'customer-service' => [
                'technical' => [
                    ['category' => 'Technical', 'question' => 'Bagaimana cara Anda menangani pelanggan yang marah atau tidak puas?', 'tip' => 'Tunjukkan empati, klarifikasi masalah, dan langkah tindak lanjut.'],
                    ['category' => 'Technical', 'question' => 'Apa yang Anda lakukan agar follow up pelanggan tidak terlewat?', 'tip' => 'Jelaskan penggunaan catatan, CRM, atau reminder kerja.'],
                ],
            ],
            'sales' => [
                'technical' => [
                    ['category' => 'Technical', 'question' => 'Bagaimana cara Anda membangun pipeline penjualan dari prospek sampai closing?', 'tip' => 'Uraikan tahapan kerja Anda secara runtut.'],
                    ['category' => 'Technical', 'question' => 'Apa contoh strategi follow up yang berhasil menaikkan peluang closing?', 'tip' => 'Gunakan contoh spesifik dan hasil yang terukur jika ada.'],
                ],
            ],
            'finance' => [
                'technical' => [
                    ['category' => 'Technical', 'question' => 'Bagaimana Anda melakukan rekonsiliasi atau pengecekan data transaksi?', 'tip' => 'Jelaskan langkah verifikasi dan dokumen pendukung yang diperiksa.'],
                    ['category' => 'Technical', 'question' => 'Pengalaman apa yang Anda miliki terkait invoice, pembayaran, atau laporan keuangan?', 'tip' => 'Sebutkan proses yang pernah Anda tangani, bukan hanya istilahnya.'],
                ],
            ],
            'hr' => [
                'technical' => [
                    ['category' => 'Technical', 'question' => 'Bagaimana Anda membantu proses rekrutmen atau onboarding berjalan rapi?', 'tip' => 'Ceritakan alur koordinasi, administrasi, atau komunikasi yang pernah Anda pegang.'],
                    ['category' => 'Technical', 'question' => 'Apa pengalaman Anda terkait administrasi karyawan, absensi, atau payroll?', 'tip' => 'Fokus pada scope kerja yang benar-benar pernah Anda lakukan.'],
                ],
            ],
            'it-support' => [
                'technical' => [
                    ['category' => 'Technical', 'question' => 'Langkah apa yang biasanya Anda lakukan saat menerima laporan troubleshooting dari user?', 'tip' => 'Jelaskan alur verifikasi, analisis, tindakan, dan eskalasi.'],
                    ['category' => 'Technical', 'question' => 'Bagaimana Anda membedakan masalah hardware, software, dan jaringan?', 'tip' => 'Berikan contoh diagnosis awal yang praktis.'],
                ],
            ],
            'digital-marketing' => [
                'technical' => [
                    ['category' => 'Technical', 'question' => 'Bagaimana Anda menilai performa campaign digital marketing?', 'tip' => 'Sebutkan metrik seperti CTR, leads, conversion, atau engagement sesuai pengalaman.'],
                    ['category' => 'Technical', 'question' => 'Apa pendekatan Anda dalam membuat konten atau campaign yang relevan dengan target audiens?', 'tip' => 'Hubungkan riset audiens dengan eksekusi konten.'],
                ],
            ],
            'backend-developer' => [
                'technical' => [
                    ['category' => 'Technical', 'question' => 'Bagaimana Anda merancang API atau struktur database agar mudah dikembangkan?', 'tip' => 'Tunjukkan cara berpikir soal maintainability dan debugging.'],
                    ['category' => 'Technical', 'question' => 'Ceritakan bug teknis yang pernah Anda selesaikan dan proses Anda menanganinya.', 'tip' => 'Jelaskan investigasi, root cause, dan hasil perbaikannya.'],
                ],
            ],
            'frontend-developer' => [
                'technical' => [
                    ['category' => 'Technical', 'question' => 'Bagaimana Anda memastikan tampilan frontend tetap responsive dan mudah dipakai?', 'tip' => 'Sebutkan pendekatan layout, testing, dan accessibility yang Anda gunakan.'],
                    ['category' => 'Technical', 'question' => 'Apa pengalaman Anda bekerja dengan React, Vue, atau JavaScript murni?', 'tip' => 'Jelaskan konteks project dan hasil kerja Anda.'],
                ],
            ],
            'project-manager' => [
                'technical' => [
                    ['category' => 'Technical', 'question' => 'Bagaimana Anda menjaga timeline proyek tetap terkendali saat ada perubahan scope?', 'tip' => 'Tunjukkan komunikasi stakeholder dan manajemen prioritas.'],
                    ['category' => 'Technical', 'question' => 'Apa metode yang Anda gunakan untuk memantau progress tim?', 'tip' => 'Sebutkan tools atau ritme reporting yang pernah dipakai.'],
                ],
            ],
            'operations' => [
                'technical' => [
                    ['category' => 'Technical', 'question' => 'Bagaimana Anda memantau pekerjaan operasional harian agar tetap berjalan sesuai SOP?', 'tip' => 'Ceritakan kontrol, checklist, atau koordinasi yang biasa Anda lakukan.'],
                    ['category' => 'Technical', 'question' => 'Apa yang Anda lakukan ketika ada hambatan operasional yang memengaruhi target tim?', 'tip' => 'Jelaskan cara eskalasi dan tindak lanjut yang realistis.'],
                ],
            ],
            'fresh-graduate' => [
                'technical' => [
                    ['category' => 'Technical', 'question' => 'Project kuliah, organisasi, atau magang apa yang paling relevan dengan posisi ini?', 'tip' => 'Pilih contoh yang menunjukkan tanggung jawab dan hasil nyata.'],
                    ['category' => 'Technical', 'question' => 'Bagaimana Anda mempelajari skill baru ketika belum punya pengalaman kerja penuh waktu?', 'tip' => 'Tekankan inisiatif belajar, praktik, dan adaptasi.'],
                ],
            ],
        ];

        $items = collect($base[$type] ?? []);

        if ($experienceLevel === 'fresh-graduate') {
            $items = $items->push(
                ['category' => ucfirst($type), 'question' => 'Pengalaman organisasi, magang, atau project apa yang paling membentuk cara kerja Anda?', 'tip' => 'Pilih pengalaman yang paling relevan dengan posisi dan jelaskan kontribusinya.']
            );
        } else {
            $items = $items->push(
                ['category' => ucfirst($type), 'question' => 'Pencapaian kerja apa yang paling relevan dengan posisi ini?', 'tip' => 'Utamakan contoh dengan hasil, perbaikan proses, atau target yang tercapai.']
            );
        }

        return $items->merge($categorySpecific[$category][$type] ?? []);
    }

    protected function preparationChecklist(string $type, string $experienceLevel): array
    {
        $items = [
            'Pelajari profil perusahaan, produk, atau layanan utamanya.',
            'Siapkan ringkasan perkenalan diri 60-90 detik yang relevan dengan posisi.',
            'Review kembali CV, pengalaman, dan project yang ingin Anda ceritakan.',
            'Siapkan 2-3 contoh situasi kerja atau project dengan pola STAR.',
            'Pastikan koneksi, pakaian, dan dokumen pendukung siap bila interview online.',
        ];

        if ($experienceLevel === 'fresh-graduate') {
            $items[] = 'Siapkan contoh pengalaman organisasi, magang, volunteer, atau project kuliah yang paling relevan.';
        }

        if (in_array($type, ['technical-interview', 'campuran'], true)) {
            $items[] = 'Latih penjelasan teknis sederhana agar recruiter atau user mudah memahami cara kerja Anda.';
        }

        return $items;
    }

    protected function interviewTypeLabel(string $type): string
    {
        return match ($type) {
            'hr-interview' => 'HR Interview',
            'user-interview' => 'User Interview',
            'technical-interview' => 'Technical Interview',
            'final-interview' => 'Final Interview',
            default => 'Campuran',
        };
    }

    protected function experienceLevelLabel(string $level): string
    {
        return match ($level) {
            '0-1-tahun' => '0-1 tahun',
            '1-3-tahun' => '1-3 tahun',
            '3-5-tahun' => '3-5 tahun',
            '5-plus-tahun' => 'Lebih dari 5 tahun',
            default => 'Fresh Graduate',
        };
    }
}
