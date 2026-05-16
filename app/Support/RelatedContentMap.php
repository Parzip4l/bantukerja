<?php

namespace App\Support;

class RelatedContentMap
{
    /**
     * @return array{blog: string[], tool: string[], template: string[]}
     */
    public static function for(?string $categorySlug): array
    {
        $map = [
            'payroll-hr' => [
                'blog' => ['payroll-hr'],
                'tool' => ['kalkulator-kerja'],
                'template' => ['karier-lamaran'],
            ],
            'dokumen-kerja' => [
                'blog' => ['dokumen-kerja'],
                'tool' => ['generator-dokumen'],
                'template' => ['surat-kerja', 'karier-lamaran'],
            ],
            'administrasi-bisnis' => [
                'blog' => ['administrasi-bisnis'],
                'tool' => ['generator-dokumen'],
                'template' => ['bisnis-legal-ringan'],
            ],
            'kalkulator-kerja' => [
                'blog' => ['payroll-hr'],
                'tool' => ['kalkulator-kerja'],
                'template' => ['karier-lamaran'],
            ],
            'generator-dokumen' => [
                'blog' => ['dokumen-kerja', 'administrasi-bisnis'],
                'tool' => ['generator-dokumen'],
                'template' => ['surat-kerja', 'karier-lamaran', 'bisnis-legal-ringan'],
            ],
            'surat-kerja' => [
                'blog' => ['dokumen-kerja'],
                'tool' => ['generator-dokumen'],
                'template' => ['surat-kerja', 'karier-lamaran'],
            ],
            'karier-lamaran' => [
                'blog' => ['dokumen-kerja', 'payroll-hr'],
                'tool' => ['generator-dokumen', 'kalkulator-kerja'],
                'template' => ['karier-lamaran', 'surat-kerja'],
            ],
            'bisnis-legal-ringan' => [
                'blog' => ['administrasi-bisnis', 'dokumen-kerja'],
                'tool' => ['generator-dokumen'],
                'template' => ['bisnis-legal-ringan', 'surat-kerja'],
            ],
        ];

        return $map[$categorySlug] ?? [
            'blog' => [],
            'tool' => [],
            'template' => [],
        ];
    }
}
