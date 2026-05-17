<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DailyWorkReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_slug' => ['nullable', 'string', 'max:150'],
            'author_name' => ['required', 'string', 'max:150'],
            'position' => ['required', 'string', 'max:150'],
            'division' => ['required', 'string', 'max:150'],
            'recipient_name' => ['nullable', 'string', 'max:150'],
            'report_date' => ['required', 'date'],
            'report_type' => ['required', Rule::in(['Laporan Harian Karyawan', 'Laporan Harian Magang', 'Laporan Harian Freelancer', 'Laporan Harian Tim Operasional', 'Laporan Harian IT/Helpdesk', 'Laporan Harian Sales', 'Laporan Harian Customer Service'])],
            'output_format' => ['required', Rule::in(['ringkas', 'detail', 'whatsapp-chat', 'email', 'dokumen-formal'])],
            'tasks' => ['required', 'array', 'min:1', 'max:25'],
            'tasks.*.task_name' => ['required', 'string', 'max:200'],
            'tasks.*.description' => ['required', 'string', 'max:1000'],
            'tasks.*.status' => ['required', Rule::in(['selesai', 'dalam-proses', 'tertunda'])],
            'tasks.*.progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'tasks.*.output' => ['nullable', 'string', 'max:500'],
            'issues' => ['nullable', 'array', 'max:15'],
            'issues.*.issue' => ['nullable', 'string', 'max:500'],
            'issues.*.impact' => ['nullable', 'string', 'max:500'],
            'issues.*.temporary_action' => ['nullable', 'string', 'max:500'],
            'plans' => ['nullable', 'array', 'max:15'],
            'plans.*.plan_task' => ['nullable', 'string', 'max:500'],
            'plans.*.priority' => ['nullable', Rule::in(['tinggi', 'sedang', 'rendah'])],
            'additional_notes' => ['nullable', 'string', 'max:2000'],
            'language_style' => ['required', Rule::in(['formal', 'natural-profesional', 'singkat'])],
        ];
    }
}
