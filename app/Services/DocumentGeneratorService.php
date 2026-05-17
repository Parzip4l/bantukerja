<?php

namespace App\Services;

use App\Models\GeneratorDownloadLog;
use App\Models\GeneratorTemplate;
use App\Models\Tool;
use App\Support\DocumentFormatter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
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
            'generator-surat-lamaran-kerja' => 'application-letter',
            'generator-quotation' => 'quotation',
            'generator-sop' => 'sop',
            'generator-job-description' => 'job-description',
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
            'renderMode' => 'pdf',
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
            'renderMode' => 'print',
        ]);
    }

    public function compose(string $generatorType, array $payload, ?string $templateSlug): array
    {
        $template = $this->templateService->findTemplate($generatorType, $templateSlug);
        $document = $this->normalizePayload($generatorType, $payload, $template->slug);

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
        if (! in_array($action, ['preview', 'download_pdf', 'print', 'copy'], true) || ! $this->hasDownloadLogTable()) {
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
            'letter' => implode("\n", array_filter([
                strtoupper((string) ($payload['subject'] ?? 'SURAT')),
                '',
                filled($payload['city']) ? $payload['city'].', '.($payload['date_label'] ?? $payload['date']) : ($payload['date_label'] ?? $payload['date']),
                '',
                filled($payload['recipient']) ? 'Kepada Yth. '.$payload['recipient'] : null,
                filled($payload['recipient']) ? '' : null,
                'Dengan hormat,',
                '',
                trim((string) ($payload['body_text'] ?? '')),
                '',
                'Hormat saya,',
                '',
                (string) ($payload['name'] ?? ''),
                filled($payload['position']) ? (string) $payload['position'] : null,
                filled($payload['company']) ? (string) $payload['company'] : null,
            ])),
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
            'application-letter' => $this->applicationLetterCopyText($payload),
            'quotation' => $this->quotationCopyText($payload),
            'sop' => $this->sopCopyText($payload),
            'job-description' => $this->jobDescriptionCopyText($payload),
            default => null,
        };
    }

    protected function renderPreviewHtml(string $generatorType, GeneratorTemplate $template, array $document): string
    {
        return view($this->previewView($generatorType), [
            'template' => $template,
            'templateView' => $this->templateService->resolveViewPath($template),
            'document' => $document,
            'renderMode' => 'preview',
        ])->render();
    }

    protected function previewView(string $generatorType): string
    {
        return match ($generatorType) {
            'invoice' => 'generators.invoice.preview',
            'letter' => 'generators.letter.preview',
            'receipt' => 'generators.receipt.preview',
            'minutes' => 'generators.minutes.preview',
            'application-letter' => 'generators.application-letter.preview',
            'quotation' => 'generators.quotation.preview',
            'sop' => 'generators.sop.preview',
            'job-description' => 'generators.job-description.preview',
            default => 'generators.letter.preview',
        };
    }

    protected function normalizePayload(string $generatorType, array $payload, ?string $templateSlug = null): array
    {
        return match ($generatorType) {
            'invoice' => $this->normalizeInvoicePayload($payload),
            'letter' => $this->normalizeLetterPayload($payload),
            'receipt' => $this->normalizeReceiptPayload($payload),
            'minutes' => $this->normalizeMinutesPayload($payload),
            'application-letter' => $this->normalizeApplicationLetterPayload($payload, $templateSlug),
            'quotation' => $this->normalizeQuotationPayload($payload, $templateSlug),
            'sop' => $this->normalizeSopPayload($payload),
            'job-description' => $this->normalizeJobDescriptionPayload($payload, $templateSlug),
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
            'business_logo_pdf_path' => $this->templateRenderService->publicStoragePath($businessLogoPath),
            'invoice_date_label' => DocumentFormatter::dateLabel($payload['invoice_date'] ?? null),
            'due_date_label' => DocumentFormatter::dateLabel($payload['due_date'] ?? null),
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
        $dateLabel = DocumentFormatter::dateLabel($payload['date'] ?? null);
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
            'subject' => 'Surat Izin Kerja',
            'date_label' => $dateLabel,
            'body_text' => $bodyText,
        ]);
    }

    protected function normalizeReceiptPayload(array $payload): array
    {
        return array_merge($payload, [
            'receipt_date_label' => DocumentFormatter::dateLabel($payload['receipt_date'] ?? null),
            'amount_label' => DocumentFormatter::formatCurrencyIdr((float) ($payload['amount'] ?? 0)),
        ]);
    }

    protected function normalizeMinutesPayload(array $payload): array
    {
        return array_merge($payload, [
            'event_date_label' => DocumentFormatter::dateLabel($payload['event_date'] ?? null),
            'content_paragraphs' => DocumentFormatter::lines($payload['content'] ?? ''),
            'opening' => trim((string) ($payload['opening'] ?? '')),
            'closing' => trim((string) ($payload['closing'] ?? 'Demikian berita acara ini dibuat untuk dipergunakan sebagaimana mestinya.')),
        ]);
    }

    protected function normalizeApplicationLetterPayload(array $payload, ?string $templateSlug = null): array
    {
        $dateLabel = DocumentFormatter::dateLabel($payload['date'] ?? null);
        $experienceLabels = [
            'fresh-graduate' => 'fresh graduate',
            '1-2-tahun' => '1 sampai 2 tahun',
            '3-5-tahun' => '3 sampai 5 tahun',
            '5-plus-tahun' => 'lebih dari 5 tahun',
        ];
        $letterTypeLabels = [
            'formal' => 'Formal',
            'singkat-profesional' => 'Singkat Profesional',
            'fresh-graduate' => 'Fresh Graduate',
            'berpengalaman' => 'Berpengalaman',
            'magang' => 'Magang / Internship',
            'email-lamaran' => 'Email Lamaran',
        ];

        $skillsList = DocumentFormatter::commaOrLineList($payload['main_skills'] ?? '');
        $isEmailMode = ($payload['letter_type'] ?? null) === 'email-lamaran' || $templateSlug === 'application-letter-email';
        $greeting = filled($payload['recruiter_name'] ?? null)
            ? 'Yth. Bapak/Ibu '.trim((string) $payload['recruiter_name'])
            : 'Yth. Tim Rekrutmen';
        $experienceLabel = $experienceLabels[$payload['experience_level']] ?? $payload['experience_level'];

        $tone = ($payload['language_style'] ?? 'indonesia-formal') === 'indonesia-santai-profesional'
            ? [
                'opening' => "Saya mendapatkan informasi mengenai lowongan {$payload['position_applied']} di {$payload['company_name']}".(filled($payload['job_source'] ?? null) ? " melalui {$payload['job_source']}" : '')." dan tertarik untuk mengajukan lamaran pada posisi tersebut.",
                'closing' => 'Saya siap mengikuti tahapan seleksi berikutnya dan dengan senang hati akan menjelaskan pengalaman saya lebih lanjut apabila dibutuhkan.',
            ]
            : [
                'opening' => "Melalui surat ini saya bermaksud mengajukan lamaran untuk posisi {$payload['position_applied']} di {$payload['company_name']}".(filled($payload['job_source'] ?? null) ? " berdasarkan informasi lowongan yang saya peroleh dari {$payload['job_source']}" : '').'.',
                'closing' => 'Besar harapan saya untuk memperoleh kesempatan wawancara agar dapat menjelaskan potensi dan kontribusi yang bisa saya berikan secara lebih rinci.',
            ];

        $profileParagraph = "Perkenalkan, saya {$payload['full_name']}, berdomisili di {$payload['city']}. "
            ."Saya memiliki latar belakang pendidikan {$payload['education_level']}"
            .(filled($payload['major'] ?? null) ? " {$payload['major']}" : '')
            ." dengan pengalaman {$experienceLabel}."
            ." Saya dapat dihubungi melalui {$payload['email']} atau {$payload['phone']}.";

        $skillsSentence = collect($skillsList)->take(5)->implode(', ');
        $relevanceParagraph = trim(($payload['experience_summary'] ?? '').' '
            .($skillsSentence !== '' ? "Keahlian utama saya mencakup {$skillsSentence}." : '')
            .' Saya percaya kombinasi pengalaman dan keahlian tersebut relevan untuk mendukung kebutuhan tim Anda.');

        $closingParagraph = ($payload['letter_type'] ?? null) === 'fresh-graduate'
            ? 'Sebagai fresh graduate, saya antusias untuk belajar cepat, bekerja dengan disiplin, dan tumbuh bersama perusahaan.'
            : (($payload['letter_type'] ?? null) === 'magang'
                ? 'Saya berharap dapat memperoleh kesempatan magang untuk mengembangkan kemampuan kerja sekaligus memberikan kontribusi nyata kepada tim.'
                : $tone['closing']);

        $emailSubject = 'Lamaran '.trim((string) $payload['position_applied']).' - '.trim((string) $payload['full_name']);
        $emailBody = implode("\n\n", array_filter([
            $greeting.',',
            $tone['opening'],
            $profileParagraph,
            $relevanceParagraph,
            $closingParagraph,
            "Terima kasih atas perhatian Bapak/Ibu. Saya menantikan kesempatan untuk berdiskusi lebih lanjut.\n\nHormat saya,\n{$payload['full_name']}\n{$payload['phone']}\n{$payload['email']}",
        ]));

        return array_merge($payload, [
            'subject' => 'Surat Lamaran Kerja',
            'date_label' => $dateLabel,
            'letter_type_label' => $letterTypeLabels[$payload['letter_type']] ?? $payload['letter_type'],
            'experience_level_label' => $experienceLabel,
            'skills_list' => $skillsList,
            'greeting' => $greeting,
            'opening_paragraph' => $tone['opening'],
            'profile_paragraph' => $profileParagraph,
            'relevance_paragraph' => $relevanceParagraph,
            'closing_paragraph' => $closingParagraph,
            'email_subject' => $emailSubject,
            'email_body' => $emailBody,
            'is_email_mode' => $isEmailMode,
        ]);
    }

    protected function normalizeQuotationPayload(array $payload, ?string $templateSlug = null): array
    {
        $items = collect($payload['items'] ?? [])
            ->filter(fn (array $item): bool => filled($item['name'] ?? null))
            ->take(30)
            ->map(function (array $item): array {
                $qty = (float) ($item['qty'] ?? 0);
                $price = (float) ($item['price'] ?? 0);
                $discount = (float) ($item['discount'] ?? 0);
                $subtotal = max(($qty * $price) - $discount, 0);

                return [
                    'name' => DocumentFormatter::sanitizeText($item['name'] ?? ''),
                    'description' => DocumentFormatter::sanitizeText($item['description'] ?? ''),
                    'qty' => $qty,
                    'unit' => DocumentFormatter::sanitizeText($item['unit'] ?? ''),
                    'price' => $price,
                    'discount' => $discount,
                    'subtotal' => round($subtotal, 2),
                    'price_label' => DocumentFormatter::formatCurrencyIdr($price),
                    'discount_label' => DocumentFormatter::formatCurrencyIdr($discount),
                    'subtotal_label' => DocumentFormatter::formatCurrencyIdr($subtotal),
                ];
            })
            ->values();

        $subtotal = (float) $items->sum('subtotal');
        $taxPercentage = (float) ($payload['tax_percentage'] ?? 0);
        $taxAmount = $subtotal * ($taxPercentage / 100);
        $additionalFee = (float) ($payload['additional_fee'] ?? 0);
        $grandTotal = $subtotal + $taxAmount + $additionalFee;
        $validityDays = (int) ($payload['validity_days'] ?? 14);
        $quotationDate = $payload['quotation_date'] ?? null;
        $paymentTermsLabel = match ($payload['payment_terms'] ?? null) {
            '100-awal' => '100% di awal pekerjaan',
            'dp50-pelunasan50' => 'DP 50% di awal, pelunasan 50% setelah pekerjaan selesai',
            'dp30-pelunasan70' => 'DP 30% di awal, pelunasan 70% setelah pekerjaan selesai',
            default => filled($payload['payment_terms_custom'] ?? null) ? $payload['payment_terms_custom'] : 'Sesuai kesepakatan bersama',
        };
        $logoPath = $payload['vendor_logo_path'] ?? null;

        return array_merge($payload, [
            'template_variant' => $templateSlug ?: 'quotation-professional',
            'vendor_logo_path' => $logoPath,
            'vendor_logo_url' => $this->templateRenderService->publicUrl($logoPath),
            'vendor_logo_pdf_path' => $this->templateRenderService->publicStoragePath($logoPath),
            'quotation_date_label' => DocumentFormatter::dateLabel($quotationDate),
            'valid_until_label' => $quotationDate ? Carbon::parse($quotationDate)->addDays($validityDays)->translatedFormat('d F Y') : null,
            'validity_label' => $validityDays.' hari kalender',
            'payment_terms_label' => $paymentTermsLabel,
            'items' => $items->all(),
            'subtotal' => round($subtotal, 2),
            'tax_percentage' => $taxPercentage,
            'tax_amount' => round($taxAmount, 2),
            'additional_fee' => round($additionalFee, 2),
            'grand_total' => round($grandTotal, 2),
            'subtotal_label' => DocumentFormatter::formatCurrencyIdr($subtotal),
            'tax_amount_label' => DocumentFormatter::formatCurrencyIdr($taxAmount),
            'additional_fee_label' => DocumentFormatter::formatCurrencyIdr($additionalFee),
            'grand_total_label' => DocumentFormatter::formatCurrencyIdr($grandTotal),
            'terms_lines' => DocumentFormatter::lines($payload['terms_and_conditions'] ?? ''),
            'notes_lines' => DocumentFormatter::lines($payload['additional_notes'] ?? ''),
        ]);
    }

    protected function normalizeSopPayload(array $payload): array
    {
        $roles = collect($payload['roles'] ?? [])
            ->filter(fn (array $role): bool => filled($role['role'] ?? null) && filled($role['responsibility'] ?? null))
            ->map(fn (array $role): array => [
                'role' => DocumentFormatter::sanitizeText($role['role'] ?? ''),
                'responsibility' => DocumentFormatter::sanitizeText($role['responsibility'] ?? ''),
            ])
            ->values()
            ->all();

        $steps = collect($payload['steps'] ?? [])
            ->filter(fn (array $step): bool => filled($step['name'] ?? null) && filled($step['description'] ?? null))
            ->values()
            ->map(fn (array $step, int $index): array => [
                'number' => $index + 1,
                'name' => DocumentFormatter::sanitizeText($step['name'] ?? ''),
                'description' => DocumentFormatter::sanitizeText($step['description'] ?? ''),
                'pic' => DocumentFormatter::sanitizeText($step['pic'] ?? ''),
                'output' => DocumentFormatter::sanitizeText($step['output'] ?? ''),
            ])
            ->all();

        $typeLabels = [
            'administrasi' => 'SOP Administrasi',
            'hr' => 'SOP HR',
            'finance' => 'SOP Finance',
            'customer-service' => 'SOP Customer Service',
            'operasional' => 'SOP Operasional',
            'it-helpdesk' => 'SOP IT Helpdesk',
            'gudang-inventory' => 'SOP Gudang / Inventory',
            'custom' => 'SOP Custom',
        ];

        return array_merge($payload, [
            'effective_date_label' => DocumentFormatter::dateLabel($payload['effective_date'] ?? null),
            'sop_type_label' => $typeLabels[$payload['sop_type']] ?? $payload['sop_type'],
            'roles' => $roles,
            'steps' => $steps,
            'definitions_lines' => DocumentFormatter::lines($payload['definitions'] ?? ''),
            'related_documents_lines' => DocumentFormatter::lines($payload['related_documents'] ?? ''),
            'risk_notes_lines' => DocumentFormatter::lines($payload['risk_notes'] ?? ''),
            'kpi_lines' => DocumentFormatter::lines($payload['kpi'] ?? ''),
        ]);
    }

    protected function normalizeJobDescriptionPayload(array $payload, ?string $templateSlug = null): array
    {
        $responsibilities = collect($payload['responsibilities'] ?? [])
            ->filter(fn (array $item): bool => filled($item['text'] ?? null))
            ->map(fn (array $item): array => ['text' => DocumentFormatter::sanitizeText($item['text'] ?? '')])
            ->values()
            ->all();

        $kpis = collect($payload['kpis'] ?? [])
            ->filter(fn (array $item): bool => filled($item['text'] ?? null))
            ->map(fn (array $item): array => ['text' => DocumentFormatter::sanitizeText($item['text'] ?? '')])
            ->values()
            ->all();

        $language = $payload['language'] ?? 'id';
        $jobLevelLabel = Str::of((string) ($payload['job_level'] ?? 'staff'))->replace('-', ' ')->title()->toString();
        $employmentTypeLabel = Str::of((string) ($payload['employment_type'] ?? 'full-time'))->replace('-', ' ')->title()->toString();
        $outputType = $payload['output_type'] ?? ($templateSlug === 'job-description-posting' ? 'job-posting' : 'internal-hr');
        $technicalSkills = DocumentFormatter::commaOrLineList($payload['technical_skills'] ?? '');
        $softSkills = DocumentFormatter::commaOrLineList($payload['soft_skills'] ?? '');
        $tools = DocumentFormatter::commaOrLineList($payload['tools_software'] ?? '');
        $benefits = DocumentFormatter::lines($payload['benefits'] ?? '');
        $intro = $outputType === 'job-posting'
            ? "Kami membuka kesempatan untuk posisi {$payload['position_name']} di divisi {$payload['department']}."
            : "Dokumen ini menjelaskan tanggung jawab dan kualifikasi posisi {$payload['position_name']} pada divisi {$payload['department']}.";

        if ($language === 'en') {
            $intro = $outputType === 'job-posting'
                ? "We are hiring for the {$payload['position_name']} role in the {$payload['department']} team."
                : "This document outlines the responsibilities and qualifications for the {$payload['position_name']} role in the {$payload['department']} team.";
        }

        return array_merge($payload, [
            'template_variant' => $templateSlug ?: 'job-description-hr',
            'output_type_label' => $outputType === 'job-posting' ? 'Format Lowongan Kerja' : 'Format Internal HR',
            'job_level_label' => $jobLevelLabel,
            'employment_type_label' => $employmentTypeLabel,
            'responsibilities' => $responsibilities,
            'kpis' => $kpis,
            'technical_skills_list' => $technicalSkills,
            'soft_skills_list' => $softSkills,
            'tools_software_list' => $tools,
            'benefits_list' => $benefits,
            'intro_paragraph' => $intro,
            'language_label' => $language === 'en' ? 'English Basic' : 'Indonesia',
        ]);
    }

    protected function applicationLetterCopyText(array $payload): string
    {
        if ($payload['is_email_mode'] ?? false) {
            return implode("\n", [
                'Subject: '.$payload['email_subject'],
                '',
                $payload['email_body'],
            ]);
        }

        return implode("\n", array_filter([
            $payload['city'].', '.$payload['date_label'],
            '',
            $payload['greeting'],
            '',
            'Dengan hormat,',
            '',
            $payload['opening_paragraph'],
            '',
            $payload['profile_paragraph'],
            '',
            $payload['relevance_paragraph'],
            '',
            $payload['closing_paragraph'],
            '',
            'Hormat saya,',
            '',
            $payload['full_name'],
            'Email: '.$payload['email'],
            'HP: '.$payload['phone'],
        ]));
    }

    protected function quotationCopyText(array $payload): string
    {
        return implode("\n", array_filter([
            'QUOTATION / PENAWARAN HARGA',
            'Nomor: '.$payload['quotation_number'],
            'Tanggal: '.($payload['quotation_date_label'] ?? ''),
            'Vendor: '.$payload['vendor_name'],
            'Client: '.$payload['client_name'],
            'Judul: '.$payload['quotation_title'],
            '',
            'Ringkasan pekerjaan:',
            $payload['project_description'] ?? null,
            '',
            'Total penawaran: '.$payload['grand_total_label'],
            'Masa berlaku: '.$payload['validity_label'],
            'Termin pembayaran: '.$payload['payment_terms_label'],
            '',
            'Dokumen ini dapat disesuaikan kembali mengikuti negosiasi, kebijakan internal, dan kebutuhan bisnis masing-masing pihak.',
        ]));
    }

    protected function sopCopyText(array $payload): string
    {
        return implode("\n", array_filter([
            strtoupper((string) $payload['sop_name']),
            'Nomor Dokumen: '.$payload['document_number'],
            'Versi: '.$payload['document_version'],
            'Tanggal Berlaku: '.($payload['effective_date_label'] ?? ''),
            'Departemen: '.$payload['department'],
            '',
            'Tujuan:',
            $payload['objective'],
            '',
            'Ruang Lingkup:',
            $payload['scope'],
            '',
            'Catatan: Dokumen SOP ini merupakan draf awal yang sebaiknya disesuaikan kembali dengan kebijakan perusahaan masing-masing.',
        ]));
    }

    protected function jobDescriptionCopyText(array $payload): string
    {
        return implode("\n", array_filter([
            strtoupper((string) $payload['position_name']),
            'Departemen: '.$payload['department'],
            'Level: '.$payload['job_level_label'],
            'Tipe kerja: '.$payload['employment_type_label'],
            'Lokasi: '.$payload['work_location'],
            '',
            $payload['position_summary'],
            '',
            'Tujuan posisi:',
            $payload['position_objective'],
        ]));
    }

    protected function buildFilename(string $generatorType, array $document): string
    {
        return match ($generatorType) {
            'invoice' => 'invoice-'.Str::slug((string) ($document['invoice_number'] ?? 'dokumen')).'-bantukerja.pdf',
            'letter' => 'surat-izin-bantukerja.pdf',
            'receipt' => 'kwitansi-'.Str::slug((string) ($document['receipt_number'] ?? $document['payer_name'] ?? 'bantukerja')).'.pdf',
            'minutes' => 'berita-acara-'.Str::slug((string) ($document['title'] ?? 'bantukerja')).'.pdf',
            'application-letter' => 'surat-lamaran-'.Str::slug((string) ($document['full_name'] ?? 'bantukerja')).'.pdf',
            'quotation' => 'quotation-'.Str::slug((string) ($document['quotation_number'] ?? 'bantukerja')).'.pdf',
            'sop' => 'sop-'.Str::slug((string) ($document['sop_name'] ?? 'bantukerja')).'.pdf',
            'job-description' => 'job-description-'.Str::slug((string) ($document['position_name'] ?? 'bantukerja')).'.pdf',
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
            'application-letter' => 'Print Surat Lamaran Kerja',
            'quotation' => 'Print Quotation '.$document['quotation_number'],
            'sop' => 'Print '.$document['sop_name'],
            'job-description' => 'Print Job Description '.$document['position_name'],
            default => 'Print Dokumen',
        };
    }

    protected function hasDownloadLogTable(): bool
    {
        static $exists;

        return $exists ??= Schema::hasTable('generator_download_logs');
    }
}
