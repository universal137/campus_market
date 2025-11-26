<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('settings.index');
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => '当前密码不正确',
            ], 422);
        }

        $user->forceFill([
            'password' => Hash::make($validated['new_password']),
        ])->save();

        return response()->json([
            'status' => 'success',
            'message' => '密码已更新',
        ]);
    }

    public function toggleNotification(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string'],
        ]);

        $user = $request->user();
        $preferences = $user->preferences ?? [];

        if (! is_array($preferences)) {
            $preferences = [];
        }

        $type = $validated['type'];
        $currentState = (bool) ($preferences[$type] ?? false);
        $preferences[$type] = ! $currentState;

        $user->preferences = $preferences;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => '通知设置已更新',
            'data' => [
                'type' => $type,
                'enabled' => $preferences[$type],
            ],
        ]);
    }
}

