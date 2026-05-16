<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceGeneratorRequest;
use App\Http\Requests\LetterGeneratorRequest;
use App\Http\Requests\MinutesGeneratorRequest;
use App\Http\Requests\ReceiptGeneratorRequest;
use App\Services\DocumentGeneratorService;
use App\Services\TemplateRenderService;
use Illuminate\Http\RedirectResponse;

class GeneratorController extends Controller
{
    public function previewInvoice(
        InvoiceGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ): RedirectResponse {
        $payload = $this->invoicePayload($request, $templateRenderService);

        return $this->previewResponse(
            $request,
            $documentGeneratorService,
            'generator-invoice',
            'invoice',
            $payload,
            $request->input('template_slug'),
        );
    }

    public function downloadInvoice(
        InvoiceGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        $payload = $this->invoicePayload($request, $templateRenderService);

        return $this->downloadPdfResponse(
            $request,
            $documentGeneratorService,
            'invoice',
            $payload,
            $request->input('template_slug'),
        );
    }

    public function printInvoice(
        InvoiceGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        $payload = $this->invoicePayload($request, $templateRenderService);

        return $this->printResponse(
            $request,
            $documentGeneratorService,
            'invoice',
            $payload,
            $request->input('template_slug'),
        );
    }

    public function previewLetter(
        LetterGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ): RedirectResponse {
        return $this->previewResponse(
            $request,
            $documentGeneratorService,
            'generator-surat-izin',
            'letter',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function downloadLetterPdf(
        LetterGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->downloadPdfResponse(
            $request,
            $documentGeneratorService,
            'letter',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function printLetter(
        LetterGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->printResponse(
            $request,
            $documentGeneratorService,
            'letter',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function downloadLetterText(
        LetterGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        return $this->downloadTextResponse(
            $request,
            $documentGeneratorService,
            $templateRenderService,
            'letter',
            $request->validated(),
            $request->input('template_slug'),
            'surat-izin-bantukerja.txt',
        );
    }

    public function previewReceipt(
        ReceiptGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ): RedirectResponse {
        return $this->previewResponse(
            $request,
            $documentGeneratorService,
            'generator-kwitansi',
            'receipt',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function downloadReceipt(
        ReceiptGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->downloadPdfResponse(
            $request,
            $documentGeneratorService,
            'receipt',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function printReceipt(
        ReceiptGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->printResponse(
            $request,
            $documentGeneratorService,
            'receipt',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function previewMinutes(
        MinutesGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ): RedirectResponse {
        return $this->previewResponse(
            $request,
            $documentGeneratorService,
            'generator-berita-acara',
            'minutes',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function downloadMinutes(
        MinutesGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->downloadPdfResponse(
            $request,
            $documentGeneratorService,
            'minutes',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function printMinutes(
        MinutesGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->printResponse(
            $request,
            $documentGeneratorService,
            'minutes',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    protected function invoicePayload(
        InvoiceGeneratorRequest $request,
        TemplateRenderService $templateRenderService,
    ): array {
        $payload = $request->safe()->except(['business_logo']);

        if ($request->hasFile('business_logo')) {
            $payload['business_logo_path'] = $templateRenderService
                ->storePublicUpload($request->file('business_logo'), 'tool-uploads/invoices/logos');
        }

        return $payload;
    }

    protected function previewResponse(
        $request,
        DocumentGeneratorService $documentGeneratorService,
        string $toolSlug,
        string $generatorType,
        array $payload,
        ?string $templateSlug,
    ): RedirectResponse {
        $preview = $documentGeneratorService->preview($generatorType, $payload, $templateSlug);
        $documentGeneratorService->logAction($request, $generatorType, $preview['template_slug'], 'preview');

        return back()->withInput($payload)->with('generator_preview', [
            'tool_slug' => $toolSlug,
            'generator_type' => $generatorType,
            'payload' => $payload,
            'template_slug' => $preview['template_slug'],
        ]);
    }

    protected function downloadPdfResponse(
        $request,
        DocumentGeneratorService $documentGeneratorService,
        string $generatorType,
        array $payload,
        ?string $templateSlug,
    ) {
        $documentState = $documentGeneratorService->compose($generatorType, $payload, $templateSlug);
        $documentGeneratorService->logAction($request, $generatorType, $documentState['template_slug'], 'download_pdf');

        return $documentGeneratorService->downloadPdf($generatorType, $payload, $documentState['template_slug']);
    }

    protected function printResponse(
        $request,
        DocumentGeneratorService $documentGeneratorService,
        string $generatorType,
        array $payload,
        ?string $templateSlug,
    ) {
        $documentState = $documentGeneratorService->compose($generatorType, $payload, $templateSlug);
        $documentGeneratorService->logAction($request, $generatorType, $documentState['template_slug'], 'print');

        return $documentGeneratorService->printView($generatorType, $payload, $documentState['template_slug']);
    }

    protected function downloadTextResponse(
        $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
        string $generatorType,
        array $payload,
        ?string $templateSlug,
        string $filename,
    ) {
        $documentState = $documentGeneratorService->compose($generatorType, $payload, $templateSlug);
        $documentGeneratorService->logAction($request, $generatorType, $documentState['template_slug'], 'copy');

        return $templateRenderService->downloadText($filename, (string) $documentState['copy_text']);
    }
}
