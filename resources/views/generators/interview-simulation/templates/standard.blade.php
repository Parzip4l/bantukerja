<div style="margin:0 auto;max-width:820px;padding:28px;border:1px solid #cbd5e1;background:#fff;color:#0f172a;font-family:DejaVu Sans,sans-serif;">
    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Simulasi interview</div>
    <div style="margin-top:8px;font-size:24px;font-weight:700;">Pertanyaan Interview untuk {{ $document['position_applied'] }}</div>
    <div style="margin-top:10px;font-size:12px;line-height:1.9;color:#475569;">Kategori: {{ $document['position_category_label'] }} | Level: {{ $document['experience_level_label'] }} | Jenis: {{ $document['interview_type_label'] }}</div>
    <div style="margin-top:20px;">
        @foreach ($document['questions'] as $item)
            <div style="margin-bottom:16px;border:1px solid #e2e8f0;border-radius:18px;padding:14px;">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:.12em;color:#64748b;">{{ $item['category'] }}</div>
                <div style="margin-top:8px;font-size:13px;font-weight:700;">{{ $item['number'] }}. {{ $item['question'] }}</div>
                @if (! empty($item['tip']))
                    <div style="margin-top:8px;font-size:12px;line-height:1.8;color:#475569;"><strong>Tip:</strong> {{ $item['tip'] }}</div>
                @endif
            </div>
        @endforeach
    </div>
    <div style="margin-top:16px;border-top:1px solid #e2e8f0;padding-top:16px;">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Checklist persiapan</div>
        <ul style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
            @foreach ($document['preparation_checklist'] as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>
</div>
