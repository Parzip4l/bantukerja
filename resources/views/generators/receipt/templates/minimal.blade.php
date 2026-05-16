<div style="margin:0 auto; max-width:760px; border:1px solid #e2e8f0; padding:26px 28px; font-family:DejaVu Sans, sans-serif; color:#0f172a;">
    <div style="display:flex; justify-content:space-between; gap:18px; align-items:flex-end; border-bottom:2px solid #0f172a; padding-bottom:12px;">
        <div style="font-size:26px; font-weight:700;">Kwitansi</div>
        <div style="font-size:12px; color:#475569; text-align:right;">
            @if (! empty($document['receipt_number']))
                <div>{{ $document['receipt_number'] }}</div>
            @endif
            <div>{{ $document['receipt_date_label'] ?? '-' }}</div>
        </div>
    </div>

    <table style="width:100%; border-collapse:collapse; margin-top:24px; font-size:14px;">
        <tr>
            <td style="width:165px; padding:8px 0; color:#64748b;">Sudah terima dari</td>
            <td style="padding:8px 0; font-weight:600;">{{ $document['payer_name'] }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0; color:#64748b;">Jumlah pembayaran</td>
            <td style="padding:8px 0; font-size:24px; font-weight:700;">{{ $document['amount_label'] ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0; color:#64748b; vertical-align:top;">Untuk pembayaran</td>
            <td style="padding:8px 0; line-height:1.8;">{{ $document['description'] }}</td>
        </tr>
        @if (! empty($document['payment_method']))
            <tr>
                <td style="padding:8px 0; color:#64748b;">Metode</td>
                <td style="padding:8px 0;">{{ $document['payment_method'] }}</td>
            </tr>
        @endif
        @if (! empty($document['notes']))
            <tr>
                <td style="padding:8px 0; color:#64748b; vertical-align:top;">Catatan</td>
                <td style="padding:8px 0; line-height:1.8;">{{ $document['notes'] }}</td>
            </tr>
        @endif
    </table>

    <div style="margin-top:36px; display:flex; justify-content:space-between; gap:24px; font-size:13px; color:#475569;">
        <div>
            @if (! empty($document['city']))
                <div>{{ $document['city'] }}, {{ $document['receipt_date_label'] ?? '-' }}</div>
            @endif
        </div>
        <div style="width:220px; text-align:center;">
            <div>Penerima</div>
            <div style="height:72px;"></div>
            <div style="border-top:1px solid #94a3b8; padding-top:10px; font-weight:700; color:#0f172a;">
                {{ $document['receiver_name'] ?? '................................' }}
            </div>
        </div>
    </div>
</div>
