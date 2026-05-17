<?php

namespace App\Services\CareerTools;

use App\Support\CareerKeywordDictionary;
use App\Support\DocumentFormatter;

class JobDescriptionMatcherService
{
    public function analyze(array $payload): array
    {
        $position = DocumentFormatter::sanitizeText($payload['target_position'] ?? '');
        $category = CareerKeywordDictionary::guessCategory($position);
        $jobDescription = DocumentFormatter::sanitizeMultilineText($payload['job_description'] ?? '');
        $profile = DocumentFormatter::sanitizeMultilineText($payload['profile_summary'] ?? '');
        $skills = DocumentFormatter::commaOrLineList($payload['owned_skills'] ?? '');
        $experience = DocumentFormatter::sanitizeMultilineText($payload['experience_summary'] ?? '');
        $education = DocumentFormatter::sanitizeText($payload['education_level'] ?? '');

        $jdKeywords = CareerKeywordDictionary::extractKeywords($jobDescription, $category);
        $profileKeywords = array_unique(array_merge(
            CareerKeywordDictionary::extractKeywords($profile.' '.$experience.' '.implode(' ', $skills), $category),
            array_map('strtolower', $skills)
        ));

        $matchedKeywords = CareerKeywordDictionary::intersect($jdKeywords, $profileKeywords);
        $missingKeywords = collect($jdKeywords)->diff($matchedKeywords)->take(12)->values()->all();

        $skillScore = min(40, (int) round((count($matchedKeywords) / max(count($jdKeywords), 1)) * 40));
        $experienceScore = $this->experienceScore($experience, $matchedKeywords);
        $educationScore = $this->educationScore($education, $jobDescription);
        $toolsScore = $this->toolsScore($jobDescription, $profileKeywords);
        $completenessScore = $this->completenessScore($profile, $skills, $experience, $education);
        $score = min(100, $skillScore + $experienceScore + $educationScore + $toolsScore + $completenessScore);

        return [
            'target_position' => $position,
            'category_label' => CareerKeywordDictionary::labelMap()[$category] ?? 'Lainnya',
            'score' => $score,
            'status_label' => $this->statusLabel($score),
            'matched_keywords' => $matchedKeywords,
            'missing_keywords' => $missingKeywords,
            'skill_gap' => $missingKeywords,
            'breakdown' => [
                ['label' => 'Keyword skill cocok', 'score' => $skillScore, 'max' => 40],
                ['label' => 'Pengalaman relevan', 'score' => $experienceScore, 'max' => 25],
                ['label' => 'Pendidikan sesuai', 'score' => $educationScore, 'max' => 10],
                ['label' => 'Tools / software cocok', 'score' => $toolsScore, 'max' => 15],
                ['label' => 'Kelengkapan profil', 'score' => $completenessScore, 'max' => 10],
            ],
            'optimization_suggestions' => $this->suggestions($missingKeywords, $skills, $education),
            'profile_summary_template' => $this->profileSummaryTemplate($position, $matchedKeywords),
            'disclaimer' => 'Hasil ini adalah estimasi berdasarkan kecocokan keyword dan kelengkapan profil, bukan jaminan diterima kerja.',
        ];
    }

    protected function experienceScore(string $experience, array $matchedKeywords): int
    {
        if ($experience === '') {
            return 0;
        }

        $score = 10;

        if (count($matchedKeywords) >= 3) {
            $score += 10;
        }

        if (preg_match('/\d|%|tahun|bulan|target|project|proyek/i', $experience)) {
            $score += 5;
        }

        return min(25, $score);
    }

    protected function educationScore(string $education, string $jobDescription): int
    {
        if ($education === '') {
            return 0;
        }

        if (preg_match('/s1|d3|sarjana|universitas|akuntansi|manajemen|teknik|informatika/i', $education.$jobDescription)) {
            return 10;
        }

        return 6;
    }

    protected function toolsScore(string $jobDescription, array $profileKeywords): int
    {
        $toolKeywords = CareerKeywordDictionary::extractKeywords($jobDescription);
        $matches = CareerKeywordDictionary::intersect($toolKeywords, $profileKeywords);

        return min(15, count($matches) * 5);
    }

    protected function completenessScore(string $profile, array $skills, string $experience, string $education): int
    {
        $score = 0;

        if ($profile !== '') {
            $score += 3;
        }

        if ($skills !== []) {
            $score += 3;
        }

        if ($experience !== '') {
            $score += 2;
        }

        if ($education !== '') {
            $score += 2;
        }

        return $score;
    }

    protected function suggestions(array $missingKeywords, array $skills, string $education): array
    {
        $suggestions = [];

        if ($missingKeywords !== []) {
            $suggestions[] = 'Tambahkan keyword yang benar-benar Anda kuasai seperti '.implode(', ', array_slice($missingKeywords, 0, 5)).' ke bagian profil, pengalaman, atau skill.';
        }

        if ($skills === []) {
            $suggestions[] = 'Isi bagian skill utama agar kecocokan profil lebih mudah terlihat recruiter.';
        }

        if ($education === '') {
            $suggestions[] = 'Lengkapi pendidikan terakhir agar profil terasa lebih utuh saat dibandingkan dengan lowongan.';
        }

        if ($suggestions === []) {
            $suggestions[] = 'Profil Anda sudah cukup relevan. Fokus berikutnya adalah memperjelas pencapaian dan menyesuaikan CV dengan bahasa lowongan.';
        }

        return $suggestions;
    }

    protected function profileSummaryTemplate(string $position, array $matchedKeywords): string
    {
        $keywords = implode(', ', array_slice($matchedKeywords, 0, 4));

        return "Profesional yang menargetkan posisi {$position} dengan kekuatan pada {$keywords}. Siap berkontribusi melalui pengalaman relevan, kemampuan belajar cepat, dan pendekatan kerja yang rapi.";
    }

    protected function statusLabel(int $score): string
    {
        return match (true) {
            $score >= 80 => 'Sangat cocok',
            $score >= 60 => 'Cukup cocok',
            $score >= 40 => 'Perlu penyesuaian',
            default => 'Kurang cocok',
        };
    }
}
