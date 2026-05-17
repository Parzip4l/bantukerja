<div style="margin:0 auto;max-width:820px;padding:28px;border:1px solid #dbe4f0;border-radius:24px;background:linear-gradient(180deg,#fff,#f8fafc);color:#0f172a;font-family:DejaVu Sans,sans-serif;">
    <div style="display:flex;justify-content:space-between;gap:20px;align-items:flex-start;">
        <div>
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.18em;color:#64748b;">Penawaran jasa</div>
            <div style="margin-top:8px;font-size:24px;font-weight:700;">{{ $document['quotation_title'] }}</div>
            <div style="margin-top:12px;font-size:12px;line-height:1.9;color:#475569;">{{ $document['project_description'] }}</div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:14px;font-weight:700;">{{ $document['vendor_name'] }}</div>
            <div style="margin-top:6px;font-size:12px;color:#475569;">{{ $document['quotation_number'] }}</div>
            <div style="margin-top:4px;font-size:12px;color:#475569;">{{ $document['quotation_date_label'] }}</div>
        </div>
    </div>
    <div style="margin-top:20px;border-radius:18px;background:#fff;padding:18px;border:1px solid #e2e8f0;">
        <div style="font-size:12px;color:#475569;">Disiapkan untuk <strong style="color:#0f172a;">{{ $document['client_name'] }}</strong></div>
        <div style="margin-top:6px;font-size:12px;color:#475569;">Termin pembayaran: {{ $document['payment_terms_label'] }}</div>
        <div style="margin-top:6px;font-size:12px;color:#475569;">Masa berlaku penawaran: {{ $document['validity_label'] }}</div>
    </div>
    @include('generators.quotation.templates.partials.table')
</div>
