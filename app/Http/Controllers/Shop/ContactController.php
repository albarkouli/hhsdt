<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ContactRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('shop.contact');
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        ContactMessage::create([
            'name'       => $request->input('name'),
            'email'      => $request->input('email'),
            'subject'    => $request->input('subject'),
            'message'    => $request->input('message'),
            'status'     => 'new',
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('shop.contact')
            ->with('contact_status', 'Thank you! Your message has been received. We will get back to you within 48 hours.');
    }
}