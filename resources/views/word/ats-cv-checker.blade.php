<h1>ATS CV Checker</h1>
<p><strong>Posisi target:</strong> {{ $document['target_position'] }}</p>
<p><strong>Skor CV:</strong> {{ $document['score'] }}/100 ({{ $document['label'] }})</p>
<p><strong>Jumlah kata:</strong> {{ $document['word_count'] }}</p>

<h2>Breakdown Skor</h2>
<ul>
    @foreach ($document['breakdown'] as $item)
        <li>{{ $item['label'] }}: {{ $item['score'] }}/{{ $item['max'] }} - {{ $item['note'] }}</li>
    @endforeach
</ul>

<h2>Yang Sudah Baik</h2>
<ul>
    @foreach ($document['good_points'] as $item)
        <li>{{ $item }}</li>
    @endforeach
</ul>

<h2>Yang Perlu Diperbaiki</h2>
<ul>
    @foreach ($document['improvements'] as $item)
        <li>{{ $item }}</li>
    @endforeach
</ul>

<h2>Keyword yang Direkomendasikan</h2>
<p>{{ implode(', ', $document['recommended_keywords']) ?: '-' }}</p>

<h2>Checklist ATS Friendly</h2>
<ul>
    @foreach ($document['ats_checklist'] as $item)
        <li>{{ $item }}</li>
    @endforeach
</ul>

<p><em>{{ $document['disclaimer'] }}</em></p>
