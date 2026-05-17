<div style="margin:0 auto;max-width:820px;padding:28px;border:1px solid #dbe4f0;background:#fff;color:#0f172a;font-family:DejaVu Sans,sans-serif;">
    <div style="display:flex;justify-content:space-between;gap:20px;align-items:flex-start;">
        <div>
            <div style="font-size:22px;font-weight:700;">{{ $document['vendor_name'] }}</div>
            <div style="margin-top:8px;font-size:12px;line-height:1.8;color:#475569;">{{ $document['vendor_address'] }}</div>
            <div style="margin-top:6px;font-size:12px;color:#475569;">{{ $document['vendor_phone'] }} @if(!empty($document['vendor_email'])) | {{ $document['vendor_email'] }} @endif</div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Quotation</div>
            <div style="margin-top:8px;font-size:12px;color:#475569;">{{ $document['quotation_number'] }}</div>
            <div style="margin-top:4px;font-size:12px;color:#475569;">{{ $document['quotation_date_label'] }}</div>
        </div>
    </div>
    <div style="margin-top:20px;border-top:1px solid #e2e8f0;padding-top:18px;">
        <div style="font-size:16px;font-weight:700;">{{ $document['quotation_title'] }}</div>
        <div style="margin-top:10px;font-size:12px;line-height:1.9;color:#475569;">Ditujukan kepada {{ $document['client_name'] }}@if(!empty($document['client_address']))<br>{{ $document['client_address'] }}@endif</div>
        <div style="margin-top:14px;font-size:12px;line-height:1.9;color:#1e293b;">{{ $document['project_description'] }}</div>
    </div>
    @include('generators.quotation.templates.partials.table')
</div>
