<?php

namespace App\Services;

use App\Models\GeneratorTemplate;
use App\Models\Tool;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DocumentGeneratorService
{
    public function __construct(
        protected GeneratorTemplateService $templateService,
        protected InvoiceCalculationService $invoiceCalculationService,
        protected TemplateRenderService $templateRenderService,
    ) {}

    public function generatorTypeForTool(Tool|string $tool): ?string
    {
        $slug = $tool instanceof Tool ? $tool->slug : $tool;

        return match ($slug) {
            'generator-invoice' => 'invoice',
            'generator-surat-izin', 'generator-surat-kuasa' => 'letter',
            'generator-kwitansi' => 'receipt',
            'generator-berita-acara' => 'minutes',
            default => null,
        };
    }

    public function preview(string $generatorType, array $payload, ?string $templateSlug): array
    {
        $template = $this->templateService->findTemplate($generatorType, $templateSlug);
        $document = $this->normalizePayload($generatorType, $payload);

        return [
            'generator_type' => $generatorType,
            'template' => $template,
            'template_slug' => $template->slug,
            'payload' => $document,
            'html' => $this->renderPreviewHtml($generatorType, $template, $document),
            'copy_text' => $this->copyText($generatorType, $document),
            'file_name' => $this->buildFilename($generatorType, $document),
        ];
    }

    public function downloadPdf(string $generatorType, array $payload, ?string $templateSlug)
    {
        $template = $this->templateService->findTemplate($generatorType, $templateSlug);
        $document = $this->normalizePayload($generatorType, $payload);

        return Pdf::loadView('generators.pdf', [
            'template' => $template,
            'templateView' => $this->templateService->resolveViewPath($template),
            'document' => $document,
        ])
            ->setPaper($template->paper_size ?: 'a4', $template->orientation ?: 'portrait')
            ->download($this->buildFilename($generatorType, $document));
    }

    public function printView(string $generatorType, array $payload, ?string $templateSlug): Response
    {
        $template = $this->templateService->findTemplate($generatorType, $templateSlug);
        $document = $this->normalizePayload($generatorType, $payload);

        return response()->view('generators.print', [
            'template' => $template,
            'templateView' => $this->templateService->resolveViewPath($template),
            'document' => $document,
            'title' => $this->documentTitle($generatorType, $document),
        ]);
    }

    public function copyText(string $generatorType, array $payload): ?string
    {
        return match ($generatorType) {
            'letter' => implode("\n", [
                strtoupper((string) ($payload['subject'] ?? 'SURAT')),
                '',
                filled($payload['city']) ? $payload['city'].', '.($payload['date_label'] ?? $payload['date']) : ($payload['date_label'] ?? $payload['date']),
                '',
                filled($payload['recipient']) ? 'Kepada Yth. '.$payload['recipient'] : '',
                filled($payload['recipient']) ? '' : null,
                'Dengan hormat,',
                '',
                trim((string) ($payload['body_text'] ?? '')),
                '',
                'Hormat saya,',
                '',
                (string) ($payload['name'] ?? ''),
                filled($payload['position']) ? (string) $payload['position'] : '',
                filled($payload['company']) ? (string) $payload['company'] : '',
            ]),
            default => null,
        };
    }

    protected function renderPreviewHtml(string $generatorType, GeneratorTemplate $template, array $document): string
    {
        return view($this->previewView($generatorType), [
            'template' => $template,
            'templateView' => $this->templateService->resolveViewPath($template),
            'document' => $document,
        ])->render();
    }

    protected function previewView(string $generatorType): string
    {
        return match ($generatorType) {
            'invoice' => 'generators.invoice.preview',
            'letter' => 'generators.letter.preview',
            'receipt' => 'generators.receipt.preview',
            'minutes' => 'generators.minutes.preview',
            default => 'generators.letter.preview',
        };
    }

    protected function normalizePayload(string $generatorType, array $payload): array
    {
        return match ($generatorType) {
            'invoice' => $this->normalizeInvoicePayload($payload),
            'letter' => $this->normalizeLetterPayload($payload),
            default => $payload,
        };
    }

    protected function normalizeInvoicePayload(array $payload): array
    {
        $summary = $this->invoiceCalculationService->calculate(
            $payload['items'] ?? [],
            [
                'tax' => (float) ($payload['tax'] ?? 0),
                'discount' => (float) ($payload['discount'] ?? 0),
            ],
        );

        $businessLogoPath = $payload['business_logo_path'] ?? null;

        return array_merge($payload, $summary, [
            'business_logo_path' => $businessLogoPath,
            'business_logo_url' => $this->templateRenderService->publicUrl($businessLogoPath),
            'invoice_date_label' => filled($payload['invoice_date'] ?? null)
                ? Carbon::parse($payload['invoice_date'])->translatedFormat('d F Y')
                : null,
            'due_date_label' => filled($payload['due_date'] ?? null)
                ? Carbon::parse($payload['due_date'])->translatedFormat('d F Y')
                : null,
            'business_contact_line' => collect([
                $payload['business_phone'] ?? null,
                $payload['business_email'] ?? null,
            ])->filter()->implode(' | '),
            'customer_contact_line' => collect([
                $payload['customer_phone'] ?? null,
                $payload['customer_email'] ?? null,
            ])->filter()->implode(' | '),
        ]);
    }

    protected function normalizeLetterPayload(array $payload): array
    {
        $date = $payload['date'] ?? null;
        $dateLabel = $date ? Carbon::parse($date)->translatedFormat('d F Y') : null;
        $subject = 'Surat Izin Kerja';
        $position = trim((string) ($payload['position'] ?? ''));
        $company = trim((string) ($payload['company'] ?? ''));

        $bodyText = collect([
            "Saya yang bertanda tangan di bawah ini, {$payload['name']}.",
            filled($position) ? "Saat ini bekerja sebagai {$position}." : null,
            filled($company) ? "Perusahaan / instansi: {$company}." : null,
            "Dengan ini mengajukan izin pada tanggal {$dateLabel} karena {$payload['reason']}.",
            'Demikian surat ini saya sampaikan. Atas perhatian dan izinnya, saya ucapkan terima kasih.',
        ])->filter()->implode(' ');

        return array_merge($payload, [
            'subject' => $subject,
            'date_label' => $dateLabel,
            'body_text' => $bodyText,
        ]);
    }

    protected function buildFilename(string $generatorType, array $document): string
    {
        return match ($generatorType) {
            'invoice' => 'invoice-'.Str::slug((string) ($document['invoice_number'] ?? 'dokumen')).'-bantukerja.pdf',
            'letter' => 'surat-izin-bantukerja.pdf',
            'receipt' => 'kwitansi-bantukerja.pdf',
            'minutes' => 'berita-acara-bantukerja.pdf',
            default => 'dokumen-bantukerja.pdf',
        };
    }

    protected function documentTitle(string $generatorType, array $document): string
    {
        return match ($generatorType) {
            'invoice' => 'Print Invoice '.$document['invoice_number'],
            'letter' => (string) ($document['subject'] ?? 'Print Surat'),
            'receipt' => 'Print Kwitansi',
            'minutes' => 'Print Berita Acara',
            default => 'Print Dokumen',
        };
    }
}
