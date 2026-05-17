<div style="margin:0 auto;max-width:760px;min-height:980px;padding:34px;border:1px solid #e2e8f0;background:#fff;color:#0f172a;font-family:DejaVu Sans,sans-serif;">
    <div style="text-align:right;font-size:12px;color:#475569;">{{ $document['city'] }}, {{ $document['date_label'] }}</div>
    <div style="margin-top:28px;font-size:12px;line-height:1.9;">
        {{ $document['greeting'] }}<br>
        {{ $document['company_name'] }}
    </div>
    <div style="margin-top:22px;font-size:12px;line-height:2;">Dengan hormat,</div>
    <div style="margin-top:14px;font-size:12px;line-height:2.05;text-align:justify;">
        <p style="margin:0 0 16px;">{{ $document['opening_paragraph'] }}</p>
        <p style="margin:0 0 16px;">{{ $document['profile_paragraph'] }}</p>
        <p style="margin:0 0 16px;">{{ $document['relevance_paragraph'] }}</p>
        <p style="margin:0;">{{ $document['closing_paragraph'] }}</p>
    </div>
    <div style="margin-top:32px;font-size:12px;line-height:2;">Hormat saya,</div>
    <div style="margin-top:68px;font-size:12px;line-height:1.9;">
        <div style="font-weight:700;">{{ $document['full_name'] }}</div>
        <div>{{ $document['email'] }}</div>
        <div>{{ $document['phone'] }}</div>
    </div>
</div>
