<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuditFeeRequest;
use App\Http\Requests\CvAtsGeneratorRequest;
use App\Http\Requests\OvertimeCalculatorRequest;
use App\Http\Requests\ProfessionalServiceFeeRequest;
use App\Http\Requests\SalaryCalculatorRequest;
use App\Http\Requests\ThrCalculatorRequest;
use App\Models\Category;
use App\Models\DocumentTemplate;
use App\Models\Post;
use App\Models\Tool;
use App\Services\DocumentGeneratorService;
use App\Services\GeneratorTemplateService;
use App\Services\SeoService;
use App\Services\TemplateRenderService;
use App\Services\ToolCalculationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ToolController extends Controller
{
    public function index(Request $request, SeoService $seoService): View
    {
        $tools = Tool::query()
            ->with(['category'])
            ->published()
            ->when($request->string('search')->toString(), function ($query, string $search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder->where('title', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%");
                });
            })
            ->when($request->string('category')->toString(), function ($query, string $category): void {
                $query->whereHas('category', fn ($builder) => $builder->where('slug', $category));
            })
            ->latestPublished()
            ->paginate(9)
            ->withQueryString();

        return view('tools.index', [
            'seo' => $seoService->defaults([
                'title' => 'Tools Gratis untuk Kerja dan Administrasi',
                'description' => 'Kumpulan tools gratis seperti kalkulator THR, kalkulator gaji bersih, kalkulator lembur, generator invoice, dan generator surat izin kerja.',
            ]),
            'tools' => $tools,
            'categories' => Category::active()->where('type', 'tool')->orderBy('name')->get(),
        ]);
    }

    public function show(
        string $slug,
        SeoService $seoService,
        GeneratorTemplateService $generatorTemplateService,
        DocumentGeneratorService $documentGeneratorService,
    ): View {
        $tool = Tool::query()
            ->with(['category', 'faqs'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
        $relatedPosts = $tool->relatedPosts()->limit(4)->get();
        $relatedTemplates = $tool->relatedTemplates()->limit(4)->get();

        if ($relatedPosts->isEmpty()) {
            $relatedPosts = Post::published()
                ->latestPublished()
                ->limit(4)
                ->get();
        }

        if ($relatedTemplates->isEmpty()) {
            $relatedTemplates = DocumentTemplate::published()
                ->featured()
                ->latestPublished()
                ->limit(4)
                ->get();
        }

        $generatorType = $documentGeneratorService->generatorTypeForTool($tool);
        $generatorTemplates = collect();
        $generatorPreview = null;

        if ($generatorType) {
            $generatorTemplates = $generatorTemplateService->getTemplatesFor($generatorType);

            $previewSession = session('generator_preview');

            if (($previewSession['tool_slug'] ?? null) === $tool->slug) {
                $generatorPreview = $documentGeneratorService->preview(
                    $previewSession['generator_type'] ?? $generatorType,
                    $previewSession['payload'] ?? [],
                    $previewSession['template_slug'] ?? null,
                );
            }
        }

        return view('tools.show', [
            'tool' => $tool,
            'seo' => $seoService->forModel($tool),
            'relatedPosts' => $relatedPosts,
            'relatedTemplates' => $relatedTemplates,
            'generatorType' => $generatorType,
            'generatorTemplates' => $generatorTemplates,
            'generatorPreview' => $generatorPreview,
        ]);
    }

    public function calculateThr(ThrCalculatorRequest $request, ToolCalculationService $service): RedirectResponse
    {
        return back()->withInput()->with('tool_result', [
            'type' => 'thr',
            'data' => $service->calculateThr(
                (float) $request->input('monthly_salary'),
                (int) $request->input('months_worked'),
                $request->input('employee_status'),
            ),
        ]);
    }

    public function calculateSalary(SalaryCalculatorRequest $request, ToolCalculationService $service): RedirectResponse
    {
        return back()->withInput()->with('tool_result', [
            'type' => 'salary',
            'data' => $service->calculateNetSalary(
                (float) $request->input('base_salary'),
                (float) $request->input('allowance', 0),
                (float) $request->input('deduction', 0),
                (float) $request->input('bpjs', 0),
                (float) $request->input('tax', 0),
            ),
        ]);
    }

    public function calculateOvertime(OvertimeCalculatorRequest $request, ToolCalculationService $service): RedirectResponse
    {
        return back()->withInput()->with('tool_result', [
            'type' => 'overtime',
            'data' => $service->calculateOvertime(
                (float) $request->input('monthly_wage'),
                (float) $request->input('hours'),
                $request->string('day_type')->toString(),
            ),
        ]);
    }

    public function calculateTaxConsultantFee(
        ProfessionalServiceFeeRequest $request,
        ToolCalculationService $service,
    ): RedirectResponse {
        return back()->withInput()->with('tool_result', [
            'type' => 'tax-consultant-fee',
            'data' => $service->calculateProfessionalServiceFee(
                $request->filled('transaction_value') ? (float) $request->input('transaction_value') : null,
                $request->filled('company_revenue') ? (float) $request->input('company_revenue') : null,
                $request->string('basis_type')->toString(),
            ),
        ]);
    }

    public function calculateAccountingServiceFee(
        ProfessionalServiceFeeRequest $request,
        ToolCalculationService $service,
    ): RedirectResponse {
        return back()->withInput()->with('tool_result', [
            'type' => 'accounting-service-fee',
            'data' => $service->calculateProfessionalServiceFee(
                $request->filled('transaction_value') ? (float) $request->input('transaction_value') : null,
                $request->filled('company_revenue') ? (float) $request->input('company_revenue') : null,
                $request->string('basis_type')->toString(),
            ),
        ]);
    }

    public function calculateAuditFee(
        AuditFeeRequest $request,
        ToolCalculationService $service,
    ): RedirectResponse {
        return back()->withInput()->with('tool_result', [
            'type' => 'audit-fee',
            'data' => $service->calculateAuditFee(
                (float) $request->input('total_assets'),
                (float) $request->input('company_revenue'),
            ),
        ]);
    }

    public function calculateCvAts(
        CvAtsGeneratorRequest $request,
        ToolCalculationService $service,
    ): RedirectResponse {
        return back()->withInput()->with('tool_result', [
            'type' => 'cv-ats',
            'data' => $service->prepareCvAtsData($request->validated()),
        ]);
    }

    public function cvAtsPdf(
        CvAtsGeneratorRequest $request,
        ToolCalculationService $service,
        TemplateRenderService $templateRenderService,
    ) {
        $payload = $service->prepareCvAtsData($request->validated());

        return $templateRenderService
            ->cvPdf($payload)
            ->download('cv-ats-'.str($request->input('full_name'))->slug().'.pdf');
    }

    public function cvAtsWord(
        CvAtsGeneratorRequest $request,
        ToolCalculationService $service,
        TemplateRenderService $templateRenderService,
    ) {
        $payload = $service->prepareCvAtsData($request->validated());

        return $templateRenderService->downloadWord(
            'cv-ats-'.str($request->input('full_name'))->slug().'.doc',
            'CV ATS - '.$request->input('full_name'),
            $templateRenderService->cvWordHtml($payload),
        );
    }
}
