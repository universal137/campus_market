<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review for a completed order.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string'],
        ]);

        $order = Order::with(['buyer', 'seller', 'review'])->findOrFail($data['order_id']);

        if ($order->review) {
            return response()->json([
                'status' => 'error',
                'message' => '此订单已被评价。',
            ], 422);
        }

        $reviewerId = Auth::id();
        $revieweeId = $order->seller_id === $reviewerId ? $order->buyer_id : $order->seller_id;

        $review = Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $reviewerId,
            'reviewee_id' => $revieweeId,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        if (!$order->isCompleted()) {
            $order->forceFill(['status' => 'completed'])->save();
        }

        $reviewee = $review->reviewee()->with('reviewsReceived')->first();

        return response()->json([
            'status' => 'success',
            'new_reputation' => $reviewee?->reputation ?? 5.0,
        ]);
    }
}
