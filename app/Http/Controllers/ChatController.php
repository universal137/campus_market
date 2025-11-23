<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    /**
     * Display the chat interface.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Get all conversations for the user
        $conversations = Conversation::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver', 'product.user', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conversation) use ($user) {
                // Determine the other user in the conversation
                $otherUser = $conversation->sender_id === $user->id 
                    ? $conversation->receiver 
                    : $conversation->sender;
                
                return [
                    'id' => $conversation->id,
                    'other_user' => $otherUser,
                    'product' => $conversation->product,
                    'last_message' => $conversation->latestMessage,
                    'last_message_at' => $conversation->last_message_at,
                    'unread_count' => Message::where('conversation_id', $conversation->id)
                        ->where('user_id', '!=', $user->id)
                        ->where('is_read', false)
                        ->count(),
                ];
            });

        return view('chat.index', [
            'conversations' => $conversations,
        ]);
    }

    /**
     * Get messages for a specific conversation.
     */
    public function show(Conversation $conversation): JsonResponse
    {
        $user = Auth::user();
        
        // Verify user is part of this conversation
        if ($conversation->sender_id !== $user->id && $conversation->receiver_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Mark messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Load conversation with relationships
        $conversation->load(['sender', 'receiver', 'product.user', 'messages.user']);

        // Determine the other user
        $otherUser = $conversation->sender_id === $user->id 
            ? $conversation->receiver 
            : $conversation->sender;

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'product' => $conversation->product ? [
                    'id' => $conversation->product->id,
                    'title' => $conversation->product->title,
                    'price' => $conversation->product->price,
                    'image' => $conversation->product->image,
                    'user_id' => $conversation->product->user_id,
                ] : null,
                'other_user' => $otherUser,
            ],
            'messages' => $conversation->messages->map(function ($message) use ($user) {
                return [
                    'id' => $message->id,
                    'body' => $message->body,
                    'is_mine' => $message->user_id === $user->id,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                    'created_at_human' => $message->created_at->diffForHumans(),
                ];
            }),
        ]);
    }

    /**
     * Send a message in a conversation.
     */
    public function sendMessage(Request $request, Conversation $conversation): JsonResponse
    {
        $user = Auth::user();
        
        // Verify user is part of this conversation
        if ($conversation->sender_id !== $user->id && $conversation->receiver_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => $validated['body'],
            'is_read' => false,
        ]);

        // Update conversation's last_message_at
        $conversation->update([
            'last_message_at' => now(),
        ]);

        return response()->json([
            'message' => [
                'id' => $message->id,
                'body' => $message->body,
                'is_mine' => true,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                'created_at_human' => $message->created_at->diffForHumans(),
            ],
        ]);
    }

    /**
     * Start a new conversation or get existing one.
     */
    public function startConversation(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'product_id' => ['nullable', 'exists:items,id'],
        ]);

        // Check if conversation already exists
        $conversation = Conversation::where(function ($query) use ($user, $validated) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $validated['receiver_id']);
        })->orWhere(function ($query) use ($user, $validated) {
            $query->where('sender_id', $validated['receiver_id'])
                  ->where('receiver_id', $user->id);
        })->when($validated['product_id'], function ($query) use ($validated) {
            $query->where('product_id', $validated['product_id']);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $user->id,
                'receiver_id' => $validated['receiver_id'],
                'product_id' => $validated['product_id'] ?? null,
                'last_message_at' => now(),
            ]);
        }

        $conversation->load(['sender', 'receiver', 'product']);

        return response()->json([
            'conversation_id' => $conversation->id,
        ]);
    }
}

