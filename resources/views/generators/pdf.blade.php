<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $document['subject'] ?? $document['invoice_number'] ?? 'Dokumen' }}</title>
    <style>
        body {
            margin: 0;
            padding: 24px;
            background: #ffffff;
        }
    </style>
</head>
<body>
    @include($templateView, ['document' => $document, 'template' => $template, 'renderMode' => $renderMode ?? 'pdf'])
</body>
</html>
