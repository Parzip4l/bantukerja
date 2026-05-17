<div style="margin:0 auto;max-width:760px;padding:30px;border:1px solid #dbe4f0;border-radius:20px;background:#fff;color:#0f172a;font-family:DejaVu Sans,sans-serif;">
    <div style="display:table;width:100%;margin-bottom:28px;">
        <div style="display:table-cell;width:68%;vertical-align:top;">
            <div style="font-size:22px;font-weight:700;">{{ $document['full_name'] }}</div>
            <div style="margin-top:8px;font-size:12px;color:#475569;">{{ $document['city'] }} | {{ $document['email'] }} | {{ $document['phone'] }}</div>
        </div>
        <div style="display:table-cell;width:32%;vertical-align:top;text-align:right;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Surat Lamaran</div>
            <div style="margin-top:8px;font-size:12px;color:#475569;">{{ $document['date_label'] }}</div>
        </div>
    </div>
    <div style="border:1px solid #e2e8f0;border-radius:18px;padding:16px;margin-bottom:22px;">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Tujuan</div>
        <div style="margin-top:8px;font-size:16px;font-weight:700;">{{ $document['company_name'] }}</div>
        <div style="margin-top:4px;font-size:12px;color:#475569;">{{ $document['greeting'] }}</div>
        <div style="margin-top:10px;font-size:12px;color:#475569;">Posisi yang dilamar: {{ $document['position_applied'] }}</div>
    </div>
    <div style="font-size:12px;line-height:2;color:#1e293b;">
        <p style="margin:0 0 16px;">{{ $document['opening_paragraph'] }}</p>
        <p style="margin:0 0 16px;">{{ $document['profile_paragraph'] }}</p>
        <p style="margin:0 0 16px;">{{ $document['relevance_paragraph'] }}</p>
        <p style="margin:0;">{{ $document['closing_paragraph'] }}</p>
    </div>
    <div style="margin-top:24px;border-top:1px solid #e2e8f0;padding-top:16px;font-size:12px;color:#475569;">
        <div style="font-weight:700;color:#0f172a;">Skill utama</div>
        <div style="margin-top:8px;">{{ implode(' • ', $document['skills_list']) }}</div>
    </div>
</div>
