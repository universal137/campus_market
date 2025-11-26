<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        $conversations = Conversation::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with([
                'sender',
                'receiver',
                'product',
                'messages' => function ($query) {
                    $query->latest()->limit(1);
                },
            ])
            ->orderByDesc('updated_at')
            ->get();

        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->get();

        return view('notifications.index', compact('conversations', 'notifications'));
    }
}

