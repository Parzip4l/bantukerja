<div style="margin:0 auto; max-width:760px; border:1px solid #cbd5e1; padding:28px 30px; font-family:DejaVu Sans, sans-serif; color:#0f172a;">
    <div style="display:flex; justify-content:space-between; gap:24px; align-items:flex-start;">
        <div>
            <div style="font-size:28px; font-weight:700; letter-spacing:0.08em;">KWITANSI</div>
            <div style="margin-top:8px; font-size:12px; color:#475569;">Bukti penerimaan pembayaran</div>
        </div>
        <div style="text-align:right; font-size:12px; color:#334155;">
            @if (! empty($document['receipt_number']))
                <div><strong>No:</strong> {{ $document['receipt_number'] }}</div>
            @endif
            <div style="margin-top:6px;"><strong>Tanggal:</strong> {{ $document['receipt_date_label'] ?? '-' }}</div>
            @if (! empty($document['city']))
                <div style="margin-top:6px;"><strong>Kota:</strong> {{ $document['city'] }}</div>
            @endif
        </div>
    </div>

    <div style="margin-top:28px; border:1px solid #e2e8f0; border-radius:18px; padding:20px;">
        <div style="font-size:12px; text-transform:uppercase; letter-spacing:0.08em; color:#64748b;">Sudah terima dari</div>
        <div style="margin-top:8px; font-size:22px; font-weight:700;">{{ $document['payer_name'] }}</div>

        <div style="margin-top:22px; font-size:12px; text-transform:uppercase; letter-spacing:0.08em; color:#64748b;">Uang sejumlah</div>
        <div style="margin-top:8px; font-size:28px; font-weight:700; color:#1d4ed8;">{{ $document['amount_label'] ?? '-' }}</div>

        <div style="margin-top:22px; font-size:12px; text-transform:uppercase; letter-spacing:0.08em; color:#64748b;">Untuk pembayaran</div>
        <div style="margin-top:10px; font-size:14px; line-height:1.8; color:#334155;">{{ $document['description'] }}</div>

        @if (! empty($document['payment_method']))
            <div style="margin-top:18px; font-size:13px; color:#334155;"><strong>Metode pembayaran:</strong> {{ $document['payment_method'] }}</div>
        @endif

        @if (! empty($document['notes']))
            <div style="margin-top:18px; font-size:13px; color:#334155;"><strong>Catatan:</strong> {{ $document['notes'] }}</div>
        @endif
    </div>

    <div style="margin-top:40px; display:flex; justify-content:flex-end;">
        <div style="width:220px; text-align:center;">
            <div style="font-size:13px; color:#475569;">Penerima,</div>
            <div style="height:72px;"></div>
            <div style="border-top:1px solid #94a3b8; padding-top:10px; font-size:14px; font-weight:700;">
                {{ $document['receiver_name'] ?? '................................' }}
            </div>
        </div>
    </div>
</div>
