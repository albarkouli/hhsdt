<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\UpdateVendorRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        $vendor = auth()->user()->vendor;

        abort_if(! $vendor, 403, 'No vendor profile.');

        return view('vendor.settings.edit', compact('vendor'));
    }

    public function update(UpdateVendorRequest $request): RedirectResponse
    {
        $vendor = auth()->user()->vendor;

        abort_if(! $vendor, 403, 'No vendor profile.');

        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($vendor->logo) {
                Storage::disk('public')->delete($vendor->logo);
            }
            $data['logo'] = $request->file('logo')->store('vendors', 'public');
        }

        $vendor->update($data);

        return redirect()
            ->route('vendor.settings.edit')
            ->with('status', 'Store settings updated.');
    }
}