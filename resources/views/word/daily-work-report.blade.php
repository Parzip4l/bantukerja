<h1>{{ $document['report_title'] }}</h1>
<p><strong>Nama:</strong> {{ $document['author_name'] }}</p>
<p><strong>Posisi:</strong> {{ $document['position'] }}</p>
<p><strong>Divisi:</strong> {{ $document['division'] }}</p>
<p><strong>Tanggal:</strong> {{ $document['report_date_label'] }}</p>

<h2>Ringkasan Tugas</h2>
<ul>
    <li>Total tugas: {{ $document['summary']['total_tasks'] }}</li>
    <li>Selesai: {{ $document['summary']['completed_tasks'] }}</li>
    <li>Dalam proses: {{ $document['summary']['in_progress_tasks'] }}</li>
    <li>Tertunda: {{ $document['summary']['pending_tasks'] }}</li>
    @if ($document['summary']['average_progress'] !== null)
        <li>Rata-rata progress: {{ $document['summary']['average_progress'] }}%</li>
    @endif
</ul>

<h2>Pekerjaan Hari Ini</h2>
<ol>
    @foreach ($document['tasks'] as $task)
        <li>
            <strong>{{ $task['task_name'] }}</strong> - {{ $task['status_label'] }}
            @if ($task['progress'] !== null)
                ({{ $task['progress'] }}%)
            @endif
            <br>
            {{ $task['description'] }}
            @if ($task['output'])
                <br><em>Output:</em> {{ $task['output'] }}
            @endif
        </li>
    @endforeach
</ol>

<h2>Kendala</h2>
@if (count($document['issues']))
    <ul>
        @foreach ($document['issues'] as $issue)
            <li>{{ $issue['issue'] }} @if($issue['impact']) - Dampak: {{ $issue['impact'] }} @endif @if($issue['temporary_action']) | Tindakan sementara: {{ $issue['temporary_action'] }} @endif</li>
        @endforeach
    </ul>
@else
    <p>{{ $document['issue_fallback'] }}</p>
@endif

<h2>Rencana Kerja Berikutnya</h2>
@if (count($document['plans']))
    <ol>
        @foreach ($document['plans'] as $plan)
            <li>{{ $plan['plan_task'] }} (Prioritas: {{ $plan['priority_label'] }})</li>
        @endforeach
    </ol>
@else
    <p>{{ $document['plan_fallback'] }}</p>
@endif

@if (! empty($document['notes']))
    <h2>Catatan</h2>
    <p>{!! nl2br(e($document['notes'])) !!}</p>
@endif
