<div style="margin:0 auto;max-width:820px;padding:28px;border:1px solid #cbd5e1;background:#fff;color:#0f172a;font-family:DejaVu Sans,sans-serif;">
    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Interview answer generator</div>
    <div style="margin-top:8px;font-size:24px;font-weight:700;">Jawaban Interview Metode STAR</div>
    <div style="margin-top:12px;font-size:12px;line-height:1.9;color:#475569;"><strong>Pertanyaan:</strong> {{ $document['question'] }}</div>
    @if (($document['output_format'] ?? 'keduanya') !== 'paragraf-interview')
        <div style="margin-top:18px;border:1px solid #e2e8f0;border-radius:18px;padding:16px;">
            @foreach ($document['star_sections'] as $label => $value)
                <div style="margin-bottom:12px;">
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">{{ $label }}</div>
                    <div style="margin-top:6px;font-size:12px;line-height:1.9;">{{ $value }}</div>
                </div>
            @endforeach
        </div>
    @endif
    @if (($document['output_format'] ?? 'keduanya') !== 'struktur-star')
        <div style="margin-top:18px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">Versi paragraf interview</div>
            <div style="margin-top:8px;font-size:12px;line-height:1.9;">{{ $document['paragraph_answer'] }}</div>
        </div>
    @endif
    <div style="margin-top:18px;border-top:1px solid #e2e8f0;padding-top:16px;">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">Tips penyampaian</div>
        <ul style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
            @foreach ($document['delivery_tips'] as $tip)
                <li>{{ $tip }}</li>
            @endforeach
        </ul>
    </div>
</div>
