<?php

namespace App\Services;

use App\Models\GeneratorTemplate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class GeneratorTemplateService
{
    public function getTemplatesFor(string $generatorType): Collection
    {
        if ($this->hasTemplatesTable()) {
            $templates = GeneratorTemplate::query()
                ->active()
                ->forType($generatorType)
                ->free()
                ->ordered()
                ->get();

            if ($templates->isNotEmpty()) {
                return $templates->map(fn (GeneratorTemplate $template) => $this->ensureResolvable($generatorType, $template));
            }
        }

        return $this->builtInTemplatesFor($generatorType);
    }

    public function findTemplate(string $generatorType, ?string $templateSlug): GeneratorTemplate
    {
        if (filled($templateSlug)) {
            if ($this->hasTemplatesTable()) {
                $template = GeneratorTemplate::query()
                    ->active()
                    ->forType($generatorType)
                    ->where('slug', $templateSlug)
                    ->first();

                if ($template) {
                    return $this->ensureResolvable($generatorType, $template);
                }
            }

            $builtInTemplate = $this->builtInTemplatesFor($generatorType)
                ->firstWhere('slug', $templateSlug);

            if ($builtInTemplate) {
                return $this->ensureResolvable($generatorType, $builtInTemplate);
            }
        }

        return $this->getDefaultTemplate($generatorType);
    }

    public function resolveViewPath(GeneratorTemplate $template): string
    {
        if (view()->exists($template->view_path)) {
            return $template->view_path;
        }

        return $this->fallbackViewPath($template->generator_type);
    }

    public function getDefaultTemplate(string $generatorType): GeneratorTemplate
    {
        if ($this->hasTemplatesTable()) {
            $template = GeneratorTemplate::query()
                ->active()
                ->forType($generatorType)
                ->ordered()
                ->first();

            if ($template) {
                return $this->ensureResolvable($generatorType, $template);
            }
        }

        return $this->builtInTemplatesFor($generatorType)->first()
            ?? $this->makeBuiltInTemplate($generatorType, [
                'name' => 'Default',
                'slug' => $generatorType.'-default',
                'description' => 'Template default',
                'view_path' => $this->fallbackViewPath($generatorType),
            ]);
    }

    protected function ensureResolvable(string $generatorType, GeneratorTemplate $template): GeneratorTemplate
    {
        $template->view_path = $this->resolveViewPath($template);

        if (! view()->exists($template->view_path)) {
            $template->view_path = $this->fallbackViewPath($generatorType);
        }

        return $template;
    }

    protected function fallbackViewPath(string $generatorType): string
    {
        return match ($generatorType) {
            'invoice' => 'generators.invoice.templates.classic',
            'letter' => 'generators.letter.templates.formal',
            'receipt' => 'generators.receipt.templates.classic',
            'minutes' => 'generators.minutes.templates.formal',
            'application-letter' => 'generators.application-letter.templates.formal',
            'quotation' => 'generators.quotation.templates.professional',
            'sop' => 'generators.sop.templates.standard',
            'job-description' => 'generators.job-description.templates.hr',
            'interview-simulation' => 'generators.interview-simulation.templates.standard',
            'interview-star' => 'generators.interview-star.templates.standard',
            'linkedin-profile' => 'generators.linkedin-profile.templates.standard',
            'jd-matcher' => 'generators.jd-matcher.templates.standard',
            'ats-cv-checker' => 'generators.ats-cv-checker.templates.standard',
            'daily-work-report' => 'generators.daily-work-report.templates.standard',
            default => 'generators.letter.templates.simple',
        };
    }

    protected function builtInTemplatesFor(string $generatorType): Collection
    {
        $definitions = match ($generatorType) {
            'invoice' => [
                ['name' => 'Classic', 'slug' => 'invoice-classic', 'description' => 'Tampilan invoice bersih dan mudah dibaca.', 'view_path' => 'generators.invoice.templates.classic'],
                ['name' => 'Modern', 'slug' => 'invoice-modern', 'description' => 'Layout modern dengan hierarki visual yang lebih tegas.', 'view_path' => 'generators.invoice.templates.modern'],
                ['name' => 'Professional', 'slug' => 'invoice-professional', 'description' => 'Gaya bisnis formal untuk proposal dan tagihan klien korporat.', 'view_path' => 'generators.invoice.templates.professional'],
            ],
            'letter' => [
                ['name' => 'Formal', 'slug' => 'letter-formal', 'description' => 'Format surat kerja formal yang aman untuk kebutuhan umum.', 'view_path' => 'generators.letter.templates.formal'],
                ['name' => 'Corporate', 'slug' => 'letter-corporate', 'description' => 'Gaya surat dengan nuansa perusahaan yang lebih rapi.', 'view_path' => 'generators.letter.templates.corporate'],
                ['name' => 'Simple', 'slug' => 'letter-simple', 'description' => 'Versi ringkas untuk kebutuhan surat yang cepat.', 'view_path' => 'generators.letter.templates.simple'],
            ],
            'receipt' => [
                ['name' => 'Classic', 'slug' => 'receipt-classic', 'description' => 'Kwitansi standar yang mudah dicetak.', 'view_path' => 'generators.receipt.templates.classic'],
                ['name' => 'Minimal', 'slug' => 'receipt-minimal', 'description' => 'Tampilan lebih ringan untuk bukti pembayaran singkat.', 'view_path' => 'generators.receipt.templates.minimal'],
            ],
            'minutes' => [
                ['name' => 'Formal', 'slug' => 'minutes-formal', 'description' => 'Cocok untuk berita acara serah terima atau rapat formal.', 'view_path' => 'generators.minutes.templates.formal'],
                ['name' => 'Professional', 'slug' => 'minutes-professional', 'description' => 'Dokumen berita acara dengan tata letak bisnis modern.', 'view_path' => 'generators.minutes.templates.professional'],
            ],
            'application-letter' => [
                ['name' => 'Formal', 'slug' => 'application-letter-formal', 'description' => 'Surat lamaran dengan struktur formal dan sopan.', 'view_path' => 'generators.application-letter.templates.formal'],
                ['name' => 'Professional', 'slug' => 'application-letter-professional', 'description' => 'Versi ringkas profesional untuk recruiter modern.', 'view_path' => 'generators.application-letter.templates.professional'],
                ['name' => 'Email', 'slug' => 'application-letter-email', 'description' => 'Format khusus untuk body email lamaran kerja.', 'view_path' => 'generators.application-letter.templates.email'],
            ],
            'quotation' => [
                ['name' => 'Simple', 'slug' => 'quotation-simple', 'description' => 'Quotation sederhana yang fokus ke informasi inti.', 'view_path' => 'generators.quotation.templates.simple', 'orientation' => 'portrait'],
                ['name' => 'Professional', 'slug' => 'quotation-professional', 'description' => 'Template penawaran harga formal untuk klien bisnis.', 'view_path' => 'generators.quotation.templates.professional', 'orientation' => 'portrait'],
                ['name' => 'Service/Freelancer', 'slug' => 'quotation-service', 'description' => 'Cocok untuk penawaran jasa freelance, desain, dan web.', 'view_path' => 'generators.quotation.templates.service', 'orientation' => 'portrait'],
            ],
            'sop' => [
                ['name' => 'Standard', 'slug' => 'sop-standard', 'description' => 'Format SOP profesional yang mudah dipahami tim operasional.', 'view_path' => 'generators.sop.templates.standard'],
            ],
            'job-description' => [
                ['name' => 'HR Formal', 'slug' => 'job-description-hr', 'description' => 'Format internal HR yang rapi dan lengkap.', 'view_path' => 'generators.job-description.templates.hr'],
                ['name' => 'Job Posting', 'slug' => 'job-description-posting', 'description' => 'Versi lebih komunikatif untuk publikasi lowongan kerja.', 'view_path' => 'generators.job-description.templates.posting'],
            ],
            'interview-simulation' => [
                ['name' => 'Standard', 'slug' => 'interview-simulation-standard', 'description' => 'Daftar pertanyaan interview dengan checklist persiapan.', 'view_path' => 'generators.interview-simulation.templates.standard'],
            ],
            'interview-star' => [
                ['name' => 'Standard', 'slug' => 'interview-star-standard', 'description' => 'Template jawaban STAR yang rapi dan siap dipelajari.', 'view_path' => 'generators.interview-star.templates.standard'],
            ],
            'linkedin-profile' => [
                ['name' => 'Standard', 'slug' => 'linkedin-profile-standard', 'description' => 'Headline dan About LinkedIn dalam format ringkas profesional.', 'view_path' => 'generators.linkedin-profile.templates.standard'],
            ],
            'jd-matcher' => [
                ['name' => 'Standard', 'slug' => 'jd-matcher-standard', 'description' => 'Analisis kecocokan CV singkat dengan lowongan kerja.', 'view_path' => 'generators.jd-matcher.templates.standard'],
            ],
            'ats-cv-checker' => [
                ['name' => 'Standard', 'slug' => 'ats-cv-checker-standard', 'description' => 'Ringkasan skor ATS CV checker sederhana.', 'view_path' => 'generators.ats-cv-checker.templates.standard'],
            ],
            'daily-work-report' => [
                ['name' => 'Standard', 'slug' => 'daily-work-report-standard', 'description' => 'Laporan kerja harian praktis untuk copy, print, atau PDF.', 'view_path' => 'generators.daily-work-report.templates.standard'],
            ],
            default => [],
        };

        return collect($definitions)
            ->map(fn (array $definition, int $index): GeneratorTemplate => $this->makeBuiltInTemplate($generatorType, array_merge($definition, [
                'sort_order' => $index,
            ])));
    }

    protected function makeBuiltInTemplate(string $generatorType, array $attributes): GeneratorTemplate
    {
        return new GeneratorTemplate([
            'name' => $attributes['name'],
            'slug' => $attributes['slug'],
            'generator_type' => $generatorType,
            'description' => $attributes['description'] ?? 'Template bawaan',
            'view_path' => $attributes['view_path'] ?? $this->fallbackViewPath($generatorType),
            'paper_size' => $attributes['paper_size'] ?? 'a4',
            'orientation' => $attributes['orientation'] ?? 'portrait',
            'is_active' => true,
            'is_premium' => false,
            'sort_order' => $attributes['sort_order'] ?? 0,
            'settings' => $attributes['settings'] ?? [],
        ]);
    }

    protected function hasTemplatesTable(): bool
    {
        static $exists;

        return $exists ??= Schema::hasTable('generator_templates');
    }
}
