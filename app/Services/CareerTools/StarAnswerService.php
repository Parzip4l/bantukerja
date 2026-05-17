<?php

namespace App\Services\CareerTools;

use App\Support\DocumentFormatter;

class StarAnswerService
{
    public function generate(array $payload): array
    {
        $situation = DocumentFormatter::sanitizeText($payload['situation'] ?? '');
        $task = DocumentFormatter::sanitizeText($payload['task'] ?? '');
        $action = DocumentFormatter::sanitizeText($payload['action'] ?? '');
        $result = DocumentFormatter::sanitizeText($payload['result'] ?? '');
        $skills = DocumentFormatter::commaOrLineList($payload['highlight_skills'] ?? '');
        $experienceTypeLabel = $this->experienceTypeLabel($payload['experience_type'] ?? 'pengalaman-kerja');
        $level = $payload['experience_level'] ?? 'fresh-graduate';

        $safeResult = $result !== ''
            ? $result
            : 'Dari pengalaman tersebut, saya belajar untuk bekerja lebih terstruktur dan meningkatkan kemampuan problem solving.';

        $starSections = [
            'Situation' => $situation,
            'Task' => $task,
            'Action' => $action,
            'Result' => $safeResult,
        ];

        $paragraph = $this->buildParagraph($payload, $starSections, $skills, $level, $experienceTypeLabel);
        $suggestions = $this->suggestions($starSections);

        return [
            'position_applied' => DocumentFormatter::sanitizeText($payload['position_applied'] ?? ''),
            'question' => DocumentFormatter::sanitizeText($payload['interview_question'] ?? ''),
            'experience_level_label' => $level === 'fresh-graduate' ? 'Fresh Graduate' : 'Berpengalaman',
            'experience_type_label' => $experienceTypeLabel,
            'style_label' => $this->styleLabel($payload['answer_style'] ?? 'natural-profesional'),
            'output_format' => $payload['output_format'] ?? 'keduanya',
            'star_sections' => $starSections,
            'paragraph_answer' => $paragraph,
            'delivery_tips' => [
                'Mulailah dengan konteks singkat, lalu masuk ke tindakan yang benar-benar Anda lakukan.',
                'Gunakan kata kerja aktif agar jawaban terdengar tegas dan tidak berputar-putar.',
                'Jika ada hasil yang terukur, sampaikan dengan sederhana tanpa berlebihan.',
                'Tutup jawaban dengan pelajaran atau skill yang relevan untuk posisi yang dilamar.',
            ],
            'improvement_suggestions' => $suggestions,
        ];
    }

    protected function buildParagraph(array $payload, array $starSections, array $skills, string $level, string $experienceTypeLabel): string
    {
        $intro = $level === 'fresh-graduate'
            ? "Untuk menjawab pertanyaan tersebut, saya biasanya mengambil contoh dari {$experienceTypeLabel} yang paling relevan."
            : "Salah satu contoh yang paling relevan dari pengalaman saya adalah saat {$starSections['Situation']}.";

        $middle = $level === 'fresh-graduate'
            ? "Saat itu saya memiliki tanggung jawab {$starSections['Task']}. Untuk menanganinya, saya {$starSections['Action']}."
            : "Dalam situasi tersebut, tanggung jawab saya adalah {$starSections['Task']}. Saya kemudian {$starSections['Action']}.";

        $skillsSentence = $skills !== []
            ? 'Pengalaman itu juga menonjolkan '.implode(', ', array_slice($skills, 0, 4)).' yang saya miliki.'
            : null;

        $ending = "Hasilnya, {$starSections['Result']}";

        if (($payload['answer_style'] ?? 'natural-profesional') === 'singkat') {
            return trim($intro.' '.$middle.' '.$ending);
        }

        return collect([$intro, $middle, $ending, $skillsSentence])->filter()->implode(' ');
    }

    protected function suggestions(array $starSections): array
    {
        $suggestions = [];

        if (mb_strlen($starSections['Situation']) < 20) {
            $suggestions[] = 'Perjelas konteks situasi agar interviewer mudah memahami latar belakang ceritanya.';
        }

        if (mb_strlen($starSections['Action']) < 20) {
            $suggestions[] = 'Tambahkan detail tindakan yang benar-benar Anda lakukan, bukan hanya hasil akhirnya.';
        }

        if (! preg_match('/\d|%|persen|hari|minggu|bulan/i', $starSections['Result'])) {
            $suggestions[] = 'Jika memungkinkan, tambahkan hasil yang lebih konkret seperti waktu, angka, atau perubahan yang terlihat.';
        }

        if ($suggestions === []) {
            $suggestions[] = 'Struktur STAR Anda sudah cukup rapi. Tinggal pastikan penyampaiannya tetap natural saat interview.';
        }

        return $suggestions;
    }

    protected function experienceTypeLabel(string $type): string
    {
        return match ($type) {
            'magang' => 'pengalaman magang',
            'organisasi' => 'pengalaman organisasi',
            'project-kuliah' => 'project kuliah',
            'freelance' => 'pengalaman freelance',
            'volunteer' => 'kegiatan volunteer',
            default => 'pengalaman kerja',
        };
    }

    protected function styleLabel(string $style): string
    {
        return match ($style) {
            'formal' => 'Formal',
            'singkat' => 'Singkat',
            'detail' => 'Detail',
            default => 'Natural profesional',
        };
    }
}
