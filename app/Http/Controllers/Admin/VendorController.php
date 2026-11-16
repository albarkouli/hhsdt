<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VendorStatus;
use App\Http\Controllers\Controller;
use App\Notifications\VendorApproved;
use App\Notifications\VendorSuspended;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $vendors = Vendor::query()
            ->when(in_array($status, ['pending','active','suspended','rejected'], true),
                fn ($q) => $q->where('status', $status))
            ->with('owner:id,name,email')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.vendors.index', [
            'vendors' => $vendors,
            'status'  => $status,
            'counts'  => [
                'pending'   => Vendor::where('status', VendorStatus::Pending)->count(),
                'active'    => Vendor::where('status', VendorStatus::Active)->count(),
                'suspended' => Vendor::where('status', VendorStatus::Suspended)->count(),
                'rejected'  => Vendor::where('status', VendorStatus::Rejected)->count(),
            ],
        ]);
    }

    public function approve(Vendor $vendor): RedirectResponse
    {
        $wasNotActive = $vendor->status !== VendorStatus::Active;
        $vendor->update(['status' => VendorStatus::Active]);

        if ($wasNotActive && $vendor->owner) {
            $vendor->owner->notify(new VendorApproved($vendor));
        }
        return back()->with('status', "Store \"{$vendor->name}\" approved.");
    }

    public function reject(Vendor $vendor): RedirectResponse
    {
        $vendor->update(['status' => VendorStatus::Rejected]);

        if ($vendor->owner) {
            $vendor->owner->notify(new VendorSuspended($vendor, 'rejected'));
        }
        return back()->with('status', "Store \"{$vendor->name}\" rejected.");
    }

    public function suspend(Vendor $vendor): RedirectResponse
    {
        $vendor->update(['status' => VendorStatus::Suspended]);

        if ($vendor->owner) {
            $vendor->owner->notify(new VendorSuspended($vendor, 'suspended'));
        }
        return back()->with('status', "Store \"{$vendor->name}\" suspended.");
    }

    public function reactivate(Vendor $vendor): RedirectResponse
    {
        $vendor->update(['status' => VendorStatus::Active]);
        return back()->with('status', "Store \"{$vendor->name}\" reactivated.");
    }
}