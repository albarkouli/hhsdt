<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .print-container { box-shadow: none !important; border: none !important; margin: 0 !important; padding: 0 !important; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body class="min-h-screen bg-gray-100 antialiased py-8">
    <div class="max-w-3xl mx-auto px-4">

        <div class="no-print mb-4 flex items-center justify-between">
            <a href="{{ route('shop.orders.show', $order) }}" class="text-sm text-gray-600 hover:underline">&larr; Back to order</a>
            <div class="flex gap-2">
                <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700">
                    Print
                </button>
                <a href="{{ route('shop.invoices.pdf', $order) }}"
                   class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700">
                    Download PDF
                </a>
            </div>
        </div>

        <div class="print-container bg-white rounded-lg shadow-lg p-8 space-y-6">

            <div class="flex items-start justify-between border-b border-gray-200 pb-6">
                <div class="flex items-center gap-4">
                    @if ($invoice->snapshot['seller']['logo'])
                        <img src="{{ Storage::url($invoice->snapshot['seller']['logo']) }}" alt="" class="w-16 h-16 rounded-lg object-cover">
                    @else
                        <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white text-2xl font-bold">
                            {{ Str::substr($invoice->snapshot['seller']['name'], 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $invoice->snapshot['seller']['name'] }}</h1>
                        @if ($invoice->snapshot['seller']['city'])
                            <p class="text-sm text-gray-600">{{ $invoice->snapshot['seller']['city'] }}{{ $invoice->snapshot['seller']['address'] ? ' - ' . $invoice->snapshot['seller']['address'] : '' }}</p>
                        @endif
                        @if ($invoice->snapshot['seller']['phone'])
                            <p class="text-sm text-gray-600" dir="ltr">{{ $invoice->snapshot['seller']['phone'] }}</p>
                        @endif
                    </div>
                </div>

                <div class="text-left">
                    <div class="text-2xl font-bold text-indigo-600 uppercase">Invoice</div>
                    <div class="text-sm font-mono text-gray-700">{{ $invoice->invoice_number }}</div>
                    @if ($invoice->isCancelled())
                        <div class="mt-1 inline-block px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded">CANCELLED</div>
                    @endif
                    @if ($invoice->version > 1)
                        <div class="text-xs text-gray-500 mt-1">Version {{ $invoice->version }}</div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 text-sm">
                <div>
                    <div class="text-xs uppercase text-gray-500 mb-2 font-medium">Bill To</div>
                    <div class="font-semibold text-gray-900">{{ $invoice->snapshot['buyer']['name'] }}</div>
                    <div class="text-gray-600">{{ $invoice->snapshot['buyer']['address'] }}</div>
                    <div class="text-gray-600">{{ $invoice->snapshot['buyer']['city'] }}</div>
                    <div class="text-gray-600" dir="ltr">{{ $invoice->snapshot['buyer']['phone'] }}</div>
                </div>
                <div class="text-left">
                    <div class="grid grid-cols-2 gap-1 text-sm">
                        <span class="text-gray-500">Invoice date:</span>
                        <span class="text-gray-900 font-medium" dir="ltr">{{ $invoice->issued_at->format('Y-m-d') }}</span>
                        <span class="text-gray-500">Order:</span>
                        <span class="text-gray-900 font-mono">{{ $invoice->snapshot['order_number'] }}</span>
                        <span class="text-gray-500">Payment:</span>
                        <span class="text-gray-900">{{ $invoice->snapshot['payment_method'] === 'cash_on_delivery' ? 'Cash on Delivery' : $invoice->snapshot['payment_method'] }}</span>
                    </div>
                </div>
            </div>

            <div>
                <table class="w-full text-sm border border-gray-200 rounded overflow-hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-right font-medium text-gray-600">#</th>
                            <th class="px-3 py-2 text-right font-medium text-gray-600">Item</th>
                            <th class="px-3 py-2 text-center font-medium text-gray-600">Qty</th>
                            <th class="px-3 py-2 text-right font-medium text-gray-600">Price</th>
                            <th class="px-3 py-2 text-right font-medium text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($invoice->snapshot['items'] as $i => $item)
                            <tr>
                                <td class="px-3 py-2 text-gray-500">{{ $i + 1 }}</td>
                                <td class="px-3 py-2 text-gray-900">{{ $item['name'] }}</td>
                                <td class="px-3 py-2 text-center text-gray-900">{{ $item['quantity'] }}</td>
                                <td class="px-3 py-2 text-right text-gray-700">{{ number_format($item['price'], 2) }}</td>
                                <td class="px-3 py-2 text-right font-medium text-gray-900">{{ number_format($item['subtotal'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end">
                <div class="w-full sm:w-72 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>{{ number_format($invoice->snapshot['totals']['subtotal'], 2) }} LYD</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Shipping</span>
                        <span>{{ $invoice->snapshot['totals']['shipping_cost'] > 0 ? number_format($invoice->snapshot['totals']['shipping_cost'], 2) . ' LYD' : 'Free' }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-200 font-bold text-lg text-indigo-600">
                        <span>Total</span>
                        <span>{{ number_format($invoice->snapshot['totals']['total'], 2) }} LYD</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4 text-xs text-gray-500 text-center">
                <p>Thank you for your business.</p>
                @if ($invoice->snapshot['notes'])
                    <p class="mt-1 italic">Note: {{ $invoice->snapshot['notes'] }}</p>
                @endif
                <p class="mt-2">WESALKOM - {{ config('app.url') }}</p>
            </div>
        </div>

        <p class="no-print mt-4 text-xs text-gray-500 text-center">
            This is a computer-generated invoice. No signature required.
        </p>
    </div>
</body>
</html>