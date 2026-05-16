<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 11px; line-height: 1.55; }
        h1 { font-size: 22px; margin-bottom: 4px; }
        h2 { font-size: 13px; margin: 22px 0 8px; text-transform: uppercase; letter-spacing: 0.06em; color: #334155; }
        h3 { font-size: 11px; margin: 12px 0 4px; }
        p { margin: 0 0 8px; }
        ul { margin: 0; padding-left: 18px; }
        .meta { color: #475569; margin-bottom: 14px; }
        .section { margin-top: 14px; }
    </style>
</head>
<body>
    <h1>{{ $full_name }}</h1>
    <p><strong>{{ $professional_title }}</strong></p>
    <p class="meta">{{ $email }} | {{ $phone }} | {{ $city }}</p>
    @if (!empty($linkedin) || !empty($portfolio))
        <p class="meta">
            @if (!empty($linkedin)) LinkedIn: {{ $linkedin }} @endif
            @if (!empty($linkedin) && !empty($portfolio)) | @endif
            @if (!empty($portfolio)) Portfolio: {{ $portfolio }} @endif
        </p>
    @endif

    <div class="section">
        <h2>Ringkasan Profesional</h2>
        <p>{{ $summary }}</p>
    </div>

    <div class="section">
        <h2>Keahlian Inti</h2>
        <p>{{ implode(' | ', $skills_list) }}</p>
    </div>

    <div class="section">
        <h2>Pengalaman Kerja</h2>
        @foreach ($work_experiences_prepared as $experience)
            <h3>{{ $experience['job_title'] }} - {{ $experience['company'] }}</h3>
            <p class="meta">{{ $experience['period'] }} @if($experience['location']) | {{ $experience['location'] }} @endif</p>
            <ul>
                @foreach ($experience['description_points'] as $point)
                    <li>{{ $point }}</li>
                @endforeach
            </ul>
        @endforeach
    </div>

    <div class="section">
        <h2>Pendidikan</h2>
        @foreach ($educations_prepared as $education)
            <h3>{{ $education['degree'] }} - {{ $education['institution'] }}</h3>
            <p class="meta">{{ $education['period'] }} @if($education['location']) | {{ $education['location'] }} @endif</p>
            @if($education['description'])
                <p>{{ $education['description'] }}</p>
            @endif
        @endforeach
    </div>

    @if (count($certifications_list))
        <div class="section">
            <h2>Sertifikasi</h2>
            <ul>
                @foreach ($certifications_list as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (count($achievements_list))
        <div class="section">
            <h2>Pencapaian</h2>
            <ul>
                @foreach ($achievements_list as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (count($languages_list))
        <div class="section">
            <h2>Bahasa</h2>
            <p>{{ implode(' | ', $languages_list) }}</p>
        </div>
    @endif
</body>
</html>
