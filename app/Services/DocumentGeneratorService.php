<?php

namespace App\Services;

use App\Models\GeneratorTemplate;
use App\Models\GeneratorDownloadLog;
use App\Models\Tool;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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
        $documentState = $this->compose($generatorType, $payload, $templateSlug);

        return [
            'generator_type' => $generatorType,
            'template' => $documentState['template'],
            'template_slug' => $documentState['template_slug'],
            'payload' => $documentState['payload'],
            'html' => $this->renderPreviewHtml($generatorType, $documentState['template'], $documentState['payload']),
            'copy_text' => $documentState['copy_text'],
            'file_name' => $documentState['file_name'],
        ];
    }

    public function downloadPdf(string $generatorType, array $payload, ?string $templateSlug)
    {
        $documentState = $this->compose($generatorType, $payload, $templateSlug);

        return Pdf::loadView('generators.pdf', [
            'template' => $documentState['template'],
            'templateView' => $this->templateService->resolveViewPath($documentState['template']),
            'document' => $documentState['payload'],
        ])
            ->setPaper($documentState['template']->paper_size ?: 'a4', $documentState['template']->orientation ?: 'portrait')
            ->download($documentState['file_name']);
    }

    public function printView(string $generatorType, array $payload, ?string $templateSlug): Response
    {
        $documentState = $this->compose($generatorType, $payload, $templateSlug);

        return response()->view('generators.print', [
            'template' => $documentState['template'],
            'templateView' => $this->templateService->resolveViewPath($documentState['template']),
            'document' => $documentState['payload'],
            'title' => $this->documentTitle($generatorType, $documentState['payload']),
        ]);
    }

    public function compose(string $generatorType, array $payload, ?string $templateSlug): array
    {
        $template = $this->templateService->findTemplate($generatorType, $templateSlug);
        $document = $this->normalizePayload($generatorType, $payload);

        return [
            'template' => $template,
            'template_slug' => $template->slug,
            'payload' => $document,
            'copy_text' => $this->copyText($generatorType, $document),
            'file_name' => $this->buildFilename($generatorType, $document),
        ];
    }

    public function logAction(Request $request, string $generatorType, ?string $templateSlug, string $action): void
    {
        if (! in_array($action, ['preview', 'download_pdf', 'print', 'copy'], true)) {
            return;
        }

        GeneratorDownloadLog::query()->create([
            'generator_type' => $generatorType,
            'template_slug' => $templateSlug,
            'action' => $action,
            'ip_hash' => filled($request->ip())
                ? hash('sha256', config('app.key').'|'.$request->ip())
                : null,
            'user_agent_hash' => filled($request->userAgent())
                ? hash('sha256', config('app.key').'|'.$request->userAgent())
                : null,
            'created_at' => now(),
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
            'receipt' => implode("\n", array_filter([
                'KWITANSI',
                filled($payload['receipt_number'] ?? null) ? 'Nomor: '.$payload['receipt_number'] : null,
                filled($payload['city'] ?? null) ? $payload['city'].', '.($payload['receipt_date_label'] ?? $payload['receipt_date']) : ($payload['receipt_date_label'] ?? $payload['receipt_date']),
                'Sudah terima dari: '.($payload['payer_name'] ?? '-'),
                'Jumlah: '.($payload['amount_label'] ?? '-'),
                'Untuk pembayaran: '.($payload['description'] ?? '-'),
                filled($payload['payment_method'] ?? null) ? 'Metode pembayaran: '.$payload['payment_method'] : null,
                filled($payload['notes'] ?? null) ? 'Catatan: '.$payload['notes'] : null,
                '',
                'Penerima,',
                '',
                (string) ($payload['receiver_name'] ?? ''),
            ])),
            'minutes' => implode("\n", array_filter([
                strtoupper((string) ($payload['title'] ?? 'BERITA ACARA')),
                filled($payload['document_number'] ?? null) ? 'Nomor: '.$payload['document_number'] : null,
                filled($payload['location'] ?? null) ? 'Lokasi: '.$payload['location'] : null,
                filled($payload['event_date_label'] ?? null) ? 'Tanggal: '.$payload['event_date_label'] : null,
                '',
                $payload['opening'] ?? null,
                '',
                $payload['content'] ?? null,
                '',
                $payload['closing'] ?? null,
            ])),
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
            'receipt' => $this->normalizeReceiptPayload($payload),
            'minutes' => $this->normalizeMinutesPayload($payload),
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

    protected function normalizeReceiptPayload(array $payload): array
    {
        $receiptDate = $payload['receipt_date'] ?? null;

        return array_merge($payload, [
            'receipt_date_label' => $receiptDate ? Carbon::parse($receiptDate)->translatedFormat('d F Y') : null,
            'amount_label' => 'Rp '.number_format((float) ($payload['amount'] ?? 0), 0, ',', '.'),
        ]);
    }

    protected function normalizeMinutesPayload(array $payload): array
    {
        $eventDate = $payload['event_date'] ?? null;

        return array_merge($payload, [
            'event_date_label' => $eventDate ? Carbon::parse($eventDate)->translatedFormat('d F Y') : null,
            'content_paragraphs' => collect(preg_split("/\r\n|\n|\r/", (string) ($payload['content'] ?? '')))
                ->map(fn (string $line): string => trim($line))
                ->filter()
                ->values()
                ->all(),
            'opening' => trim((string) ($payload['opening'] ?? '')),
            'closing' => trim((string) ($payload['closing'] ?? 'Demikian berita acara ini dibuat untuk dipergunakan sebagaimana mestinya.')),
        ]);
    }

    protected function buildFilename(string $generatorType, array $document): string
    {
        return match ($generatorType) {
            'invoice' => 'invoice-'.Str::slug((string) ($document['invoice_number'] ?? 'dokumen')).'-bantukerja.pdf',
            'letter' => 'surat-izin-bantukerja.pdf',
            'receipt' => 'kwitansi-'.Str::slug((string) ($document['receipt_number'] ?? $document['payer_name'] ?? 'bantukerja')).'.pdf',
            'minutes' => 'berita-acara-'.Str::slug((string) ($document['title'] ?? 'bantukerja')).'.pdf',
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
