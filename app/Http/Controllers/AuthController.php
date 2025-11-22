<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    /**
     * 显示注册页面
     */
    public function showRegisterForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('user.profile');
        }
        return view('auth.register');
    }

    /**
     * 处理用户注册
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'student_id' => ['nullable', 'string', 'max:50', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
        ], [
            'name.required' => '请输入姓名',
            'email.required' => '请输入邮箱',
            'email.email' => '请输入有效的邮箱地址',
            'email.unique' => '该邮箱已被注册',
            'password.required' => '请输入密码',
            'password.min' => '密码至少需要8个字符',
            'password.confirmed' => '两次输入的密码不一致',
            'student_id.unique' => '该学号已被注册',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'student_id' => $validated['student_id'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('user.profile')->with('success', '注册成功！欢迎加入校园易');
    }

    /**
     * 显示登录页面
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('user.profile');
        }
        return view('auth.login');
    }

    /**
     * 处理用户登录
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => '请输入邮箱',
            'email.email' => '请输入有效的邮箱地址',
            'password.required' => '请输入密码',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('user.profile'))->with('success', '登录成功！');
        }

        throw ValidationException::withMessages([
            'email' => ['邮箱或密码错误'],
        ]);
    }

    /**
     * 处理用户退出
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json(['message' => '已成功退出登录'], 200);
        }

        return redirect('/')->with('success', '已成功退出登录');
    }

    /**
     * 更新用户资料
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ], [
            'name.required' => '请输入昵称',
            'name.max' => '昵称不能超过255个字符',
            'phone.max' => '联系方式不能超过20个字符',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? $user->phone,
        ]);

        return redirect()->route('user.profile')->with('success', '资料更新成功！');
    }
}

