<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SopGeneratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_slug' => ['nullable', 'string', 'max:150'],
            'sop_name' => ['required', 'string', 'max:200'],
            'document_number' => ['required', 'string', 'max:100'],
            'document_version' => ['required', 'string', 'max:50'],
            'effective_date' => ['required', 'date'],
            'department' => ['required', 'string', 'max:150'],
            'prepared_by' => ['required', 'string', 'max:150'],
            'reviewed_by' => ['required', 'string', 'max:150'],
            'approved_by' => ['required', 'string', 'max:150'],
            'objective' => ['required', 'string', 'max:2000'],
            'scope' => ['required', 'string', 'max:2000'],
            'definitions' => ['nullable', 'string', 'max:2000'],
            'roles' => ['required', 'array', 'min:1', 'max:20'],
            'roles.*.role' => ['required', 'string', 'max:150'],
            'roles.*.responsibility' => ['required', 'string', 'max:500'],
            'steps' => ['required', 'array', 'min:1', 'max:30'],
            'steps.*.name' => ['required', 'string', 'max:150'],
            'steps.*.description' => ['required', 'string', 'max:1000'],
            'steps.*.pic' => ['nullable', 'string', 'max:150'],
            'steps.*.output' => ['nullable', 'string', 'max:150'],
            'related_documents' => ['nullable', 'string', 'max:2000'],
            'risk_notes' => ['nullable', 'string', 'max:2000'],
            'kpi' => ['nullable', 'string', 'max:2000'],
            'sop_type' => ['required', Rule::in(['administrasi', 'hr', 'finance', 'customer-service', 'operasional', 'it-helpdesk', 'gudang-inventory', 'custom'])],
        ];
    }
}
