<div style="margin: 0 auto; max-width: 820px; border: 1px solid #cbd5e1; border-radius: 20px; padding: 30px; background: #ffffff; color: #0f172a; font-family: DejaVu Sans, sans-serif;">
    @include('generators.shared.partials.document-header', [
        'title' => 'Invoice Profesional',
        'subtitle' => 'Dokumen penagihan resmi untuk kebutuhan bisnis',
    ])

    <div style="display: table; width: 100%; margin-bottom: 24px;">
        <div style="display: table-cell; width: 50%; vertical-align: top; padding-right: 12px;">
            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.16em; color: #64748b;">Informasi bisnis</div>
            <div style="margin-top: 8px; font-size: 18px; font-weight: 700;">{{ $document['business_name'] }}</div>
            <div style="margin-top: 8px; font-size: 12px; line-height: 1.8; color: #475569;">{{ $document['business_address'] }}</div>
        </div>
        <div style="display: table-cell; width: 50%; vertical-align: top;">
            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.16em; color: #64748b;">Informasi invoice</div>
            <div style="margin-top: 8px; font-size: 12px; line-height: 1.8; color: #475569;">
                <div>No. Invoice: {{ $document['invoice_number'] }}</div>
                <div>Tanggal: {{ $document['invoice_date_label'] }}</div>
                <div>Jatuh tempo: {{ $document['due_date_label'] ?? '-' }}</div>
                <div>PO / Ref: {{ $document['po_number'] ?: '-' }}</div>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 22px; border-radius: 18px; border: 1px solid #e2e8f0; padding: 16px;">
        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.16em; color: #64748b;">Pelanggan</div>
        <div style="margin-top: 8px; font-size: 16px; font-weight: 700;">{{ $document['customer_name'] }}</div>
        @if (! empty($document['customer_company']))
            <div style="margin-top: 4px; font-size: 13px; color: #475569;">{{ $document['customer_company'] }}</div>
        @endif
        <div style="margin-top: 8px; font-size: 12px; line-height: 1.8; color: #475569;">{{ $document['customer_address'] }}</div>
    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead style="background: #f8fafc;">
            <tr>
                <th style="padding: 12px 8px; text-align: left;">Item / layanan</th>
                <th style="padding: 12px 8px; text-align: left;">Unit</th>
                <th style="padding: 12px 8px; text-align: left;">Qty</th>
                <th style="padding: 12px 8px; text-align: left;">Harga</th>
                <th style="padding: 12px 8px; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($document['items'] as $item)
                <tr>
                    <td style="border-bottom: 1px solid #e2e8f0; padding: 12px 8px;">
                        <div style="font-weight: 700;">{{ $item['name'] }}</div>
                        @if ($item['description'])
                            <div style="margin-top: 4px; color: #64748b;">{{ $item['description'] }}</div>
                        @endif
                    </td>
                    <td style="border-bottom: 1px solid #e2e8f0; padding: 12px 8px;">{{ $item['unit'] ?: '-' }}</td>
                    <td style="border-bottom: 1px solid #e2e8f0; padding: 12px 8px;">{{ $item['qty'] }}</td>
                    <td style="border-bottom: 1px solid #e2e8f0; padding: 12px 8px;">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td style="border-bottom: 1px solid #e2e8f0; padding: 12px 8px; text-align: right;">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 24px; display: table; width: 100%;">
        <div style="display: table-cell; width: 54%; vertical-align: top; padding-right: 14px;">
            @if (! empty($document['bank_name']) || ! empty($document['bank_account_number']))
                <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.16em; color: #64748b;">Informasi pembayaran</div>
                <div style="margin-top: 8px; font-size: 12px; line-height: 1.8; color: #475569;">
                    @if (! empty($document['bank_name']))<div>Bank: {{ $document['bank_name'] }}</div>@endif
                    @if (! empty($document['bank_account_name']))<div>Atas nama: {{ $document['bank_account_name'] }}</div>@endif
                    @if (! empty($document['bank_account_number']))<div>No. Rekening: {{ $document['bank_account_number'] }}</div>@endif
                </div>
            @endif
        </div>
        <div style="display: table-cell; width: 46%; vertical-align: top;">
            <div style="border-radius: 18px; background: #f8fafc; padding: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 12px;"><span>Subtotal</span><strong>{{ $document['formatted_subtotal'] }}</strong></div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 12px;"><span>Pajak</span><strong>{{ $document['formatted_tax_amount'] }}</strong></div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 12px;"><span>Diskon</span><strong>{{ $document['formatted_discount_amount'] }}</strong></div>
                <div style="display: flex; justify-content: space-between; border-top: 1px solid #cbd5e1; padding-top: 12px; font-size: 15px;"><span>Total</span><strong>{{ $document['formatted_grand_total'] }}</strong></div>
            </div>
        </div>
    </div>

    @include('generators.shared.partials.document-footer')
</div>
