<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; direction: rtl; font-size: 11px; color: #111827; padding: 20px; }
        .header { border-bottom: 3px solid #4f46e5; padding-bottom: 15px; margin-bottom: 20px; }
        .header table { width: 100%; }
        .header td { vertical-align: top; }
        .seller-name { font-size: 18px; font-weight: bold; color: #111827; }
        .seller-meta { font-size: 10px; color: #6b7280; margin-top: 3px; }
        .invoice-title { font-size: 24px; font-weight: bold; color: #4f46e5; text-align: left; }
        .invoice-number { font-size: 12px; color: #111827; text-align: left; margin-top: 4px; font-family: monospace; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-weight: bold; font-size: 10px; margin-top: 5px; }
        .badge-cancelled { background: #fee2e2; color: #dc2626; }
        .meta { margin-bottom: 20px; }
        .meta table { width: 100%; }
        .meta td { vertical-align: top; padding: 0 8px; }
        .label { font-size: 9px; text-transform: uppercase; color: #6b7280; margin-bottom: 4px; letter-spacing: 0.5px; }
        .buyer-name { font-weight: bold; color: #111827; font-size: 12px; }
        .buyer-detail { color: #4b5563; margin-top: 2px; font-size: 10px; }
        .info-table { width: 100%; }
        .info-table td { padding: 3px 0; font-size: 10px; }
        .info-table td:first-child { color: #6b7280; width: 40%; }
        .info-table td:last-child { color: #111827; font-weight: 500; }
        .items { width: 100%; border-collapse: collapse; margin-bottom: 15px; border: 1px solid #e5e7eb; }
        .items thead { background: #f3f4f6; }
        .items th { padding: 8px 6px; text-align: right; font-size: 10px; font-weight: bold; color: #374151; border-bottom: 2px solid #e5e7eb; }
        .items td { padding: 8px 6px; font-size: 11px; border-bottom: 1px solid #f3f4f6; color: #111827; }
        .items .num { text-align: center; }
        .items .total-col { text-align: left; font-weight: bold; }
        .items .qty-col { text-align: center; }
        .totals { width: 280px; margin-right: auto; margin-top: 15px; }
        .totals table { width: 100%; }
        .totals td { padding: 5px 0; font-size: 11px; }
        .totals .label { color: #6b7280; text-align: right; }
        .totals .value { text-align: left; color: #111827; }
        .totals .grand { border-top: 2px solid #4f46e5; padding-top: 10px; margin-top: 8px; font-size: 14px; font-weight: bold; color: #4f46e5; }
        .totals .grand .value { color: #4f46e5; }
        .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 9px; color: #9ca3af; line-height: 1.6; }
        .notes { margin-top: 15px; padding: 8px 12px; background: #f9fafb; border-right: 3px solid #d1d5db; font-size: 10px; color: #4b5563; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td style="width: 60%;">
                <div class="seller-name">{{ $invoice->snapshot['seller']['name'] }}</div>
                @if($invoice->snapshot['seller']['city'])
                    <div class="seller-meta">
                        {{ $invoice->snapshot['seller']['city'] }}@if($invoice->snapshot['seller']['address']) â€” {{ $invoice->snapshot['seller']['address'] }}@endif
                    </div>
                @endif
                @if($invoice->snapshot['seller']['phone'])
                    <div class="seller-meta" dir="ltr" style="text-align: right;">{{ $invoice->snapshot['seller']['phone'] }}</div>
                @endif
            </td>
            <td style="width: 40%; text-align: left;">
                <div class="invoice-title">ظپط§طھظˆط±ط©</div>
                <div class="invoice-number" dir="ltr">{{ $invoice->invoice_number }}</div>
                @if($invoice->isCancelled())
                    <div class="badge badge-cancelled" dir="ltr">CANCELLED</div>
                @endif
                @if($invoice->version > 1)
                    <div class="seller-meta" style="text-align: left;" dir="ltr">Version {{ $invoice->version }}</div>
                @endif
            </td>
        </tr>
    </table>

    <table class="meta">
        <tr>
            <td style="width: 50%;">
                <div class="label">ظپط§طھظˆط±ط© ط¥ظ„ظ‰</div>
                <div class="buyer-name">{{ $invoice->snapshot['buyer']['name'] }}</div>
                <div class="buyer-detail">{{ $invoice->snapshot['buyer']['address'] }}</div>
                <div class="buyer-detail">{{ $invoice->snapshot['buyer']['city'] }}</div>
                <div class="buyer-detail" dir="ltr" style="text-align: right;">{{ $invoice->snapshot['buyer']['phone'] }}</div>
            </td>
            <td style="width: 50%;">
                <table class="info-table">
                    <tr><td>طھط§ط±ظٹط® ط§ظ„ظپط§طھظˆط±ط©:</td><td dir="ltr" style="text-align: left;">{{ $invoice->issued_at->format('Y-m-d') }}</td></tr>
                    <tr><td>ط±ظ‚ظ… ط§ظ„ط·ظ„ط¨:</td><td dir="ltr" style="text-align: left; font-family: monospace;">{{ $invoice->snapshot['order_number'] }}</td></tr>
                    <tr><td>ط·ط±ظٹظ‚ط© ط§ظ„ط¯ظپط¹:</td><td>{{ $invoice->snapshot['payment_method'] === 'cash_on_delivery' ? 'ط§ظ„ط¯ظپط¹ ط¹ظ†ط¯ ط§ظ„ط§ط³طھظ„ط§ظ…' : $invoice->snapshot['payment_method'] }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th style="width: 6%;">#</th>
                <th style="width: 44%;">ط§ظ„ظ…ظ†طھط¬</th>
                <th style="width: 12%;">ط§ظ„ظƒظ…ظٹط©</th>
                <th style="width: 19%;">ط§ظ„ط³ط¹ط±</th>
                <th style="width: 19%;">ط§ظ„ط¥ط¬ظ…ط§ظ„ظٹ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->snapshot['items'] as $i => $item)
                <tr>
                    <td class="num">{{ $i + 1 }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td class="qty-col">{{ $item['quantity'] }}</td>
                    <td>{{ number_format($item['price'], 2) }} LYD</td>
                    <td class="total-col">{{ number_format($item['subtotal'], 2) }} LYD</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($invoice->snapshot['notes'] ?? null)
        <div class="notes"><strong>ظ…ظ„ط§ط­ط¸ط©:</strong> {{ $invoice->snapshot['notes'] }}</div>
    @endif

    <div class="totals">
        <table>
            <tr><td class="label">ط§ظ„ظ…ط¬ظ…ظˆط¹ ط§ظ„ظپط±ط¹ظٹ:</td><td class="value">{{ number_format($invoice->snapshot['totals']['subtotal'], 2) }} LYD</td></tr>
            <tr><td class="label">ط§ظ„ط´ط­ظ†:</td><td class="value">{{ $invoice->snapshot['totals']['shipping_cost'] > 0 ? number_format($invoice->snapshot['totals']['shipping_cost'], 2) . ' LYD' : 'ظ…ط¬ط§ظ†ظٹ' }}</td></tr>
            <tr class="grand"><td>ط§ظ„ط¥ط¬ظ…ط§ظ„ظٹ:</td><td class="value">{{ number_format($invoice->snapshot['totals']['total'], 2) }} LYD</td></tr>
        </table>
    </div>

    <div class="footer">
        <p>ط´ظƒط±ط§ظ‹ ظ„طھط³ظˆظ‚ظƒظ… ظ…ط¹ظ†ط§</p>
        <p>WESALKOM â€” {{ config('app.url') }}</p>
        <p>ظ‡ط°ظ‡ ظپط§طھظˆط±ط© ظ…ظڈظˆظ„ظ‘ط¯ط© ط¢ظ„ظٹط§ظ‹طŒ ظ„ط§ طھط­طھط§ط¬ ط¥ظ„ظ‰ طھظˆظ‚ظٹط¹.</p>
    </div>

</body>
</html>