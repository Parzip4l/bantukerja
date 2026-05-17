<div style="margin:0 auto;max-width:820px;padding:28px;border:1px solid #cbd5e1;background:#fff;color:#0f172a;font-family:DejaVu Sans,sans-serif;">
    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Laporan kerja harian</div>
    <div style="margin-top:8px;font-size:24px;font-weight:700;">{{ $document['report_title'] }}</div>
    <div style="margin-top:12px;font-size:12px;line-height:1.9;color:#475569;">{{ $document['author_name'] }} | {{ $document['position'] }} | {{ $document['division'] }} | {{ $document['report_date_label'] }}</div>
    @if (($document['output_format'] ?? '') === 'whatsapp-chat')
        <div style="margin-top:18px;white-space:pre-line;font-size:12px;line-height:1.9;color:#475569;">{{ $document['whatsapp_text'] }}</div>
    @elseif (($document['output_format'] ?? '') === 'email')
        <div style="margin-top:18px;border:1px solid #e2e8f0;border-radius:18px;padding:16px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">Subject email</div>
            <div style="margin-top:8px;font-size:13px;font-weight:700;">{{ $document['email_subject'] }}</div>
            <div style="margin-top:16px;white-space:pre-line;font-size:12px;line-height:1.9;color:#475569;">{{ $document['email_body'] }}</div>
        </div>
    @else
        <div style="margin-top:18px;border:1px solid #e2e8f0;border-radius:18px;padding:16px;background:#f8fafc;">
            <div style="font-size:12px;font-weight:700;">Ringkasan tugas</div>
            <div style="margin-top:8px;font-size:12px;line-height:1.9;color:#475569;">
                Total tugas: {{ $document['summary']['total_tasks'] }} |
                Selesai: {{ $document['summary']['completed_tasks'] }} |
                Dalam proses: {{ $document['summary']['in_progress_tasks'] }} |
                Tertunda: {{ $document['summary']['pending_tasks'] }}
                @if ($document['summary']['average_progress'] !== null)
                    | Rata-rata progress: {{ $document['summary']['average_progress'] }}%
                @endif
            </div>
        </div>
        <div style="margin-top:18px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">Pekerjaan hari ini</div>
            <ol style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
                @foreach ($document['tasks'] as $task)
                    <li><strong style="color:#0f172a;">{{ $task['task_name'] }}</strong> - {{ $task['status_label'] }}@if ($task['progress'] !== null) ({{ $task['progress'] }}%)@endif. {{ $task['description'] }}</li>
                @endforeach
            </ol>
        </div>
        <div style="margin-top:18px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">Kendala</div>
            @if (count($document['issues']))
                <ul style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
                    @foreach ($document['issues'] as $issue)
                        <li>{{ $issue['issue'] }}@if ($issue['impact']) - Dampak: {{ $issue['impact'] }}@endif @if ($issue['temporary_action']) | Tindakan sementara: {{ $issue['temporary_action'] }}@endif</li>
                    @endforeach
                </ul>
            @else
                <div style="margin-top:8px;font-size:12px;line-height:1.9;color:#475569;">{{ $document['issue_fallback'] }}</div>
            @endif
        </div>
        <div style="margin-top:18px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">Rencana kerja berikutnya</div>
            @if (count($document['plans']))
                <ol style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
                    @foreach ($document['plans'] as $plan)
                        <li>{{ $plan['plan_task'] }} (Prioritas: {{ $plan['priority_label'] }})</li>
                    @endforeach
                </ol>
            @else
                <div style="margin-top:8px;font-size:12px;line-height:1.9;color:#475569;">{{ $document['plan_fallback'] }}</div>
            @endif
        </div>
        @if (! empty($document['notes']))
            <div style="margin-top:18px;">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">Catatan</div>
                <div style="margin-top:8px;font-size:12px;line-height:1.9;color:#475569;">{{ $document['notes'] }}</div>
            </div>
        @endif
    @endif
</div>
