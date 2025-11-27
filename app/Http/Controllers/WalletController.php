<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Display the wallet overview and transaction history.
     */
    public function index()
    {
        $user = Auth::user();
        $transactions = $user->transactions()->latest()->paginate(20);

        return view('user.wallet', compact('user', 'transactions'));
    }

    /**
     * Handle Campus Coin recharge requests.
     */
    public function recharge(Request $request)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
            'reference_id' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        $methodLabels = [
            'wechat' => '微信支付',
            'alipay' => '支付宝',
            'offline' => '线下支付',
            'coin' => '校园币',
        ];

        $methodLabel = $methodLabels[$data['payment_method']] ?? ucfirst($data['payment_method']);
        $description = $data['description'] ?? "余额充值 - {$methodLabel}";

        $transaction = $user->transactions()->create([
            'type' => Transaction::TYPE_DEPOSIT,
            'amount' => $data['amount'],
            'description' => $description,
            'reference_id' => $data['reference_id'] ?? null,
        ]);

        $user->increment('balance', $data['amount']);
        $newBalance = $user->fresh()->balance;

        return response()->json([
            'status' => 'success',
            'new_balance' => number_format($newBalance, 2, '.', ''),
            'transaction' => [
                'type' => $transaction->type,
                'description' => $transaction->description,
                'created_at_formatted' => $transaction->created_at->format('Y-m-d H:i'),
                'amount_formatted' => '+' . number_format($transaction->amount, 2, '.', ''),
            ],
        ]);
    }
}

