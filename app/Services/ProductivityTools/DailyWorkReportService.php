<?php

namespace App\Services\ProductivityTools;

use App\Support\DocumentFormatter;
use Illuminate\Support\Collection;

class DailyWorkReportService
{
    public function generate(array $payload): array
    {
        $tasks = collect($payload['tasks'] ?? [])
            ->filter(fn (array $task): bool => filled($task['task_name'] ?? null))
            ->map(fn (array $task, int $index): array => [
                'number' => $index + 1,
                'task_name' => DocumentFormatter::sanitizeText($task['task_name'] ?? ''),
                'description' => DocumentFormatter::sanitizeText($task['description'] ?? ''),
                'status' => $task['status'] ?? 'selesai',
                'status_label' => $this->statusLabel($task['status'] ?? 'selesai'),
                'progress' => filled($task['progress']) ? (int) $task['progress'] : null,
                'output' => DocumentFormatter::sanitizeText($task['output'] ?? ''),
            ])
            ->values();

        $issues = collect($payload['issues'] ?? [])
            ->filter(fn (array $issue): bool => filled($issue['issue'] ?? null))
            ->map(fn (array $issue): array => [
                'issue' => DocumentFormatter::sanitizeText($issue['issue'] ?? ''),
                'impact' => DocumentFormatter::sanitizeText($issue['impact'] ?? ''),
                'temporary_action' => DocumentFormatter::sanitizeText($issue['temporary_action'] ?? ''),
            ])
            ->values();

        $plans = collect($payload['plans'] ?? [])
            ->filter(fn (array $plan): bool => filled($plan['plan_task'] ?? null))
            ->map(fn (array $plan, int $index): array => [
                'number' => $index + 1,
                'plan_task' => DocumentFormatter::sanitizeText($plan['plan_task'] ?? ''),
                'priority' => $plan['priority'] ?? 'sedang',
                'priority_label' => $this->priorityLabel($plan['priority'] ?? 'sedang'),
            ])
            ->values();

        $summary = [
            'total_tasks' => $tasks->count(),
            'completed_tasks' => $tasks->where('status', 'selesai')->count(),
            'in_progress_tasks' => $tasks->where('status', 'dalam-proses')->count(),
            'pending_tasks' => $tasks->where('status', 'tertunda')->count(),
            'average_progress' => $this->averageProgress($tasks),
        ];

        return [
            'report_title' => $payload['report_type'] ?? 'Laporan Harian Karyawan',
            'report_date_label' => DocumentFormatter::dateLabel($payload['report_date'] ?? null),
            'author_name' => DocumentFormatter::sanitizeText($payload['author_name'] ?? ''),
            'position' => DocumentFormatter::sanitizeText($payload['position'] ?? ''),
            'division' => DocumentFormatter::sanitizeText($payload['division'] ?? ''),
            'recipient_name' => DocumentFormatter::sanitizeText($payload['recipient_name'] ?? ''),
            'output_format' => $payload['output_format'] ?? 'ringkas',
            'style_label' => $this->styleLabel($payload['language_style'] ?? 'formal'),
            'tasks' => $tasks->all(),
            'issues' => $issues->all(),
            'plans' => $plans->all(),
            'summary' => $summary,
            'notes' => DocumentFormatter::sanitizeMultilineText($payload['additional_notes'] ?? ''),
            'issue_fallback' => 'Tidak ada kendala signifikan yang dilaporkan hari ini.',
            'plan_fallback' => 'Rencana kerja berikutnya akan disesuaikan dengan prioritas tim.',
            'whatsapp_text' => $this->whatsappText($payload, $tasks, $issues, $plans),
            'email_subject' => 'Laporan Kerja Harian - '.DocumentFormatter::sanitizeText($payload['author_name'] ?? '').' - '.DocumentFormatter::dateLabel($payload['report_date'] ?? null),
            'email_body' => $this->emailBody($payload, $tasks, $issues, $plans),
            'document_text' => $this->documentText($payload, $tasks, $issues, $plans, $summary),
            'formal_summary' => $this->formalSummary($payload, $summary),
            'disclaimer' => 'Laporan yang dihasilkan bersifat template praktis dan dapat disesuaikan kembali dengan format internal perusahaan masing-masing.',
        ];
    }

    protected function averageProgress(Collection $tasks): ?int
    {
        $progressValues = $tasks->pluck('progress')->filter(fn ($value) => $value !== null);

        if ($progressValues->isEmpty()) {
            return null;
        }

        return (int) round($progressValues->avg());
    }

