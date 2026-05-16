<div style="margin: 0 auto; max-width: 780px; border: 1px solid #e2e8f0; padding: 26px; color: #111827; font-family: DejaVu Sans, sans-serif;">
    <div style="display: flex; justify-content: space-between; gap: 16px; margin-bottom: 26px;">
        <div>
            <div style="font-size: 28px; font-weight: 700;">Invoice</div>
            <div style="margin-top: 10px; font-size: 12px; color: #4b5563;">{{ $document['business_name'] }}</div>
        </div>
        <div style="text-align: right; font-size: 12px; color: #4b5563;">
            <div>No. {{ $document['invoice_number'] }}</div>
            <div style="margin-top: 6px;">{{ $document['invoice_date_label'] }}</div>
        </div>
    </div>

    <div style="margin-bottom: 20px; font-size: 12px; line-height: 1.8; color: #4b5563;">
        <strong style="color: #111827;">Untuk:</strong> {{ $document['customer_name'] }}<br>
        {{ $document['customer_address'] }}
    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead>
            <tr>
                <th style="border-bottom: 2px solid #111827; padding: 10px 6px; text-align: left;">Item</th>
                <th style="border-bottom: 2px solid #111827; padding: 10px 6px; text-align: left;">Qty</th>
                <th style="border-bottom: 2px solid #111827; padding: 10px 6px; text-align: left;">Harga</th>
                <th style="border-bottom: 2px solid #111827; padding: 10px 6px; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($document['items'] as $item)
                <tr>
                    <td style="border-bottom: 1px solid #e5e7eb; padding: 10px 6px;">{{ $item['name'] }}</td>
                    <td style="border-bottom: 1px solid #e5e7eb; padding: 10px 6px;">{{ $item['qty'] }}</td>
                    <td style="border-bottom: 1px solid #e5e7eb; padding: 10px 6px;">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td style="border-bottom: 1px solid #e5e7eb; padding: 10px 6px; text-align: right;">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; margin-left: auto; width: 250px; font-size: 12px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;"><span>Subtotal</span><strong>{{ $document['formatted_subtotal'] }}</strong></div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;"><span>Pajak</span><strong>{{ $document['formatted_tax_amount'] }}</strong></div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;"><span>Diskon</span><strong>{{ $document['formatted_discount_amount'] }}</strong></div>
        <div style="display: flex; justify-content: space-between; border-top: 1px solid #111827; padding-top: 10px; font-size: 14px;"><span>Total</span><strong>{{ $document['formatted_grand_total'] }}</strong></div>
    </div>
</div>
