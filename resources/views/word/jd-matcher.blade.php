<h1>Job Description Matcher</h1>
<p><strong>Posisi target:</strong> {{ $document['target_position'] }}</p>
<p><strong>Kategori:</strong> {{ $document['category_label'] }}</p>
<p><strong>Match score:</strong> {{ $document['score'] }}% ({{ $document['status_label'] }})</p>

<h2>Keyword Cocok</h2>
<p>{{ implode(', ', $document['matched_keywords']) ?: '-' }}</p>

<h2>Keyword Belum Muncul</h2>
<p>{{ implode(', ', $document['missing_keywords']) ?: '-' }}</p>

<h2>Skill Gap</h2>
<p>{{ implode(', ', $document['skill_gap']) ?: '-' }}</p>

<h2>Breakdown</h2>
<ul>
    @foreach ($document['breakdown'] as $item)
        <li>{{ $item['label'] }}: {{ $item['score'] }}/{{ $item['max'] }}</li>
    @endforeach
</ul>

<h2>Saran Optimasi</h2>
<ul>
    @foreach ($document['optimization_suggestions'] as $item)
        <li>{{ $item }}</li>
    @endforeach
</ul>

<h2>Contoh Ringkasan Profil</h2>
<p>{{ $document['profile_summary_template'] }}</p>

<p><em>{{ $document['disclaimer'] }}</em></p>