    protected function whatsappText(array $payload, Collection $tasks, Collection $issues, Collection $plans): string
    {
        $taskLines = $tasks->map(fn (array $task): string => "{$task['number']}. {$task['task_name']} - {$task['status_label']}")->implode("\n");
        $issueLines = $issues->isNotEmpty()
            ? $issues->map(fn (array $issue): string => '- '.$issue['issue'])->implode("\n")
            : 'Tidak ada kendala signifikan yang dilaporkan hari ini.';
        $planLines = $plans->isNotEmpty()
            ? $plans->map(fn (array $plan): string => "{$plan['number']}. {$plan['plan_task']}")->implode("\n")
            : 'Rencana kerja berikutnya akan disesuaikan dengan prioritas tim.';

        return trim(implode("\n\n", [
            ($payload['report_type'] ?? 'Laporan Kerja Harian').' - '.DocumentFormatter::dateLabel($payload['report_date'] ?? null),
            'Nama: '.DocumentFormatter::sanitizeText($payload['author_name'] ?? '')."\nPosisi: ".DocumentFormatter::sanitizeText($payload['position'] ?? ''),
            "Pekerjaan hari ini:\n".$taskLines,
            "Kendala:\n".$issueLines,
            "Rencana berikutnya:\n".$planLines,
        ]));
    }

    protected function emailBody(array $payload, Collection $tasks, Collection $issues, Collection $plans): string
    {
        $recipient = filled($payload['recipient_name'] ?? null)
            ? 'Yth. '.DocumentFormatter::sanitizeText($payload['recipient_name'])
            : 'Yth. Bapak/Ibu';

        return implode("\n\n", [
            $recipient.',',
            'Berikut saya sampaikan laporan kerja harian untuk tanggal '.DocumentFormatter::dateLabel($payload['report_date'] ?? null).'.',
            "Pekerjaan hari ini:\n".$tasks->map(fn (array $task): string => "{$task['number']}. {$task['task_name']} ({$task['status_label']})")->implode("\n"),
            "Kendala:\n".($issues->isNotEmpty() ? $issues->map(fn (array $issue): string => '- '.$issue['issue'])->implode("\n") : 'Tidak ada kendala signifikan yang dilaporkan hari ini.'),
            "Rencana kerja berikutnya:\n".($plans->isNotEmpty() ? $plans->map(fn (array $plan): string => "{$plan['number']}. {$plan['plan_task']}")->implode("\n") : 'Rencana kerja berikutnya akan disesuaikan dengan prioritas tim.'),
            'Demikian laporan ini saya sampaikan. Terima kasih.',
            "Hormat saya,\n".DocumentFormatter::sanitizeText($payload['author_name'] ?? ''),
        ]);
    }

    protected function formalSummary(array $payload, array $summary): array
    {
        return [
            'title' => $payload['report_type'] ?? 'Laporan Harian Karyawan',
            'intro' => 'Laporan ini merangkum pekerjaan yang dikerjakan pada '.DocumentFormatter::dateLabel($payload['report_date'] ?? null).' oleh '.DocumentFormatter::sanitizeText($payload['author_name'] ?? '').'.',
            'task_summary' => [
                'Total tugas: '.$summary['total_tasks'],
                'Selesai: '.$summary['completed_tasks'],
                'Dalam proses: '.$summary['in_progress_tasks'],
                'Tertunda: '.$summary['pending_tasks'],
                $summary['average_progress'] !== null ? 'Rata-rata progress: '.$summary['average_progress'].'%' : null,
            ],
        ];
    }

    protected function documentText(array $payload, Collection $tasks, Collection $issues, Collection $plans, array $summary): string
    {
        $taskLines = $tasks->map(fn (array $task): string => "{$task['number']}. {$task['task_name']} - {$task['status_label']}")->implode("\n");
        $issueLines = $issues->isNotEmpty()
            ? $issues->map(fn (array $issue): string => '- '.$issue['issue'])->implode("\n")
            : 'Tidak ada kendala signifikan yang dilaporkan hari ini.';
        $planLines = $plans->isNotEmpty()
            ? $plans->map(fn (array $plan): string => "{$plan['number']}. {$plan['plan_task']} ({$plan['priority_label']})")->implode("\n")
            : 'Rencana kerja berikutnya akan disesuaikan dengan prioritas tim.';

        return implode("\n\n", [
            $payload['report_type'] ?? 'Laporan Harian Karyawan',
            'Nama: '.DocumentFormatter::sanitizeText($payload['author_name'] ?? '')."\nPosisi: ".DocumentFormatter::sanitizeText($payload['position'] ?? '')."\nDivisi: ".DocumentFormatter::sanitizeText($payload['division'] ?? ''),
            "Ringkasan tugas:\nTotal tugas: {$summary['total_tasks']}\nSelesai: {$summary['completed_tasks']}\nDalam proses: {$summary['in_progress_tasks']}\nTertunda: {$summary['pending_tasks']}",
            "Pekerjaan hari ini:\n".$taskLines,
            "Kendala:\n".$issueLines,
            "Rencana kerja berikutnya:\n".$planLines,
        ]);
    }

    protected function statusLabel(string $status): string
    {
        return match ($status) {
            'dalam-proses' => 'Dalam proses',
            'tertunda' => 'Tertunda',
            default => 'Selesai',
        };
    }

    protected function priorityLabel(string $priority): string
    {
        return match ($priority) {
            'tinggi' => 'Tinggi',
            'rendah' => 'Rendah',
            default => 'Sedang',
        };
    }

    protected function styleLabel(string $style): string
    {
        return match ($style) {
            'natural-profesional' => 'Natural profesional',
            'singkat' => 'Singkat',
            default => 'Formal',
        };
    }
}
