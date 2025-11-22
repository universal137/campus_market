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
            'bio' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'school' => ['nullable', 'string', 'max:255'],
            'major' => ['nullable', 'string', 'max:255'],
            'student_id' => ['nullable', 'string', 'max:50', 'unique:users,student_id,' . $user->id],
            'enrollment_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
        ], [
            'name.required' => '请输入昵称',
            'name.max' => '昵称不能超过255个字符',
            'bio.max' => '个人简介不能超过500个字符',
            'phone.max' => '联系方式不能超过20个字符',
            'school.max' => '学校名称不能超过255个字符',
            'major.max' => '专业名称不能超过255个字符',
            'student_id.unique' => '该学号已被使用',
            'student_id.max' => '学号不能超过50个字符',
            'enrollment_year.integer' => '入学年份必须是数字',
            'enrollment_year.min' => '入学年份无效',
            'enrollment_year.max' => '入学年份无效',
        ]);

        // 如果学号已存在且已验证，不允许修改
        if ($user->student_id && $user->student_id !== ($validated['student_id'] ?? null)) {
            return redirect()->route('user.profile')
                ->withErrors(['student_id' => '已认证的学号不可修改，如需修改请联系管理员'])
                ->withInput();
        }

        // 准备更新数据
        $updateData = [
            'name' => $validated['name'],
            'bio' => $validated['bio'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'school' => $validated['school'] ?? null,
            'major' => $validated['major'] ?? null,
            'enrollment_year' => $validated['enrollment_year'] ?? null,
        ];

        // 学号：如果已存在则保持不变，否则使用新提交的值
        $updateData['student_id'] = $user->student_id ?? ($validated['student_id'] ?? null);

        $user->update($updateData);

        return redirect()->route('user.profile')->with('success', '资料更新成功！');
    }
}

