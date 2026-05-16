<h1>{{ $full_name }}</h1>
<p><strong>{{ $professional_title }}</strong></p>
<p>{{ $email }} | {{ $phone }} | {{ $city }}</p>
@if (!empty($linkedin) || !empty($portfolio))
    <p>
        @if (!empty($linkedin)) LinkedIn: {{ $linkedin }} @endif
        @if (!empty($linkedin) && !empty($portfolio)) | @endif
        @if (!empty($portfolio)) Portfolio: {{ $portfolio }} @endif
    </p>
@endif

<h2>Ringkasan Profesional</h2>
<p>{{ $summary }}</p>

<h2>Keahlian Inti</h2>
<ul>
    @foreach ($skills_list as $skill)
        <li>{{ $skill }}</li>
    @endforeach
</ul>

<h2>Pengalaman Kerja</h2>
@foreach ($work_experiences_prepared as $experience)
    <h3>{{ $experience['job_title'] }} - {{ $experience['company'] }}</h3>
    <p>{{ $experience['period'] }} @if($experience['location']) | {{ $experience['location'] }} @endif</p>
    <ul>
        @foreach ($experience['description_points'] as $point)
            <li>{{ $point }}</li>
        @endforeach
    </ul>
@endforeach

<h2>Pendidikan</h2>
@foreach ($educations_prepared as $education)
    <h3>{{ $education['degree'] }} - {{ $education['institution'] }}</h3>
    <p>{{ $education['period'] }} @if($education['location']) | {{ $education['location'] }} @endif</p>
    @if($education['description'])
        <p>{{ $education['description'] }}</p>
    @endif
@endforeach

@if (count($certifications_list))
    <h2>Sertifikasi</h2>
    <ul>
        @foreach ($certifications_list as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
@endif

@if (count($achievements_list))
    <h2>Pencapaian</h2>
    <ul>
        @foreach ($achievements_list as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
@endif

@if (count($languages_list))
    <h2>Bahasa</h2>
    <ul>
        @foreach ($languages_list as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
@endif
