<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Item;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of orders (bought and sold).
     */
    public function index(): View
    {
        $user = Auth::user();

        // Fetch orders where user is the buyer
        $boughtOrders = Order::where('buyer_id', $user->id)
            ->with(['seller', 'product', 'review'])
            ->latest()
            ->get();

        // Fetch orders where user is the seller
        $soldOrders = Order::where('seller_id', $user->id)
            ->with(['buyer', 'product', 'review'])
            ->latest()
            ->get();

        return view('orders.index', compact('boughtOrders', 'soldOrders'));
    }

    /**
     * Create an order for a product and simulate payment success.
     */
    public function createAndPay(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:items,id'],
            'payment_method' => ['required', 'string', 'in:wechat,alipay,offline'],
        ]);

        $buyer = Auth::user();
        $product = Item::findOrFail($validated['product_id']);

        if ($product->user_id === $buyer->id) {
            return response()->json([
                'status' => 'error',
                'message' => '不能购买自己的商品',
            ], 422);
        }

        if ($product->status !== 'on_sale') {
            return response()->json([
                'status' => 'error',
                'message' => '商品已售出或正在交易中',
            ], 422);
        }

        $order = DB::transaction(function () use ($product, $buyer, $validated) {
            $order = Order::create([
                'buyer_id' => $buyer->id,
                'seller_id' => $product->user_id,
                'product_id' => $product->id,
                'price' => $product->price,
                'status' => 'pending',
                'transaction_method' => $validated['payment_method'],
            ]);

            $product->update([
                'status' => 'sold',
            ]);

            return $order;
        });

        return response()->json([
            'status' => 'success',
            'order_id' => $order->id,
            'message' => 'Payment Successful',
        ]);
    }

    /**
     * Update the status of an order.
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:completed,cancelled'],
        ]);

        $order = Order::findOrFail($id);
        $user = Auth::user();

        // Verify that the user is either the buyer or seller
        if ($order->buyer_id !== $user->id && $order->seller_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Only allow status updates for pending orders
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => '只能更新进行中订单的状态'
            ], 400);
        }

        $order->status = $validated['status'];
        $order->save();

        return response()->json([
            'success' => true,
            'message' => '订单状态已更新',
            'order' => $order->load(['buyer', 'seller', 'product'])
        ]);
    }

    /**
     * Store a review for an order.
     */
    public function storeReview(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = Order::findOrFail($id);
        $user = Auth::user();

        // Verify that the user is either the buyer or seller
        if ($order->buyer_id !== $user->id && $order->seller_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Only allow reviews for completed orders
        if ($order->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => '只能对已完成的订单进行评价'
            ], 400);
        }

        // Check if review already exists
        if ($order->review) {
            return response()->json([
                'success' => false,
                'message' => '该订单已有评价'
            ], 400);
        }

        // Determine reviewer and reviewee
        $reviewerId = $user->id;
        $revieweeId = $order->buyer_id === $user->id 
            ? $order->seller_id 
            : $order->buyer_id;

        $review = Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $reviewerId,
            'reviewee_id' => $revieweeId,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => '评价已提交',
            'review' => $review->load(['reviewer', 'reviewee'])
        ]);
    }
}
