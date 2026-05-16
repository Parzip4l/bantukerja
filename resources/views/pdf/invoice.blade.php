<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 12px; }
        .header, .row { width: 100%; }
        .muted { color: #475569; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border-bottom: 1px solid #e2e8f0; padding: 10px 0; text-align: left; }
        .totals { margin-top: 24px; width: 280px; margin-left: auto; }
        .totals td { padding: 5px 0; }
    </style>
</head>
<body>
    <div class="header">
        @if (!empty($business_logo_pdf_path) && file_exists($business_logo_pdf_path))
            <p><img src="{{ $business_logo_pdf_path }}" alt="{{ $business_name }}" style="max-height: 70px; margin-bottom: 12px;"></p>
        @endif
        <h1>Invoice</h1>
        <p class="muted">{{ $business_name }}</p>
        <p class="muted">{{ $business_address }}</p>
        <p class="muted">{{ $business_phone }} @if(!empty($business_email)) | {{ $business_email }} @endif</p>
        @if (!empty($business_website) || !empty($business_npwp))
            <p class="muted">
                @if (!empty($business_website)) {{ $business_website }} @endif
                @if (!empty($business_website) && !empty($business_npwp)) | @endif
                @if (!empty($business_npwp)) NPWP: {{ $business_npwp }} @endif
            </p>
        @endif
        <p class="muted" style="margin-top: 16px;">Pelanggan: {{ $customer_name }} @if(!empty($customer_company)) ({{ $customer_company }}) @endif</p>
        <p class="muted">{{ $customer_address }}</p>
        <p class="muted">
            @if (!empty($customer_phone)) {{ $customer_phone }} @endif
            @if (!empty($customer_phone) && !empty($customer_email)) | @endif
            @if (!empty($customer_email)) {{ $customer_email }} @endif
        </p>
        <p class="muted">No. {{ $invoice_number }} | Tanggal {{ $invoice_date }} @if(!empty($due_date)) | Jatuh tempo {{ $due_date }} @endif</p>
        @if (!empty($po_number) || !empty($payment_terms))
            <p class="muted">
                @if (!empty($po_number)) Ref: {{ $po_number }} @endif
                @if (!empty($po_number) && !empty($payment_terms)) | @endif
                @if (!empty($payment_terms)) Syarat bayar: {{ $payment_terms }} @endif
            </p>
        @endif
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Unit</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>
                        <strong>{{ $item['name'] }}</strong>
                        @if (!empty($item['description']))
                            <div class="muted" style="font-size: 10px; margin-top: 4px;">{{ $item['description'] }}</div>
                        @endif
                    </td>
                    <td>{{ $item['unit'] ?: '-' }}</td>
                    <td>{{ $item['qty'] }}</td>
                    <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Subtotal</td><td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td></tr>
        <tr><td>Pajak</td><td>Rp {{ number_format($tax_amount, 0, ',', '.') }}</td></tr>
        <tr><td>Diskon</td><td>Rp {{ number_format($discount_amount, 0, ',', '.') }}</td></tr>
        <tr><td><strong>Total</strong></td><td><strong>Rp {{ number_format($grand_total, 0, ',', '.') }}</strong></td></tr>
    </table>

    @if (!empty($bank_name) || !empty($bank_account_name) || !empty($bank_account_number) || !empty($notes))
        <div style="margin-top: 28px;">
            @if (!empty($bank_name) || !empty($bank_account_name) || !empty($bank_account_number))
                <h3>Informasi Pembayaran</h3>
                @if (!empty($bank_name)) <p>Bank: {{ $bank_name }}</p> @endif
                @if (!empty($bank_account_name)) <p>Atas nama: {{ $bank_account_name }}</p> @endif
                @if (!empty($bank_account_number)) <p>No. rekening: {{ $bank_account_number }}</p> @endif
            @endif

            @if (!empty($notes))
                <h3 style="margin-top: 16px;">Catatan</h3>
                <p>{{ $notes }}</p>
            @endif
        </div>
    @endif
</body>
</html>
