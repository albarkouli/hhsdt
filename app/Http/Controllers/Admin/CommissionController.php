<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformEarning;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissionController extends Controller
{
    public function index(): View
    {
        $vendors = Vendor::query()
            ->withCount('orders')
            ->withSum(['orders as total_sales' => fn ($q) => $q->where('status', 'delivered')], 'total')
            ->orderBy('name')
            ->get();

        $stats = [
            'avg_rate'         => round((float) Vendor::avg('commission_rate'), 2),
            'min_rate'         => round((float) Vendor::min('commission_rate'), 2),
            'max_rate'         => round((float) Vendor::max('commission_rate'), 2),
            'total_vendors'    => Vendor::count(),
            'total_earned'     => (float) PlatformEarning::sum('amount'),
            'pending_earned'   => (float) PlatformEarning::where('status', 'pending')->sum('amount'),
        ];

        return view('admin.commissions.index', compact('vendors', 'stats'));
    }

    public function update(Request $request, Vendor $vendor): RedirectResponse
    {
        $validated = $request->validate([
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:30'],
        ]);

        $old = (float) $vendor->commission_rate;
        $new = (float) $validated['commission_rate'];

        $vendor->update(['commission_rate' => $new]);

        return back()->with('status', "{$vendor->name}: {$old}% → {$new}%");
    }

    public function bulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bulk_rate' => ['required', 'numeric', 'min:0', 'max:30'],
            'confirm'   => ['accepted'],
        ], [
            'confirm.accepted' => 'You must confirm the bulk update.',
        ]);

        $new = (float) $validated['bulk_rate'];
        $count = Vendor::query()->update(['commission_rate' => $new]);

        return back()->with('status', "Updated commission rate for all {$count} vendors to {$new}%.");
    }
}