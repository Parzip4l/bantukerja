<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        body {
            margin: 0;
            background: #f8fafc;
            color: #0f172a;
            font-family: Arial, Helvetica, sans-serif;
        }
        .print-shell {
            max-width: 980px;
            margin: 0 auto;
            padding: 24px;
        }
        .print-toolbar {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
        }
        .print-toolbar button {
            border: 1px solid #cbd5e1;
            background: #ffffff;
            border-radius: 14px;
            height: 42px;
            padding: 0 18px;
            font-weight: 600;
            cursor: pointer;
        }
        @media print {
            body {
                background: #ffffff;
            }
            .print-toolbar {
                display: none;
            }
            .print-shell {
                max-width: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-shell">
        <div class="print-toolbar">
            <button type="button" onclick="window.print()">Print dokumen</button>
            <button type="button" onclick="window.close()">Tutup</button>
        </div>

        @include($templateView, ['document' => $document, 'template' => $template, 'renderMode' => $renderMode ?? 'print'])
    </div>
</body>
</html>
