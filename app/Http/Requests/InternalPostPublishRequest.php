<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InternalPostPublishRequest extends FormRequest
{
    public function authorize(): bool
    {
        $expectedToken = (string) config('services.internal_publisher.token');
        $providedToken = (string) $this->header('X-BK-Internal-Token');

        return filled($expectedToken) && hash_equals($expectedToken, $providedToken);
    }

    protected function prepareForValidation(): void
    {
        $faqs = collect($this->input('faqs', []))
            ->map(function (array $faq): array {
                return [
                    'question' => trim((string) ($faq['question'] ?? '')),
                    'answer' => trim((string) ($faq['answer'] ?? '')),
                    'is_active' => array_key_exists('is_active', $faq) ? (bool) $faq['is_active'] : true,
                ];
            })
            ->filter(fn (array $faq): bool => filled($faq['question']) || filled($faq['answer']))
            ->values()
            ->all();

        $this->merge([
            'faqs' => $faqs,
            'status' => $this->input('status', 'draft'),
        ]);
    }

    public function rules(): array
    {
        return [
            'category_slug' => ['required', 'string', 'max:150'],
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'faqs' => ['nullable', 'array', 'max:10'],
            'faqs.*.question' => ['required_with:faqs', 'string', 'max:255'],
            'faqs.*.answer' => ['required_with:faqs', 'string', 'max:1000'],
            'faqs.*.is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_slug.required' => 'Category slug wajib diisi.',
            'faqs.max' => 'FAQ dibatasi maksimal 10 item per artikel.',
        ];
    }
}
