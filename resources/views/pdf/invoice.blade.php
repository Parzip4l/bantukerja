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
        <h1>Invoice</h1>
        <p class="muted">{{ $business_name }}</p>
        <p class="muted">Pelanggan: {{ $customer_name }}</p>
        <p class="muted">No. {{ $invoice_number }} | {{ $invoice_date }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
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
</body>
</html>
