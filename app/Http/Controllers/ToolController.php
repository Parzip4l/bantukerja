<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceGeneratorRequest;
use App\Http\Requests\LeaveLetterGeneratorRequest;
use App\Http\Requests\OvertimeCalculatorRequest;
use App\Http\Requests\SalaryCalculatorRequest;
use App\Http\Requests\ThrCalculatorRequest;
use App\Models\Category;
use App\Models\Tool;
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

    public function show(string $slug, SeoService $seoService): View
    {
        $tool = Tool::query()
            ->with(['category', 'faqs'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('tools.show', [
            'tool' => $tool,
            'seo' => $seoService->forModel($tool),
            'relatedPosts' => $tool->relatedPosts()->limit(4)->get(),
            'relatedTemplates' => $tool->relatedTemplates()->limit(4)->get(),
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

    public function calculateInvoice(InvoiceGeneratorRequest $request, ToolCalculationService $service): RedirectResponse
    {
        $summary = $service->calculateInvoiceTotal(
            $request->input('items', []),
            (float) $request->input('tax', 0),
            (float) $request->input('discount', 0),
        );

        return back()->withInput()->with('tool_result', [
            'type' => 'invoice',
            'data' => array_merge($request->validated(), $summary),
        ]);
    }

    public function invoicePdf(
        InvoiceGeneratorRequest $request,
        ToolCalculationService $service,
        TemplateRenderService $templateRenderService,
    ) {
        $summary = $service->calculateInvoiceTotal(
            $request->input('items', []),
            (float) $request->input('tax', 0),
            (float) $request->input('discount', 0),
        );

        return $templateRenderService
            ->invoicePdf(array_merge($request->validated(), $summary))
            ->download('invoice-'.$request->input('invoice_number').'.pdf');
    }

    public function calculateLeaveLetter(
        LeaveLetterGeneratorRequest $request,
        TemplateRenderService $templateRenderService,
    ): RedirectResponse {
        return back()->withInput()->with('tool_result', [
            'type' => 'leave-letter',
            'data' => [
                'payload' => $request->validated(),
                'content' => $templateRenderService->renderLeaveLetter($request->validated()),
            ],
        ]);
    }

    public function downloadLeaveLetter(
        LeaveLetterGeneratorRequest $request,
        TemplateRenderService $templateRenderService,
    ) {
        $content = $templateRenderService->renderLeaveLetter($request->validated());

        return $templateRenderService->downloadText('surat-izin-kerja.txt', $content);
    }
}
