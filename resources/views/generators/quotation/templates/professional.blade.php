@php
    $vendorLogoSource = ($renderMode ?? 'preview') === 'pdf'
        ? ($document['vendor_logo_pdf_path'] ?? null)
        : ($document['vendor_logo_url'] ?? null);
@endphp

<div style="margin:0 auto;max-width:840px;border:1px solid #cbd5e1;border-radius:20px;padding:30px;background:#fff;color:#0f172a;font-family:DejaVu Sans,sans-serif;">
    <div style="display:table;width:100%;margin-bottom:24px;">
        <div style="display:table-cell;width:56%;vertical-align:top;padding-right:16px;">
            @if (! empty($vendorLogoSource))
                <img src="{{ $vendorLogoSource }}" alt="{{ $document['vendor_name'] }}" style="max-height:48px;max-width:180px;margin-bottom:14px;">
            @endif
            <div style="font-size:22px;font-weight:700;">{{ $document['vendor_name'] }}</div>
            <div style="margin-top:8px;font-size:12px;line-height:1.8;color:#475569;">{{ $document['vendor_address'] }}</div>
            <div style="margin-top:6px;font-size:12px;color:#475569;">{{ $document['vendor_phone'] }} @if(!empty($document['vendor_email'])) | {{ $document['vendor_email'] }} @endif</div>
        </div>
        <div style="display:table-cell;width:44%;vertical-align:top;">
            <div style="border-radius:18px;background:#f8fafc;padding:16px;">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Informasi quotation</div>
                <div style="margin-top:10px;font-size:12px;line-height:1.9;color:#475569;">
                    <div>Nomor: {{ $document['quotation_number'] }}</div>
                    <div>Tanggal: {{ $document['quotation_date_label'] }}</div>
                    <div>Berlaku sampai: {{ $document['valid_until_label'] }}</div>
                    <div>Masa berlaku: {{ $document['validity_label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div style="display:table;width:100%;margin-bottom:22px;">
        <div style="display:table-cell;width:48%;vertical-align:top;padding-right:12px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Client</div>
            <div style="margin-top:8px;font-size:16px;font-weight:700;">{{ $document['client_name'] }}</div>
            @if (! empty($document['client_address']))
                <div style="margin-top:8px;font-size:12px;line-height:1.8;color:#475569;">{{ $document['client_address'] }}</div>
            @endif
        </div>
        <div style="display:table-cell;width:52%;vertical-align:top;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Judul penawaran</div>
            <div style="margin-top:8px;font-size:16px;font-weight:700;">{{ $document['quotation_title'] }}</div>
            <div style="margin-top:10px;font-size:12px;line-height:1.9;color:#475569;">{{ $document['project_description'] }}</div>
        </div>
    </div>
    @include('generators.quotation.templates.partials.table')
</div>
