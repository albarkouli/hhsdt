<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Order</title>
</head>
<body style="margin:0; padding:0; background:#f3f4f6; font-family: -apple-system, Segoe UI, Roboto, sans-serif; color:#1f2937;">
    <div style="max-width:600px; margin:0 auto; padding:32px 16px;">

        {{-- Header --}}
        <div style="text-align:center; margin-bottom:24px;">
            <div style="display:inline-block; background:#4f46e5; color:#fff; padding:12px 24px; border-radius:8px; font-size:18px; font-weight:700;">
                WESALKOM
            </div>
        </div>

        {{-- Card --}}
        <div style="background:#fff; border-radius:12px; padding:32px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">

            <h1 style="font-size:22px; margin:0 0 8px; color:#111827;">ًں›’ New order received</h1>
            <p style="font-size:15px; color:#6b7280; margin:0 0 24px;">
                Hi {{ $vendor->name }}, you have a new order from <strong>{{ $order->user->name }}</strong>. Please review and confirm it as soon as possible.
            </p>

            {{-- Order summary --}}
            <div style="background:#fef3c7; border-left:4px solid #f59e0b; border-radius:6px; padding:12px 16px; margin-bottom:20px;">
                <div style="font-size:13px; color:#78350f;">
                    <strong>Order:</strong> {{ $order->order_number }} آ·
                    <strong>Total:</strong> {{ number_format((float) $order->total, 2) }} LYD
                </div>
            </div>

            {{-- Items --}}
            <h2 style="font-size:15px; font-weight:600; color:#111827; margin:0 0 12px;">Items ordered</h2>
            <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
                @foreach ($order->items as $item)
                    <tr style="border-bottom:1px solid #f3f4f6;">
                        <td style="padding:10px 0; font-size:14px; color:#374151;">
                            {{ $item->product_name }}
                            <span style="color:#9ca3af; font-size:12px;">أ— {{ $item->quantity }}</span>
                        </td>
                        <td style="padding:10px 0; text-align:right; font-size:14px; font-weight:600; color:#111827;">
                            {{ number_format((float) $item->subtotal, 2) }} LYD
                        </td>
                    </tr>
                @endforeach
            </table>

            {{-- Customer info --}}
            <h2 style="font-size:15px; font-weight:600; color:#111827; margin:24px 0 12px;">Ship to</h2>
            <p style="font-size:14px; color:#374151; margin:0; line-height:1.6;">
                <strong>{{ $order->shipping_name }}</strong><br>
                ًں“‍ {{ $order->shipping_phone }}<br>
                ًں“چ {{ $order->shipping_city }}, {{ $order->shipping_address }}
            </p>

            @if ($order->notes)
                <div style="margin-top:16px; padding:12px; background:#f9fafb; border-radius:6px;">
                    <div style="font-size:12px; color:#6b7280; margin-bottom:4px;">Customer notes:</div>
                    <div style="font-size:13px; color:#374151;">{{ $order->notes }}</div>
                </div>
            @endif

            {{-- CTA --}}
            <div style="text-align:center; margin-top:28px;">
                <a href="{{ $orderUrl }}"
                   style="display:inline-block; padding:12px 24px; background:#4f46e5; color:#fff; text-decoration:none; border-radius:8px; font-weight:600; font-size:14px;">
                    Confirm order â†’
                </a>
            </div>

            <p style="font-size:12px; color:#9ca3af; margin:20px 0 0; text-align:center;">
                Please confirm or cancel this order within 24 hours. Unconfirmed orders hurt customer trust.
            </p>
        </div>

        {{-- Footer --}}
        <div style="text-align:center; margin-top:24px; font-size:12px; color:#9ca3af;">
            <p style="margin:0;">آ© {{ date('Y') }} WESALKOM. All rights reserved.</p>
        </div>
    </div>
</body>
</html>