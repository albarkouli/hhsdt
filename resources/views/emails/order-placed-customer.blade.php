<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmed</title>
</head>
<body style="margin:0; padding:0; background:#f3f4f6; font-family: -apple-system, Segoe UI, Roboto, sans-serif; color:#1f2937;">
    <div style="max-width:600px; margin:0 auto; padding:32px 16px;">

        {{-- Header --}}
        <div style="text-align:center; margin-bottom:24px;">
            <div style="display:inline-block; background:#4f46e5; color:#fff; padding:12px 24px; border-radius:8px; font-size:18px; font-weight:700;">
                Libya Marketplace
            </div>
        </div>

        {{-- Card --}}
        <div style="background:#fff; border-radius:12px; padding:32px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">

            <h1 style="font-size:22px; margin:0 0 8px; color:#111827;">Thank you for your order! ✅</h1>
            <p style="font-size:15px; color:#6b7280; margin:0 0 24px;">
                Hi {{ $customer->name }}, we've received your order and sent it to the vendor. They'll review and confirm it shortly.
            </p>

            {{-- Order summary --}}
            <div style="background:#f9fafb; border-radius:8px; padding:16px; margin-bottom:20px;">
                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <span style="font-size:13px; color:#6b7280;">Order number</span>
                    <span style="font-size:13px; font-weight:600; color:#111827;">{{ $order->order_number }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <span style="font-size:13px; color:#6b7280;">Order date</span>
                    <span style="font-size:13px; color:#111827;">{{ $order->created_at->format('F j, Y') }}</span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span style="font-size:13px; color:#6b7280;">Vendor</span>
                    <span style="font-size:13px; color:#111827;">{{ $order->vendor->name }}</span>
                </div>
            </div>

            {{-- Items --}}
            <h2 style="font-size:15px; font-weight:600; color:#111827; margin:0 0 12px;">Items</h2>
            <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
                @foreach ($order->items as $item)
                    <tr style="border-bottom:1px solid #f3f4f6;">
                        <td style="padding:10px 0; font-size:14px; color:#374151;">
                            {{ $item->product_name }}
                            <span style="color:#9ca3af; font-size:12px;">× {{ $item->quantity }}</span>
                        </td>
                        <td style="padding:10px 0; text-align:right; font-size:14px; font-weight:600; color:#111827;">
                            {{ number_format((float) $item->subtotal, 2) }} LYD
                        </td>
                    </tr>
                @endforeach
            </table>

            {{-- Total --}}
            <div style="display:flex; justify-content:space-between; padding-top:12px; border-top:2px solid #f3f4f6;">
                <span style="font-size:15px; font-weight:600; color:#111827;">Total</span>
                <span style="font-size:18px; font-weight:700; color:#4f46e5;">{{ number_format((float) $order->total, 2) }} LYD</span>
            </div>

            {{-- Shipping --}}
            <h2 style="font-size:15px; font-weight:600; color:#111827; margin:24px 0 12px;">Shipping to</h2>
            <p style="font-size:14px; color:#374151; margin:0; line-height:1.6;">
                {{ $order->shipping_name }}<br>
                {{ $order->shipping_phone }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_address }}
            </p>

            {{-- Payment --}}
            <p style="font-size:13px; color:#6b7280; margin:20px 0 0;">
                💰 <strong>Payment method:</strong> Cash on Delivery
            </p>

            {{-- CTA --}}
            <div style="text-align:center; margin-top:28px;">
                <a href="{{ $orderUrl }}"
                   style="display:inline-block; padding:12px 24px; background:#4f46e5; color:#fff; text-decoration:none; border-radius:8px; font-weight:600; font-size:14px;">
                    View order details →
                </a>
            </div>
        </div>

        {{-- Footer --}}
        <div style="text-align:center; margin-top:24px; font-size:12px; color:#9ca3af;">
            <p style="margin:0 0 8px;">
                Need help? Reply to this email or contact us via <a href="{{ url('/shop/contact') }}" style="color:#4f46e5;">our contact page</a>.
            </p>
            <p style="margin:0;">© {{ date('Y') }} Libya Marketplace. All rights reserved.</p>
        </div>
    </div>
</body>
</html>