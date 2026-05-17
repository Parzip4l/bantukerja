<div style="margin:0 auto;max-width:820px;padding:28px;border:1px solid #cbd5e1;background:#fff;color:#0f172a;font-family:DejaVu Sans,sans-serif;">
    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Job description matcher</div>
    <div style="margin-top:8px;font-size:24px;font-weight:700;">{{ $document['target_position'] }}</div>
    <div style="margin-top:12px;display:inline-block;border-radius:999px;background:#e0f2fe;padding:6px 12px;font-size:12px;font-weight:700;color:#0c4a6e;">Match score {{ $document['score'] }}% • {{ $document['status_label'] }}</div>
    <div style="display:table;width:100%;margin-top:20px;">
        <div style="display:table-cell;width:50%;vertical-align:top;padding-right:10px;">
            <div style="border:1px solid #e2e8f0;border-radius:18px;padding:16px;">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">Keyword cocok</div>
                <div style="margin-top:8px;font-size:12px;line-height:1.9;">{{ implode(', ', $document['matched_keywords']) ?: '-' }}</div>
            </div>
        </div>
        <div style="display:table-cell;width:50%;vertical-align:top;padding-left:10px;">
            <div style="border:1px solid #e2e8f0;border-radius:18px;padding:16px;">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">Keyword belum muncul</div>
                <div style="margin-top:8px;font-size:12px;line-height:1.9;">{{ implode(', ', $document['missing_keywords']) ?: '-' }}</div>
            </div>
        </div>
    </div>
    <div style="margin-top:18px;">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">Saran optimasi</div>
        <ul style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
            @foreach ($document['optimization_suggestions'] as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>
</div>
