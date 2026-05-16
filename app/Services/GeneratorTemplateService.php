<?php

namespace App\Services;

use App\Models\GeneratorTemplate;

class GeneratorTemplateService
{
    public function getTemplatesFor(string $generatorType)
    {
        return GeneratorTemplate::query()
            ->active()
            ->forType($generatorType)
            ->free()
            ->ordered()
            ->get();
    }

    public function findTemplate(string $generatorType, ?string $templateSlug): GeneratorTemplate
    {
        if (filled($templateSlug)) {
            $template = GeneratorTemplate::query()
                ->active()
                ->forType($generatorType)
                ->where('slug', $templateSlug)
                ->first();

            if ($template) {
                return $this->ensureResolvable($generatorType, $template);
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
        $template = GeneratorTemplate::query()
            ->active()
            ->forType($generatorType)
            ->ordered()
            ->first();

        if ($template) {
            return $this->ensureResolvable($generatorType, $template);
        }

        return new GeneratorTemplate([
            'name' => 'Default',
            'slug' => $generatorType.'-default',
            'generator_type' => $generatorType,
            'description' => 'Template default',
            'view_path' => $this->fallbackViewPath($generatorType),
            'paper_size' => 'a4',
            'orientation' => 'portrait',
            'is_active' => true,
            'is_premium' => false,
            'sort_order' => 0,
            'settings' => [],
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
            default => 'generators.letter.templates.simple',
        };
    }
}
