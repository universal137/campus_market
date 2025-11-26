@extends('layouts.app')

@section('title', '设置 · 校园易')

@section('content')
<div class="min-h-screen bg-[#F5F5F7] py-12">
    <div class="max-w-3xl mx-auto px-4 space-y-8">
        
        {{-- Section 1: Header --}}
        <div class="text-center space-y-2">
            <h1 class="text-4xl font-bold text-gray-900">设置</h1>
            <p class="text-gray-500 text-lg">管理您的账号与偏好设置</p>
        </div>

        {{-- Section 2: Account Security Card (Collapsible Accordion) --}}
        <div class="bg-white rounded-3xl shadow-sm overflow-hidden">
            {{-- Accordion Header --}}
            <div 
                onclick="toggleAccordion()" 
                class="p-8 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition-colors"
            >
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-900">账号安全</h2>
                </div>
                <svg 
                    id="accordion-chevron" 
                    class="w-5 h-5 text-gray-400 transition-transform duration-300 ease-in-out" 
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>

            {{-- Accordion Body (Hidden by default) --}}
            <div 
                id="accordion-body" 
                class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out"
            >
                <div class="px-8 pb-8">
                    <form id="password-form" class="space-y-6">
                        @csrf
                        
                        {{-- Current Password --}}
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">当前密码</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="current_password" 
                                    name="current_password"
                                    class="w-full bg-gray-50 border-0 rounded-2xl py-4 px-5 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                                    placeholder="请输入当前密码"
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword('current_password', this)" 
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 transition-colors p-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- New Password --}}
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">新密码</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="new_password" 
                                    name="new_password"
                                    class="w-full bg-gray-50 border-0 rounded-2xl py-4 px-5 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                                    placeholder="新密码"
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword('new_password', this)" 
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 transition-colors p-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Confirm New Password --}}
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">确认新密码</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="new_password_confirmation" 
                                    name="new_password_confirmation"
                                    class="w-full bg-gray-50 border-0 rounded-2xl py-4 px-5 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                                    placeholder="确认新密码"
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword('new_password_confirmation', this)" 
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 transition-colors p-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button 
                            type="button" 
                            onclick="submitPassword()"
                            class="w-full bg-gray-900 text-white rounded-2xl py-4 font-bold hover:bg-gray-800 transition-colors shadow-lg hover:shadow-xl"
                        >
                            保存修改
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Section 3: General Preferences Card (Expanded) --}}
        <div class="bg-white rounded-3xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">通用偏好</h2>
            
            <div class="divide-y divide-gray-100">
                {{-- New Message Notification --}}
                <div class="flex items-center justify-between py-5">
                    <div>
                        <p class="text-gray-900 font-medium">新消息通知</p>
                        <p class="text-sm text-gray-500 mt-1">接收私信和交易状态提醒</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-green-500 rounded-full peer peer-focus:outline-none peer-checked:bg-green-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                    </label>
                </div>

                {{-- Dark Mode --}}
                <div class="flex items-center justify-between py-5">
                    <div>
                        <p class="text-gray-900 font-medium">深色模式</p>
                        <p class="text-sm text-gray-500 mt-1">夜间更护眼</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-focus:outline-none after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                </div>

                {{-- Language --}}
                <div class="flex items-center justify-between py-5">
                    <div>
                        <p class="text-gray-900 font-medium">语言</p>
                        <p class="text-sm text-gray-500 mt-1">简体中文</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>

                {{-- Clear Cache --}}
                <div class="flex items-center justify-between py-5 cursor-pointer hover:bg-gray-50 transition-colors" onclick="clearCache()">
                    <div>
                        <p class="text-gray-900 font-medium">清除缓存</p>
                        <p class="text-sm text-gray-500 mt-1">清理临时文件和缓存数据</p>
                    </div>
                    <div id="cache-spinner" class="hidden">
                        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div id="cache-check" class="hidden text-green-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                {{-- Privacy Settings --}}
                <a href="#" class="flex items-center justify-between py-5 hover:bg-gray-50 transition-colors">
                    <div>
                        <p class="text-gray-900 font-medium">隐私设置</p>
                        <p class="text-sm text-gray-500 mt-1">管理您的隐私和数据权限</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                {{-- About Us --}}
                <a href="#" class="flex items-center justify-between py-5 hover:bg-gray-50 transition-colors">
                    <div>
                        <p class="text-gray-900 font-medium">关于我们</p>
                        <p class="text-sm text-gray-500 mt-1">了解我们的团队和产品信息</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Section 4: Danger Zone (Delete Account) --}}
        <div class="text-center mt-8">
            <button 
                onclick="deleteAccount()" 
                class="text-red-500 font-medium hover:text-red-600 transition-colors"
            >
                注销账号
            </button>
        </div>

        {{-- Toast Notification --}}
        <div 
            id="toast" 
            class="fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-xl shadow-lg transform transition-all duration-300 ease-in-out opacity-0 translate-y-4 pointer-events-none z-50"
        >
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span id="toast-message">操作成功</span>
            </div>
        </div>

    </div>
