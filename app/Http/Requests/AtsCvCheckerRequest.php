<?php

namespace App\Http\Requests;

use App\Services\PdfTextExtractorService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AtsCvCheckerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->hasFile('cv_pdf')) {
            return;
        }

        $file = $this->file('cv_pdf');

        if (! $file) {
            return;
        }

        $extractedText = app(PdfTextExtractorService::class)->extractFromUpload($file);

        if ($extractedText !== '' && blank($this->input('cv_text'))) {
            $this->merge([
                'cv_text' => $extractedText,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'template_slug' => ['nullable', 'string', 'max:150'],
            'target_position' => ['required', 'string', 'max:150'],
            'cv_text' => ['nullable', 'string', 'min:120', 'max:12000', 'required_without:cv_pdf'],
            'cv_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'main_skills' => ['nullable', 'string', 'max:2000'],
            'experience_level' => ['required', Rule::in(['fresh-graduate', '0-1-tahun', '1-3-tahun', '3-5-tahun', '5-plus-tahun'])],
            'target_industry' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->hasFile('cv_pdf')) {
                return;
            }

            if (filled($this->input('cv_text'))) {
                return;
            }

            $validator->errors()->add('cv_pdf', 'PDF belum bisa dibaca. Gunakan PDF berbasis teks atau paste isi CV secara manual.');
        });
    }
}
