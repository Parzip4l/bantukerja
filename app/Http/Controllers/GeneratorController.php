<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceGeneratorRequest;
use App\Http\Requests\LetterGeneratorRequest;
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
        $payload = $request->safe()->except(['business_logo']);

        if ($request->hasFile('business_logo')) {
            $payload['business_logo_path'] = $templateRenderService
                ->storePublicUpload($request->file('business_logo'), 'tool-uploads/invoices/logos');
        }

        $preview = $documentGeneratorService->preview('invoice', $payload, $request->input('template_slug'));

        return back()->withInput($payload)->with('generator_preview', [
            'tool_slug' => 'generator-invoice',
            'generator_type' => 'invoice',
            'payload' => $payload,
            'template_slug' => $preview['template_slug'],
        ]);
    }

    public function downloadInvoice(
        InvoiceGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        $payload = $request->safe()->except(['business_logo']);

        if ($request->hasFile('business_logo')) {
            $payload['business_logo_path'] = $templateRenderService
                ->storePublicUpload($request->file('business_logo'), 'tool-uploads/invoices/logos');
        }

        return $documentGeneratorService->downloadPdf('invoice', $payload, $request->input('template_slug'));
    }

    public function printInvoice(
        InvoiceGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        $payload = $request->safe()->except(['business_logo']);

        if ($request->hasFile('business_logo')) {
            $payload['business_logo_path'] = $templateRenderService
                ->storePublicUpload($request->file('business_logo'), 'tool-uploads/invoices/logos');
        }

        return $documentGeneratorService->printView('invoice', $payload, $request->input('template_slug'));
    }

    public function previewLetter(
        LetterGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ): RedirectResponse {
        $payload = $request->validated();
        $preview = $documentGeneratorService->preview('letter', $payload, $request->input('template_slug'));

        return back()->withInput($payload)->with('generator_preview', [
            'tool_slug' => 'generator-surat-izin',
            'generator_type' => 'letter',
            'payload' => $payload,
            'template_slug' => $preview['template_slug'],
        ]);
    }

    public function downloadLetterPdf(
        LetterGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $documentGeneratorService->downloadPdf('letter', $request->validated(), $request->input('template_slug'));
    }

    public function printLetter(
        LetterGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $documentGeneratorService->printView('letter', $request->validated(), $request->input('template_slug'));
    }

    public function downloadLetterText(
        LetterGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        $payload = $documentGeneratorService->preview('letter', $request->validated(), $request->input('template_slug'));

        return $templateRenderService->downloadText('surat-izin-bantukerja.txt', (string) $payload['copy_text']);
    }
}