</div>

<script>
    // Accordion Toggle Function
    function toggleAccordion() {
        const body = document.getElementById('accordion-body');
        const chevron = document.getElementById('accordion-chevron');
        
        if (body.classList.contains('max-h-0')) {
            body.classList.remove('max-h-0');
            body.classList.add('max-h-[800px]');
            chevron.classList.add('rotate-180');
        } else {
            body.classList.add('max-h-0');
            body.classList.remove('max-h-[800px]');
            chevron.classList.remove('rotate-180');
        }
    }

    // Password Visibility Toggle
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        if (!input) return;

        if (input.type === 'password') {
            // Switch to TEXT (Show password)
            input.type = 'text';
            btn.classList.add('text-blue-600');
            // Show Eye Slash icon (meaning "Click to Hide")
            btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a9.04 9.04 0 014.222-.42h.003a9.97 9.97 0 014.222 3.449c1.275 4.057-1.515 8.825-9.543 8.825-1.746 0-3.34-.437-4.741-1.203m0 0l-2.828 2.829M1 1l22 22"></path></svg>';
        } else {
            // Switch to PASSWORD (Hide password)
            input.type = 'password';
            btn.classList.remove('text-blue-600');
            // Show Eye icon (meaning "Click to Show")
            btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>';
        }
    }

    // Submit Password Function
    function submitPassword() {
        const form = document.getElementById('password-form');
        const formData = new FormData(form);
        
        // Disable button during submission
        const submitBtn = form.querySelector('button[type="button"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = '保存中...';

        fetch('{{ route("settings.password.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(async (response) => {
            const data = await response.json();
            if (!response.ok) throw data;
            return data;
        })
        .then(() => {
            submitBtn.textContent = '保存成功！';
            form.reset();
            setTimeout(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }, 2000);
        })
        .catch((error) => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            alert(error.message || error.errors?.general || '更新失败，请稍后重试');
        });
    }

    // Clear Cache Function
    function clearCache() {
        const spinner = document.getElementById('cache-spinner');
        const check = document.getElementById('cache-check');
        
        // Show spinner
        spinner.classList.remove('hidden');
        check.classList.add('hidden');
        
        // Simulate cache clearing (1 second)
        setTimeout(() => {
            spinner.classList.add('hidden');
            check.classList.remove('hidden');
            
            // Show toast
            showToast('缓存已清除');
            
            // Hide check after 2 seconds
            setTimeout(() => {
                check.classList.add('hidden');
            }, 2000);
        }, 1000);
    }

    // Show Toast Notification
    function showToast(message) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        
        toastMessage.textContent = message;
        toast.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
        toast.classList.add('opacity-100', 'translate-y-0');
        
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
            toast.classList.remove('opacity-100', 'translate-y-0');
        }, 2000);
    }

    // Delete Account Function
    function deleteAccount() {
        const confirmed = confirm('Are you sure you want to permanently delete your account? This cannot be undone.');
        
        if (confirmed) {
            // Create a form and submit DELETE request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/profile';
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            // Add method spoofing for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
