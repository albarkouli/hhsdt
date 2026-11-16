<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $messages = ContactMessage::query()
            ->when(in_array($status, ['new', 'read', 'replied'], true),
                fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'new'     => ContactMessage::where('status', 'new')->count(),
            'read'    => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
        ];

        return view('admin.messages.index', compact('messages', 'status', 'counts'));
    }

    public function show(ContactMessage $message): View
    {
        $message->markAsRead();
        return view('admin.messages.show', compact('message'));
    }

    public function markReplied(ContactMessage $message): RedirectResponse
    {
        $message->markAsReplied();
        return back()->with('status', 'Message marked as replied.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('status', 'Message deleted.');
    }
}