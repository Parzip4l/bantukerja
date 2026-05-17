<?php

namespace App\Services\CareerTools;

use App\Support\CareerKeywordDictionary;
use App\Support\DocumentFormatter;

class AtsCvCheckerService
{
    public function analyze(array $payload): array
    {
        $position = DocumentFormatter::sanitizeText($payload['target_position'] ?? '');
        $category = CareerKeywordDictionary::guessCategory($position);
        $cvText = DocumentFormatter::sanitizeMultilineText($payload['cv_text'] ?? '');
        $skills = DocumentFormatter::commaOrLineList($payload['main_skills'] ?? '');
        $wordCount = str_word_count(strip_tags($cvText));

        $sections = [
            'Kontak lengkap' => $this->contactScore($cvText),
            'Ringkasan profil' => $this->summaryScore($cvText),
            'Pengalaman kerja / project' => $this->experienceScore($cvText),
            'Pendidikan' => $this->educationScore($cvText),
            'Skill' => $this->skillsScore($cvText, $skills),
            'Keyword posisi target' => $this->targetKeywordScore($cvText, $position, $category),
            'Pencapaian terukur' => $this->achievementScore($cvText),
            'Panjang CV ideal' => $this->lengthScore($wordCount),
        ];

        $score = array_sum(array_column($sections, 'score'));

        $goodPoints = [];
        $improvements = [];

        foreach ($sections as $label => $item) {
            if ($item['score'] >= ($item['max'] * 0.7)) {
                $goodPoints[] = $label.' sudah cukup baik.';
            } else {
                $improvements[] = $item['note'];
            }
        }

        return [
            'target_position' => $position,
            'score' => $score,
            'label' => $this->scoreLabel($score),
            'breakdown' => collect($sections)->map(fn (array $item, string $label) => [
                'label' => $label,
                'score' => $item['score'],
                'max' => $item['max'],
                'note' => $item['note'],
            ])->values()->all(),
            'good_points' => $goodPoints,
            'improvements' => $improvements,
            'recommended_keywords' => CareerKeywordDictionary::recommendKeywords($position),
            'ats_checklist' => [
                'Gunakan format sederhana',
                'Hindari tabel berlebihan',
                'Gunakan heading jelas',
                'Masukkan keyword relevan',
                'Tambahkan pencapaian terukur',
            ],
            'word_count' => $wordCount,
            'disclaimer' => 'Skor ini merupakan estimasi berdasarkan struktur CV dan keyword umum. Setiap perusahaan dapat menggunakan sistem ATS dan kriteria seleksi yang berbeda.',
        ];
    }

    protected function contactScore(string $text): array
    {
        $score = 0;

        if (preg_match('/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i', $text)) {
            $score += 8;
        }

        if (preg_match('/(\+62|08)\d{8,13}/', preg_replace('/\s+/', '', $text))) {
            $score += 5;
        }

        if (preg_match('/linkedin|portfolio|github/i', $text)) {
            $score += 2;
        }

        return ['score' => $score, 'max' => 15, 'note' => 'Lengkapi email aktif, nomor HP, dan tautan LinkedIn atau portofolio jika relevan.'];
    }

    protected function summaryScore(string $text): array
    {
        $hasSummary = preg_match('/profil|summary|ringkasan|tentang saya/i', $text) || mb_strlen($text) > 250;

        return ['score' => $hasSummary ? 10 : 3, 'max' => 10, 'note' => 'Tambahkan ringkasan profil singkat di bagian awal CV.'];
    }

    protected function experienceScore(string $text): array
    {
        $hasExperience = preg_match('/pengalaman|experience|project|proyek|magang|organisasi/i', $text);

        return ['score' => $hasExperience ? 15 : 4, 'max' => 15, 'note' => 'Cantumkan pengalaman kerja, project, magang, atau organisasi yang relevan.'];
    }

    protected function educationScore(string $text): array
    {
        $hasEducation = preg_match('/pendidikan|universitas|sekolah|jurusan|degree|sarjana|diploma|s1|d3/i', $text);

        return ['score' => $hasEducation ? 10 : 3, 'max' => 10, 'note' => 'Tambahkan bagian pendidikan agar struktur CV lebih lengkap.'];
    }

    protected function skillsScore(string $text, array $skills): array
    {
        $score = 0;

        if (preg_match('/skill|kemampuan|tools|software|technical skills/i', $text)) {
            $score += 8;
        }

        if (count($skills) >= 3) {
            $score += 7;
        }

        return ['score' => min(15, $score), 'max' => 15, 'note' => 'Tambahkan bagian skill yang spesifik dan relevan dengan posisi target.'];
    }

    protected function targetKeywordScore(string $text, string $position, string $category): array
    {
        $matched = CareerKeywordDictionary::intersect(
            CareerKeywordDictionary::recommendKeywords($position),
            CareerKeywordDictionary::extractKeywords($text, $category)
        );

        return [
            'score' => min(15, count($matched) * 3),
            'max' => 15,
            'note' => 'Masukkan keyword yang relevan dengan posisi target agar CV lebih mudah terbaca secara ATS.',
        ];
    }

    protected function achievementScore(string $text): array
    {
        $hasAchievement = preg_match('/\d|%|rp|meningkat|menurunkan|mempercepat|target/i', $text);

        return ['score' => $hasAchievement ? 10 : 2, 'max' => 10, 'note' => 'Tambahkan pencapaian terukur seperti angka, persentase, atau dampak kerja.'];
    }

    protected function lengthScore(int $wordCount): array
    {
        $score = match (true) {
            $wordCount >= 180 && $wordCount <= 650 => 10,
            $wordCount >= 120 && $wordCount < 180, $wordCount > 650 && $wordCount <= 800 => 6,
            default => 3,
        };

        return ['score' => $score, 'max' => 10, 'note' => 'Usahakan isi CV tidak terlalu pendek dan tidak terlalu panjang agar tetap nyaman dipindai recruiter.'];
    }

    protected function scoreLabel(int $score): string
    {
        return match (true) {
            $score >= 85 => 'Sangat baik',
            $score >= 70 => 'Cukup baik',
            $score >= 50 => 'Perlu diperbaiki',
            default => 'Masih kurang lengkap',
        };
    }
}
