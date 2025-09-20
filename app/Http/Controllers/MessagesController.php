<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Message;

class MessagesController extends Controller
{
    /**
     * Store a newly created message in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        Message::create($validated);

        return back()->with('success', 'تم إرسال رسالتك بنجاح! سنقوم بالرد عليك في أقرب وقت ممكن.');
    }

    /**
     * Display a listing of the messages for admin dashboard.
     */
    public function index(): View
    {
        $messages = Message::latest()->paginate(10);
        return view('messages.index', compact('messages'));
    }

    /**
     * Display the specified message.
     */
    public function show(Message $message): View
    {
        // Mark message as read when viewed
        if (!$message->read) {
            $message->update(['read' => true]);
        }

        return view('messages.show', compact('message'));
    }

    /**
     * Mark the specified message as read.
     */
    public function markAsRead(Message $message): RedirectResponse
    {
        $message->update(['read' => true]);
        return back()->with('success', 'تم تعليم الرسالة كمقروءة.');
    }
}
