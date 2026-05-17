<?php

namespace App\Services\CareerTools;

use App\Support\DocumentFormatter;

class LinkedInProfileService
{
    public function generate(array $payload): array
    {
        $position = DocumentFormatter::sanitizeText($payload['target_position'] ?? '');
        $level = $payload['career_level'] ?? 'fresh-graduate';
        $industry = DocumentFormatter::sanitizeText($payload['industry'] ?? '');
        $skills = array_values(array_filter([
            DocumentFormatter::sanitizeText($payload['primary_skill_1'] ?? ''),
            DocumentFormatter::sanitizeText($payload['primary_skill_2'] ?? ''),
            DocumentFormatter::sanitizeText($payload['primary_skill_3'] ?? ''),
        ]));
        $mainActivity = DocumentFormatter::sanitizeText($payload['main_experience'] ?? '');
        $careerTarget = DocumentFormatter::sanitizeText($payload['career_target'] ?? '');
        $language = $payload['language'] ?? 'id';

        return [
            'headlines' => $this->headlineOptions($position, $level, $industry, $skills, $careerTarget, $language),
            'about_short' => $this->aboutShort($position, $level, $industry, $skills, $mainActivity, $careerTarget, $language),
            'about_professional' => $this->aboutProfessional($position, $level, $industry, $skills, $mainActivity, $careerTarget, $language),
            'tips' => [
                'Gunakan headline yang jelas dan tidak terlalu penuh kata kunci.',
                'Pastikan bagian About tetap relevan dengan posisi yang Anda incar saat ini.',
                'Cantumkan skill dan pengalaman yang konsisten dengan CV serta portofolio.',
                'Perbarui pengalaman, sertifikasi, dan skill agar profil lebih mudah ditemukan recruiter.',
            ],
            'checklist' => [
                'Foto profil',
                'Headline',
                'About',
                'Pengalaman',
                'Skill',
                'Sertifikasi',
            ],
            'language_label' => $language === 'en' ? 'English basic' : 'Indonesia',
        ];
    }

    protected function headlineOptions(string $position, string $level, string $industry, array $skills, string $careerTarget, string $language): array
    {
        $skillOne = $skills[0] ?? 'Problem Solving';
        $skillTwo = $skills[1] ?? 'Communication';
        $skillThree = $skills[2] ?? 'Collaboration';

        if ($language === 'en') {
            return [
                "{$position} | {$skillOne} | {$skillTwo} | {$industry}",
                ucfirst(str_replace('-', ' ', $level))." {$position} focused on {$skillOne}, {$skillTwo}, and {$skillThree}",
                "{$position} | Helping teams grow through {$skillOne}".($careerTarget !== '' ? " for {$careerTarget}" : ''),
            ];
        }

        return [
            "{$position} | {$skillOne} | {$skillTwo} | {$industry}",
            $this->levelLabel($level)." {$position} dengan fokus pada {$skillOne}, {$skillTwo}, dan {$skillThree}",
            "{$position} | Membantu kebutuhan {$industry} melalui {$skillOne}".($careerTarget !== '' ? " dan arah karier ke {$careerTarget}" : ''),
        ];
    }

    protected function aboutShort(string $position, string $level, string $industry, array $skills, string $mainActivity, string $careerTarget, string $language): string
    {
        $skillsLine = implode(', ', array_slice($skills, 0, 3));

        if ($language === 'en') {
            return "I am building my career as a {$position} in the {$industry} space, with a focus on {$skillsLine}. {$mainActivity} I am currently aiming to grow toward {$careerTarget}.";
        }

        if ($level === 'fresh-graduate') {
            return "Saya sedang membangun karier sebagai {$position} di bidang {$industry} dengan fokus pada {$skillsLine}. {$mainActivity} Saat ini saya ingin berkembang ke arah {$careerTarget}.";
        }

        return "Saya bekerja dan berkembang sebagai {$position} di bidang {$industry}, dengan fokus pada {$skillsLine}. {$mainActivity} Ke depan, saya ingin terus bertumbuh menuju {$careerTarget}.";
    }

    protected function aboutProfessional(string $position, string $level, string $industry, array $skills, string $mainActivity, string $careerTarget, string $language): string
    {
        $skillsLine = implode(', ', array_slice($skills, 0, 3));

        if ($language === 'en') {
            return collect([
                "I am focused on growing as a {$position} in the {$industry} field.",
                $mainActivity !== '' ? $mainActivity : "My core strengths include {$skillsLine}.",
                $careerTarget !== '' ? "I am looking for opportunities that allow me to contribute while moving toward {$careerTarget}." : null,
            ])->filter()->implode(' ');
        }

        $first = $level === 'fresh-graduate'
            ? "Saya tertarik membangun karier sebagai {$position} di bidang {$industry}."
            : "Saya berfokus mengembangkan karier sebagai {$position} di bidang {$industry}.";

        return collect([
            $first,
            $mainActivity !== '' ? $mainActivity : "Keahlian utama saya mencakup {$skillsLine}.",
            $careerTarget !== '' ? "Saya terbuka pada peluang yang memungkinkan saya berkontribusi sekaligus berkembang ke arah {$careerTarget}." : null,
        ])->filter()->implode(' ');
    }

    protected function levelLabel(string $level): string
    {
        return match ($level) {
            'junior' => 'Junior',
            'mid-level' => 'Mid-level',
            'senior' => 'Senior',
            'manager' => 'Manager',
            default => 'Fresh Graduate',
        };
    }
}
