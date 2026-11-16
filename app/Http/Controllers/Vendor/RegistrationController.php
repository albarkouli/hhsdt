<?php

namespace App\Http\Controllers\Vendor;

use App\Enums\VendorStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreVendorRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    /**
     * Show the "open a store" form.
     */
    public function create(): View|RedirectResponse
    {
        if (auth()->user()->vendor()->exists()) {
            return redirect()->route('vendor.dashboard');
        }

        return view('vendor.register');
    }

    /**
     * Persist the new vendor and redirect to the vendor dashboard.
     */
    public function store(StoreVendorRequest $request): RedirectResponse
    {
        $vendor = $request->user()->vendor()->create([
            'name'        => $request->string('name')->toString(),
            'description' => $request->input('description'),
            'phone'       => $request->input('phone'),
            'email'       => $request->input('email'),
            'address'     => $request->input('address'),
            'city'        => $request->input('city'),
            'status'      => VendorStatus::Pending,
        ]);

        // The Vendor::booted() hook promotes the user to the "vendor" role
        // and removes the default "customer" role automatically.

        return redirect()
            ->route('vendor.dashboard')
            ->with('status', 'Your store has been submitted and is pending approval.');
    }
}
