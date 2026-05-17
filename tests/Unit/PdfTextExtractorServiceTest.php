<?php

namespace Tests\Unit;

use App\Services\PdfTextExtractorService;
use Tests\TestCase;

class PdfTextExtractorServiceTest extends TestCase
{
    public function test_it_can_extract_text_from_simple_text_pdf_binary(): void
    {
        $service = app(PdfTextExtractorService::class);

        $pdf = $this->sampleTextPdf([
            'Profil',
            'Ayu Maharani',
            'Digital Marketing Specialist',
            'SEO Google Ads Analytics',
            'Meningkatkan lead 40 persen',
        ]);

        $text = $service->extractFromBinary($pdf);

        $this->assertStringContainsString('Ayu Maharani', $text);
        $this->assertStringContainsString('Digital Marketing Specialist', $text);
        $this->assertStringContainsString('Meningkatkan lead 40 persen', $text);
    }

    protected function sampleTextPdf(array $lines): string
    {
        $content = collect($lines)
            ->map(fn (string $line): string => '(' . addslashes($line) . ') Tj')
            ->implode("\n");

        return "%PDF-1.4\n1 0 obj\n<< /Length 200 >>\nstream\nBT\n/F1 12 Tf\n72 720 Td\n{$content}\nET\nendstream\nendobj\ntrailer\n<<>>\n%%EOF";
    }
}
