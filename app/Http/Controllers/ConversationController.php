<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ConversationController extends Controller
{
    /**
     * Check if a conversation exists, or create a new one.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function checkOrCreate(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'receiver_id' => ['required', 'exists:users,id'],
            'product_id' => ['nullable', 'exists:items,id'],
            'task_id' => ['nullable', 'exists:tasks,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $receiverId = $validated['receiver_id'];
        $productId = $validated['product_id'] ?? null;
        $taskId = $validated['task_id'] ?? null;

        // Prevent users from starting a conversation with themselves
        if ($user->id == $receiverId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot start a conversation with yourself'
            ], 400);
        }

        // Check if a conversation already exists between these two users for this specific product/task
        $conversation = Conversation::where(function ($query) use ($user, $receiverId) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($user, $receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', $user->id);
        })->when($productId, function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })->when($taskId, function ($query) use ($taskId) {
            $query->where('task_id', $taskId);
        })->when(!$productId && !$taskId, function ($query) {
            // If neither product_id nor task_id is provided, look for conversations without both
            $query->whereNull('product_id')->whereNull('task_id');
        })->first();

        // If conversation doesn't exist, create a new one
        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $user->id,
                'receiver_id' => $receiverId,
                'product_id' => $productId,
                'task_id' => $taskId,
                'last_message_at' => now(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'redirect_url' => '/chat/' . $conversation->id
        ]);
    }

    /**
     * Display a specific conversation.
     * 
     * @param Conversation $conversation
     * @return View|JsonResponse
     */
    public function show(Conversation $conversation)
    {
        $user = Auth::user();
        
        // Verify user is part of this conversation
        if ($conversation->sender_id !== $user->id && $conversation->receiver_id !== $user->id) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized');
        }

        // Mark messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Eager load all necessary relationships
        $conversation->load('messages.user', 'product', 'task', 'sender', 'receiver');

        // If AJAX request, return JSON (for polling)
        if (request()->expectsJson()) {
            $otherUser = $conversation->sender_id === $user->id 
                ? $conversation->receiver 
                : $conversation->sender;

            // Helper function to format image path
            $formatImagePath = function ($image) {
                if (!$image) {
                    return '/images/placeholder-product.png'; // Default placeholder
                }
                if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                    return $image;
                }
                return '/storage/' . ltrim($image, '/');
            };

            return response()->json([
                'conversation' => [
                    'id' => $conversation->id,
                    'product' => $conversation->product ? [
                        'id' => $conversation->product->id,
                        'title' => $conversation->product->title,
                        'price' => $conversation->product->price,
                        'image' => $formatImagePath($conversation->product->image),
                        'user_id' => $conversation->product->user_id,
                        'status' => $this->normalizeProductStatus($conversation->product->status),
                    ] : null,
                    'task' => $conversation->task ? [
                        'id' => $conversation->task->id,
                        'title' => $conversation->task->title,
                        'reward' => $conversation->task->reward,
                    ] : null,
                    'other_user' => $otherUser,
                ],
                'messages' => $conversation->messages->map(function ($message) use ($user) {
                    return [
                        'id' => $message->id,
                        'body' => $message->body,
                        'is_me' => $message->user_id === $user->id,
                        'time_ago' => $message->created_at->diffForHumans(),
                        'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
            ]);
        }

        // Get all conversations for the sidebar
        $allConversations = Conversation::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver', 'product', 'task', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conv) use ($user) {
                // Determine the other user in the conversation
                $otherUser = $conv->sender_id === $user->id 
                    ? $conv->receiver 
                    : $conv->sender;
                
                return [
                    'id' => $conv->id,
                    'other_user' => $otherUser,
                    'product' => $conv->product,
                    'task' => $conv->task,
                    'last_message' => $conv->latestMessage,
                    'last_message_at' => $conv->last_message_at,
                    'unread_count' => Message::where('conversation_id', $conv->id)
                        ->where('user_id', '!=', $user->id)
                        ->where('is_read', false)
                        ->count(),
                ];
            });

        return view('chat.show', compact('conversation', 'allConversations'));
    }

    /**
     * Get messages for a specific conversation (AJAX API).
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function getMessages($id): JsonResponse
    {
        $user = Auth::user();
        
        $conversation = Conversation::with(['messages.user', 'product', 'task', 'sender', 'receiver'])
            ->findOrFail($id);
        
        // Verify user is part of this conversation
        if ($conversation->sender_id !== $user->id && $conversation->receiver_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Mark messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Determine the other user
        $otherUser = $conversation->sender_id === $user->id 
            ? $conversation->receiver 
            : $conversation->sender;

        // Helper function to format image path
        $formatImagePath = function ($image) {
            if (!$image) {
                return '/images/placeholder-product.png'; // Default placeholder
            }
            if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                return $image;
            }
            return '/storage/' . ltrim($image, '/');
        };

        // Build conversation data
        $conversationData = [
            'id' => $conversation->id,
        ];

        // Add product data if exists
        if ($conversation->product) {
            $conversationData['product'] = [
                'id' => $conversation->product->id,
                'title' => $conversation->product->title,
                'price' => $conversation->product->price,
                'image' => $formatImagePath($conversation->product->image),
                'user_id' => $conversation->product->user_id,
                'status' => $this->normalizeProductStatus($conversation->product->status),
            ];
        } else {
            $conversationData['product'] = null;
        }

        // Add task data if exists
        if ($conversation->task) {
            $conversationData['task'] = [
                'id' => $conversation->task->id,
                'title' => $conversation->task->title,
                'reward' => $conversation->task->reward,
            ];
        } else {
            $conversationData['task'] = null;
        }

        // Add other user data
        $conversationData['other_user'] = $otherUser ? [
            'id' => $otherUser->id,
            'name' => $otherUser->name,
            'email' => $otherUser->email,
        ] : null;

        // Build messages data
        $messages = $conversation->messages->map(function ($message) use ($user) {
            return [
                'id' => $message->id,
                'body' => $message->body,
                'is_me' => $message->user_id === $user->id,
                'time_ago' => $message->created_at->diffForHumans(),
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'conversation' => $conversationData,
            'messages' => $messages,
        ]);
    }

    /**
     * Normalize product status so frontend can rely on unified values.
     */
    private function normalizeProductStatus(?string $status): string
    {
        return match ($status) {
            'active', 'on_sale' => 'active',
            'pending' => 'pending',
            default => 'sold',
        };
    }

    /**
     * Store a new message in a conversation (AJAX API).
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function store(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        
        $conversation = Conversation::findOrFail($id);
        
        // Verify user is part of this conversation
        if ($conversation->sender_id !== $user->id && $conversation->receiver_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
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

        // Load the user relationship for the response
        $message->load('user');

        // Return the same structure as getChatData
        return response()->json([
            'status' => 'success',
            'message' => [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'body' => $message->body,
                'avatar' => $message->user ? substr($message->user->name, 0, 1) : 'U',
                'time_ago' => $message->created_at ? $message->created_at->diffForHumans() : '刚刚',
                'is_me' => $message->user_id === $user->id,
            ],
        ]);
    }

    /**
     * Get chat data for a specific conversation (SPA API).
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function getChatData($id): JsonResponse
    {
        $user = Auth::user();
        
        // Fetch conversation with all necessary relations (including messages.user)
        $conversation = Conversation::with(['messages.user', 'product', 'task', 'sender', 'receiver'])
            ->findOrFail($id);
        
        // Verify user is part of this conversation
        if ($conversation->sender_id !== $user->id && $conversation->receiver_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Mark messages as read (messages from other users)
        Message::where('conversation_id', $id)
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Helper function to format image path
        $formatImagePath = function ($image) {
            if (!$image) {
                return '/images/placeholder-product.png';
            }
            if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                return $image;
            }
            return '/storage/' . ltrim($image, '/');
        };

        // Determine the other user (receiver from current user's perspective)
        $otherUser = $conversation->sender_id === $user->id 
            ? $conversation->receiver 
            : $conversation->sender;

        // Build conversation data
        $conversationData = [
            'id' => $conversation->id,
            'sender_id' => $conversation->sender_id,
            'receiver_id' => $conversation->receiver_id,
            'product' => $conversation->product ? [
                'id' => $conversation->product->id,
                'title' => $conversation->product->title,
                'price' => $conversation->product->price,
                'image' => $formatImagePath($conversation->product->image),
                'user_id' => $conversation->product->user_id,
                'status' => $this->normalizeProductStatus($conversation->product->status),
            ] : null,
            'task' => $conversation->task ? [
                'id' => $conversation->task->id,
                'title' => $conversation->task->title,
                'reward' => $conversation->task->reward,
            ] : null,
            'sender' => $conversation->sender ? [
                'id' => $conversation->sender->id,
                'name' => $conversation->sender->name,
                'email' => $conversation->sender->email,
            ] : null,
            'receiver' => $conversation->receiver ? [
                'id' => $conversation->receiver->id,
                'name' => $conversation->receiver->name,
                'email' => $conversation->receiver->email,
                'avatar_url' => $conversation->receiver->avatar_url,
            ] : null,
            'other_user' => $otherUser ? [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'email' => $otherUser->email,
                'avatar_url' => $otherUser->avatar_url,
            ] : null,
            'last_message_at' => $conversation->last_message_at?->toISOString(),
        ];

        // Build messages data with formatted time_ago, sender_avatar, and sender_name
        $messages = $conversation->messages->map(function ($message) use ($user) {
            $sender = $message->user;
            return [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'body' => $message->body,
                'sender_avatar' => $sender ? $sender->avatar_url : null,
                'sender_name' => $sender ? $sender->name : 'Unknown',
                'time_ago' => $message->created_at ? $message->created_at->diffForHumans() : '刚刚',
                'is_me' => $message->user_id === $user->id,
            ];
        })->values();

        return response()->json([
            'conversation' => $conversationData,
            'messages' => $messages,
            'current_user_id' => $user->id,
        ]);
    }

    /**
     * Delete a conversation.
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $conversation = Conversation::findOrFail($id);
        
        // Verify user is part of this conversation (sender or receiver)
        if ($conversation->sender_id !== $user->id && $conversation->receiver_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        // Delete the conversation (messages will be cascade deleted if foreign key constraints are set up)
        $conversation->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
