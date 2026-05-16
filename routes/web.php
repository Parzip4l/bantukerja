<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{slug}', [ToolController::class, 'show'])->name('tools.show');
Route::post('/tools/kalkulator-thr/calculate', [ToolController::class, 'calculateThr'])->middleware('throttle:20,1')->name('tools.thr.calculate');
Route::post('/tools/kalkulator-gaji-bersih/calculate', [ToolController::class, 'calculateSalary'])->middleware('throttle:20,1')->name('tools.salary.calculate');
Route::post('/tools/kalkulator-lembur/calculate', [ToolController::class, 'calculateOvertime'])->middleware('throttle:20,1')->name('tools.overtime.calculate');
Route::post('/tools/kalkulator-fee-konsultan-pajak/calculate', [ToolController::class, 'calculateTaxConsultantFee'])->middleware('throttle:20,1')->name('tools.tax-consultant-fee.calculate');
Route::post('/tools/kalkulator-fee-accounting-service/calculate', [ToolController::class, 'calculateAccountingServiceFee'])->middleware('throttle:20,1')->name('tools.accounting-service-fee.calculate');
Route::post('/tools/kalkulator-fee-audit/calculate', [ToolController::class, 'calculateAuditFee'])->middleware('throttle:20,1')->name('tools.audit-fee.calculate');
Route::post('/tools/generator-invoice/calculate', [ToolController::class, 'calculateInvoice'])->middleware('throttle:20,1')->name('tools.invoice.calculate');
Route::post('/tools/generator-invoice/pdf', [ToolController::class, 'invoicePdf'])->middleware('throttle:10,1')->name('tools.invoice.pdf');
Route::post('/tools/generator-cv-ats/calculate', [ToolController::class, 'calculateCvAts'])->middleware('throttle:20,1')->name('tools.cv-ats.calculate');
Route::post('/tools/generator-cv-ats/pdf', [ToolController::class, 'cvAtsPdf'])->middleware('throttle:10,1')->name('tools.cv-ats.pdf');
Route::post('/tools/generator-cv-ats/word', [ToolController::class, 'cvAtsWord'])->middleware('throttle:10,1')->name('tools.cv-ats.word');
Route::post('/tools/generator-surat-izin/calculate', [ToolController::class, 'calculateLeaveLetter'])->middleware('throttle:20,1')->name('tools.leave-letter.calculate');
Route::post('/tools/generator-surat-izin/download', [ToolController::class, 'downloadLeaveLetter'])->middleware('throttle:10,1')->name('tools.leave-letter.download');

Route::get('/template', [TemplateController::class, 'index'])->name('templates.index');
Route::get('/template/{slug}', [TemplateController::class, 'show'])->name('templates.show');
Route::get('/template/{slug}/download.txt', [TemplateController::class, 'download'])->name('templates.download');
Route::get('/template/{slug}/download.doc', [TemplateController::class, 'downloadWord'])->name('templates.download-word');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/about', [PageController::class, 'show'])->defaults('slug', 'about')->name('pages.about');
Route::get('/contact', [PageController::class, 'show'])->defaults('slug', 'contact')->name('pages.contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,10')->name('contact.store');
Route::get('/privacy-policy', [PageController::class, 'show'])->defaults('slug', 'privacy-policy')->name('pages.privacy-policy');
Route::get('/terms', [PageController::class, 'show'])->defaults('slug', 'terms')->name('pages.terms');
Route::get('/disclaimer', [PageController::class, 'show'])->defaults('slug', 'disclaimer')->name('pages.disclaimer');
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');
