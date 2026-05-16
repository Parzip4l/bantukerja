<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TemplateRenderService
{
    public function renderLeaveLetter(array $data): string
    {
        return implode("\n", [
            'SURAT IZIN KERJA',
            '',
            'Yang bertanda tangan di bawah ini:',
            'Nama: '.$data['name'],
            'Jabatan: '.$data['position'],
            'Perusahaan: '.$data['company'],
            '',
            'Dengan ini mengajukan izin kerja pada tanggal '.$data['leave_date'].' karena '.$data['reason'].'.',
            '',
            'Demikian surat izin ini dibuat dengan sebenarnya untuk digunakan sebagaimana mestinya.',
            '',
            $data['company'].', '.$data['leave_date'],
            '',
            $data['name'],
        ]);
    }

    public function downloadText(string $filename, string $content): StreamedResponse
    {
        return response()->streamDownload(function () use ($content): void {
            echo $content;
        }, $filename, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    public function invoicePdf(array $payload)
    {
        return Pdf::loadView('pdf.invoice', $payload)->setPaper('a4');
    }
}
