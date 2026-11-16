@props(['order'])

@php
    $status = $order->status->value;
    $isCancelled = $status === 'cancelled';

    $stages = [
        ['label' => 'Placed',    'time' => $order->created_at,    'reached' => true, 'icon' => 'cart'],
        ['label' => 'Confirmed', 'time' => $order->confirmed_at,  'reached' => in_array($status, ['confirmed','shipped','delivered'])],
        ['label' => 'Shipped',   'time' => $order->shipped_at,    'reached' => in_array($status, ['shipped','delivered'])],
        ['label' => 'Delivered', 'time' => $order->delivered_at,  'reached' => $status === 'delivered'],
    ];

    $reachedCount = collect($stages)->where('reached', true)->count();
    $progressPercent = $reachedCount > 0 ? (($reachedCount - 1) / 3) * 100 : 0;
@endphp

<div class="bg-[#171717] border border-gray-800 rounded-2xl p-5 sm:p-6">
    <h2 class="font-black text-white mb-5 text-lg">Order Tracking</h2>

    @if ($isCancelled)
        <div class="flex items-center gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/30">
            <div class="w-12 h-12 rounded-full bg-red-500/20 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <div class="font-black text-red-400">Order Cancelled</div>
                @if ($order->cancelled_at)
                    <div class="text-xs text-gray-500 mt-0.5">{{ $order->cancelled_at->format('M d, Y · H:i') }}</div>
                @endif
            </div>
        </div>
    @else
        <div class="relative">
            <div class="absolute top-5 left-[12%] right-[12%] h-0.5 bg-gray-800"></div>
            <div class="absolute top-5 left-[12%] h-0.5 bg-lime-400 transition-all duration-500"
                 style="width: {{ $progressPercent * 0.76 }}%"></div>

            <div class="relative grid grid-cols-4 gap-1">
                @foreach ($stages as $stage)
                    <div class="flex flex-col items-center text-center">
                        <div @class([
                            'w-10 h-10 rounded-full flex items-center justify-center mb-2 relative z-10 border-2 transition',
                            'bg-lime-400 text-black border-lime-400' => $stage['reached'],
                            'bg-[#171717] text-gray-600 border-gray-800' => ! $stage['reached'],
                        ])>
                            @if ($stage['label'] === 'Placed')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            @elseif ($stage['label'] === 'Confirmed')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @elseif ($stage['label'] === 'Shipped')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            @endif
                        </div>
                        <div class="text-[11px] sm:text-xs font-black {{ $stage['reached'] ? 'text-white' : 'text-gray-500' }}">{{ $stage['label'] }}</div>
                        @if ($stage['time'])
                            <div class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5">{{ $stage['time']->format('M d') }}<br>{{ $stage['time']->format('H:i') }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        @if ($status === 'pending_customer_approval')
            <div class="mt-4 rounded-lg bg-purple-500/10 border border-purple-500/30 p-3 text-xs text-purple-300">
                Waiting for your approval of vendor's proposed changes.
            </div>
        @endif
    @endif
</div>