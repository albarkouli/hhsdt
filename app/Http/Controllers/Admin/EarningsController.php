<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformEarning;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EarningsController extends Controller
{
    public function index(): View
    {
        $earnings = PlatformEarning::with(['order:id,order_number', 'vendor:id,name'])
            ->latest()
            ->paginate(25);

        return view('admin.earnings.index', compact('earnings'));
    }

    public function settle(PlatformEarning $earning): RedirectResponse
    {
        $earning->update(['status' => 'settled', 'settled_at' => now()]);

        return back()->with('status', 'Earning marked as settled.');
    }
}