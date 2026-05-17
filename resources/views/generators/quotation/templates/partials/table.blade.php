<table style="width:100%;border-collapse:collapse;margin-top:24px;font-size:12px;">
    <thead style="background:#f8fafc;">
        <tr>
            <th style="padding:12px 8px;text-align:left;">Item</th>
            <th style="padding:12px 8px;text-align:left;">Qty</th>
            <th style="padding:12px 8px;text-align:left;">Satuan</th>
            <th style="padding:12px 8px;text-align:left;">Harga</th>
            <th style="padding:12px 8px;text-align:left;">Diskon</th>
            <th style="padding:12px 8px;text-align:right;">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($document['items'] as $item)
            <tr>
                <td style="border-bottom:1px solid #e2e8f0;padding:12px 8px;">
                    <div style="font-weight:700;">{{ $item['name'] }}</div>
                    @if ($item['description'])
                        <div style="margin-top:4px;color:#64748b;">{{ $item['description'] }}</div>
                    @endif
                </td>
                <td style="border-bottom:1px solid #e2e8f0;padding:12px 8px;">{{ $item['qty'] }}</td>
                <td style="border-bottom:1px solid #e2e8f0;padding:12px 8px;">{{ $item['unit'] ?: '-' }}</td>
                <td style="border-bottom:1px solid #e2e8f0;padding:12px 8px;">{{ $item['price_label'] }}</td>
                <td style="border-bottom:1px solid #e2e8f0;padding:12px 8px;">{{ $item['discount'] > 0 ? $item['discount_label'] : '-' }}</td>
                <td style="border-bottom:1px solid #e2e8f0;padding:12px 8px;text-align:right;">{{ $item['subtotal_label'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<div style="margin-top:24px;display:table;width:100%;">
    <div style="display:table-cell;width:56%;vertical-align:top;padding-right:14px;">
        @if (count($document['terms_lines']))
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Terms & Conditions</div>
            <ul style="margin:10px 0 0;padding-left:18px;color:#475569;font-size:12px;line-height:1.8;">
                @foreach ($document['terms_lines'] as $line)
                    <li>{{ $line }}</li>
                @endforeach
            </ul>
        @endif
        @if (count($document['notes_lines']))
            <div style="margin-top:16px;font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">Catatan</div>
            <ul style="margin:10px 0 0;padding-left:18px;color:#475569;font-size:12px;line-height:1.8;">
                @foreach ($document['notes_lines'] as $line)
                    <li>{{ $line }}</li>
                @endforeach
            </ul>
        @endif
        <div style="margin-top:16px;font-size:11px;color:#64748b;">Dokumen ini masih dapat disesuaikan kembali mengikuti negosiasi dan kebijakan internal masing-masing pihak.</div>
    </div>
    <div style="display:table-cell;width:44%;vertical-align:top;">
        <div style="border-radius:18px;background:#f8fafc;padding:16px;">
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:12px;"><span>Subtotal</span><strong>{{ $document['subtotal_label'] }}</strong></div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:12px;"><span>Pajak</span><strong>{{ $document['tax_amount_label'] }}</strong></div>
            <div style="display:flex;justify-content:space-between;margin-bottom:12px;font-size:12px;"><span>Biaya tambahan</span><strong>{{ $document['additional_fee_label'] }}</strong></div>
            <div style="display:flex;justify-content:space-between;border-top:1px solid #cbd5e1;padding-top:12px;font-size:15px;"><span>Total</span><strong>{{ $document['grand_total_label'] }}</strong></div>
        </div>
        <div style="margin-top:24px;text-align:right;font-size:12px;color:#475569;">
            <div>Hormat kami,</div>
            <div style="margin-top:56px;font-weight:700;color:#0f172a;">{{ $document['person_in_charge'] }}</div>
            <div>{{ $document['person_in_charge_title'] }}</div>
        </div>
    </div>
</div>
