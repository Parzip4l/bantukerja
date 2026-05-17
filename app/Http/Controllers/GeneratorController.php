<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationLetterGeneratorRequest;
use App\Http\Requests\AtsCvCheckerRequest;
use App\Http\Requests\DailyWorkReportRequest;
use App\Http\Requests\InvoiceGeneratorRequest;
use App\Http\Requests\InterviewSimulationRequest;
use App\Http\Requests\InterviewStarAnswerRequest;
use App\Http\Requests\JobDescriptionGeneratorRequest;
use App\Http\Requests\JobDescriptionMatcherRequest;
use App\Http\Requests\LetterGeneratorRequest;
use App\Http\Requests\LinkedInProfileGeneratorRequest;
use App\Http\Requests\MinutesGeneratorRequest;
use App\Http\Requests\QuotationGeneratorRequest;
use App\Http\Requests\ReceiptGeneratorRequest;
use App\Http\Requests\SopGeneratorRequest;
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

    public function previewApplicationLetter(
        ApplicationLetterGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ): RedirectResponse {
        return $this->previewResponse(
            $request,
            $documentGeneratorService,
            'generator-surat-lamaran-kerja',
            'application-letter',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function downloadApplicationLetter(
        ApplicationLetterGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->downloadPdfResponse(
            $request,
            $documentGeneratorService,
            'application-letter',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function printApplicationLetter(
        ApplicationLetterGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->printResponse(
            $request,
            $documentGeneratorService,
            'application-letter',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function downloadApplicationLetterText(
        ApplicationLetterGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        return $this->downloadTextResponse(
            $request,
            $documentGeneratorService,
            $templateRenderService,
            'application-letter',
            $request->validated(),
            $request->input('template_slug'),
            'surat-lamaran-kerja-bantukerja.txt',
        );
    }

    public function previewQuotation(
        QuotationGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ): RedirectResponse {
        $payload = $this->quotationPayload($request, $templateRenderService);

        return $this->previewResponse(
            $request,
            $documentGeneratorService,
            'generator-quotation',
            'quotation',
            $payload,
            $request->input('template_slug'),
        );
    }

    public function downloadQuotation(
        QuotationGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        $payload = $this->quotationPayload($request, $templateRenderService);

        return $this->downloadPdfResponse(
            $request,
            $documentGeneratorService,
            'quotation',
            $payload,
            $request->input('template_slug'),
        );
    }

    public function printQuotation(
        QuotationGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        $payload = $this->quotationPayload($request, $templateRenderService);

        return $this->printResponse(
            $request,
            $documentGeneratorService,
            'quotation',
            $payload,
            $request->input('template_slug'),
        );
    }

    public function downloadQuotationText(
        QuotationGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        $payload = $this->quotationPayload($request, $templateRenderService);

        return $this->downloadTextResponse(
            $request,
            $documentGeneratorService,
            $templateRenderService,
            'quotation',
            $payload,
            $request->input('template_slug'),
            'quotation-ringkasan-bantukerja.txt',
        );
    }

    public function previewSop(
        SopGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ): RedirectResponse {
        return $this->previewResponse(
            $request,
            $documentGeneratorService,
            'generator-sop',
            'sop',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function downloadSop(
        SopGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->downloadPdfResponse(
            $request,
            $documentGeneratorService,
            'sop',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function printSop(
        SopGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->printResponse(
            $request,
            $documentGeneratorService,
            'sop',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function downloadSopText(
        SopGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        return $this->downloadTextResponse(
            $request,
            $documentGeneratorService,
            $templateRenderService,
            'sop',
            $request->validated(),
            $request->input('template_slug'),
            'sop-bantukerja.txt',
        );
    }

    public function previewJobDescription(
        JobDescriptionGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ): RedirectResponse {
        return $this->previewResponse(
            $request,
            $documentGeneratorService,
            'generator-job-description',
            'job-description',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function downloadJobDescription(
        JobDescriptionGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->downloadPdfResponse(
            $request,
            $documentGeneratorService,
            'job-description',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function printJobDescription(
        JobDescriptionGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->printResponse(
            $request,
            $documentGeneratorService,
            'job-description',
            $request->validated(),
            $request->input('template_slug'),
        );
    }

    public function downloadJobDescriptionText(
        JobDescriptionGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        return $this->downloadTextResponse(
            $request,
            $documentGeneratorService,
            $templateRenderService,
            'job-description',
            $request->validated(),
            $request->input('template_slug'),
            'job-description-bantukerja.txt',
        );
    }

    public function previewInterviewSimulation(
        InterviewSimulationRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ): RedirectResponse {
        return $this->previewResponse($request, $documentGeneratorService, 'simulasi-pertanyaan-interview', 'interview-simulation', $request->validated(), $request->input('template_slug'));
    }

    public function downloadInterviewSimulation(
        InterviewSimulationRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->downloadPdfResponse($request, $documentGeneratorService, 'interview-simulation', $request->validated(), $request->input('template_slug'));
    }

    public function printInterviewSimulation(
        InterviewSimulationRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->printResponse($request, $documentGeneratorService, 'interview-simulation', $request->validated(), $request->input('template_slug'));
    }

    public function downloadInterviewSimulationText(
        InterviewSimulationRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        return $this->downloadTextResponse($request, $documentGeneratorService, $templateRenderService, 'interview-simulation', $request->validated(), $request->input('template_slug'), 'simulasi-pertanyaan-interview-bantukerja.txt');
    }

    public function previewInterviewStarAnswer(
        InterviewStarAnswerRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ): RedirectResponse {
        return $this->previewResponse($request, $documentGeneratorService, 'interview-answer-star', 'interview-star', $request->validated(), $request->input('template_slug'));
    }

    public function downloadInterviewStarAnswer(
        InterviewStarAnswerRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->downloadPdfResponse($request, $documentGeneratorService, 'interview-star', $request->validated(), $request->input('template_slug'));
    }

    public function printInterviewStarAnswer(
        InterviewStarAnswerRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->printResponse($request, $documentGeneratorService, 'interview-star', $request->validated(), $request->input('template_slug'));
    }

    public function downloadInterviewStarAnswerText(
        InterviewStarAnswerRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        return $this->downloadTextResponse($request, $documentGeneratorService, $templateRenderService, 'interview-star', $request->validated(), $request->input('template_slug'), 'jawaban-interview-star-bantukerja.txt');
    }

    public function previewLinkedInProfile(
        LinkedInProfileGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ): RedirectResponse {
        return $this->previewResponse($request, $documentGeneratorService, 'linkedin-headline-about-generator', 'linkedin-profile', $request->validated(), $request->input('template_slug'));
    }

    public function downloadLinkedInProfile(
        LinkedInProfileGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->downloadPdfResponse($request, $documentGeneratorService, 'linkedin-profile', $request->validated(), $request->input('template_slug'));
    }

    public function printLinkedInProfile(
        LinkedInProfileGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->printResponse($request, $documentGeneratorService, 'linkedin-profile', $request->validated(), $request->input('template_slug'));
    }

    public function downloadLinkedInProfileText(
        LinkedInProfileGeneratorRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        return $this->downloadTextResponse($request, $documentGeneratorService, $templateRenderService, 'linkedin-profile', $request->validated(), $request->input('template_slug'), 'linkedin-headline-about-bantukerja.txt');
    }

    public function previewJobDescriptionMatcher(
        JobDescriptionMatcherRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ): RedirectResponse {
        return $this->previewResponse($request, $documentGeneratorService, 'job-description-matcher', 'jd-matcher', $request->validated(), $request->input('template_slug'));
    }

    public function downloadJobDescriptionMatcher(
        JobDescriptionMatcherRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->downloadPdfResponse($request, $documentGeneratorService, 'jd-matcher', $request->validated(), $request->input('template_slug'));
    }

    public function printJobDescriptionMatcher(
        JobDescriptionMatcherRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->printResponse($request, $documentGeneratorService, 'jd-matcher', $request->validated(), $request->input('template_slug'));
    }

    public function downloadJobDescriptionMatcherText(
        JobDescriptionMatcherRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        return $this->downloadTextResponse($request, $documentGeneratorService, $templateRenderService, 'jd-matcher', $request->validated(), $request->input('template_slug'), 'job-description-matcher-bantukerja.txt');
    }

    public function previewAtsCvChecker(
        AtsCvCheckerRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ): RedirectResponse {
        return $this->previewResponse($request, $documentGeneratorService, 'ats-cv-checker', 'ats-cv-checker', $this->atsCvCheckerPayload($request), $request->input('template_slug'));
    }

    public function downloadAtsCvChecker(
        AtsCvCheckerRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->downloadPdfResponse($request, $documentGeneratorService, 'ats-cv-checker', $this->atsCvCheckerPayload($request), $request->input('template_slug'));
    }

    public function printAtsCvChecker(
        AtsCvCheckerRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->printResponse($request, $documentGeneratorService, 'ats-cv-checker', $this->atsCvCheckerPayload($request), $request->input('template_slug'));
    }

    public function downloadAtsCvCheckerText(
        AtsCvCheckerRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        return $this->downloadTextResponse($request, $documentGeneratorService, $templateRenderService, 'ats-cv-checker', $this->atsCvCheckerPayload($request), $request->input('template_slug'), 'ats-cv-checker-bantukerja.txt');
    }

    public function downloadAtsCvCheckerWord(
        AtsCvCheckerRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        $documentState = $documentGeneratorService->compose('ats-cv-checker', $this->atsCvCheckerPayload($request), $request->input('template_slug'));
        $documentGeneratorService->logAction($request, 'ats-cv-checker', $documentState['template_slug'], 'download_word');

        return $templateRenderService->downloadWord(
            'ats-cv-checker-bantukerja.doc',
            'ATS CV Checker',
            view('word.ats-cv-checker', ['document' => $documentState['payload']])->render(),
        );
    }

    public function previewDailyWorkReport(
        DailyWorkReportRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ): RedirectResponse {
        return $this->previewResponse($request, $documentGeneratorService, 'generator-laporan-kerja-harian', 'daily-work-report', $request->validated(), $request->input('template_slug'));
    }

    public function downloadDailyWorkReport(
        DailyWorkReportRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->downloadPdfResponse($request, $documentGeneratorService, 'daily-work-report', $request->validated(), $request->input('template_slug'));
    }

    public function printDailyWorkReport(
        DailyWorkReportRequest $request,
        DocumentGeneratorService $documentGeneratorService,
    ) {
        return $this->printResponse($request, $documentGeneratorService, 'daily-work-report', $request->validated(), $request->input('template_slug'));
    }

    public function downloadDailyWorkReportText(
        DailyWorkReportRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        return $this->downloadTextResponse($request, $documentGeneratorService, $templateRenderService, 'daily-work-report', $request->validated(), $request->input('template_slug'), 'laporan-kerja-harian-bantukerja.txt');
    }

    public function downloadDailyWorkReportWord(
        DailyWorkReportRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        $documentState = $documentGeneratorService->compose('daily-work-report', $request->validated(), $request->input('template_slug'));
        $documentGeneratorService->logAction($request, 'daily-work-report', $documentState['template_slug'], 'download_pdf');

        return $templateRenderService->downloadWord(
            'laporan-kerja-harian-bantukerja.doc',
            'Laporan Kerja Harian',
            view('word.daily-work-report', ['document' => $documentState['payload']])->render(),
        );
    }

    public function downloadJobDescriptionMatcherWord(
        JobDescriptionMatcherRequest $request,
        DocumentGeneratorService $documentGeneratorService,
        TemplateRenderService $templateRenderService,
    ) {
        $documentState = $documentGeneratorService->compose('jd-matcher', $request->validated(), $request->input('template_slug'));
        $documentGeneratorService->logAction($request, 'jd-matcher', $documentState['template_slug'], 'download_word');

        return $templateRenderService->downloadWord(
            'job-description-matcher-bantukerja.doc',
            'Job Description Matcher',
            view('word.jd-matcher', ['document' => $documentState['payload']])->render(),
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

    protected function quotationPayload(
        QuotationGeneratorRequest $request,
        TemplateRenderService $templateRenderService,
    ): array {
        $payload = $request->safe()->except(['vendor_logo']);

        if ($request->hasFile('vendor_logo')) {
            $payload['vendor_logo_path'] = $templateRenderService
                ->storePublicUpload($request->file('vendor_logo'), 'tool-uploads/quotations/logos');
        }

        return $payload;
    }

    protected function atsCvCheckerPayload(AtsCvCheckerRequest $request): array
    {
        return collect($request->validated())
            ->except('cv_pdf')
            ->all();
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
