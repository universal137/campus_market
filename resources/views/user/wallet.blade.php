@extends('layouts.app')

@section('title', '我的钱包 - 校园易')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">我的钱包</h1>
                <p class="text-sm text-gray-500 mt-2">当前账号：{{ $user->name }}</p>
            </div>
            <button id="openRechargeModal"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white rounded-full shadow-lg hover:scale-[1.02] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                立即充值
            </button>
        </div>

        <div class="mt-8 bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-800 text-white rounded-3xl p-8 shadow-2xl">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-white/70 text-sm mb-3">可用余额</p>
                    <p id="balanceDisplay" data-balance="{{ number_format($user->balance, 2, '.', '') }}"
                        class="text-5xl font-black tracking-tight">￥{{ number_format($user->balance, 2) }}</p>
                    <p class="text-white/60 text-xs mt-4">更新时间：{{ now()->format('Y-m-d H:i') }}</p>
                </div>
                <div class="text-right text-white/80 text-sm">
                    <p>用户 ID：{{ $user->id }}</p>
                    <p>账单总数：{{ $transactions->total() }}</p>
                </div>
            </div>
        </div>

        <div class="max-w-3xl mx-auto mt-12">
            <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                <h2 class="text-xl font-semibold text-gray-900">账单明细</h2>
                <div class="relative bg-gray-100 rounded-full p-1 flex items-center w-fit">
                    <div id="filterSwitchBg"
                        class="absolute top-1 left-1 h-[calc(100%-8px)] w-[calc(33.33%-4px)] rounded-full bg-white shadow-sm transition-all duration-300"></div>
                    <button type="button" class="filter-switch-btn relative z-10 px-4 py-1 text-xs font-bold text-gray-900 w-16 text-center"
                        onclick="filterTransactions('all', this)">全部</button>
                    <button type="button" class="filter-switch-btn relative z-10 px-4 py-1 text-xs font-bold text-gray-500 w-16 text-center"
                        onclick="filterTransactions('income', this)">收入</button>
                    <button type="button" class="filter-switch-btn relative z-10 px-4 py-1 text-xs font-bold text-gray-500 w-16 text-center"
                        onclick="filterTransactions('expense', this)">支出</button>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm divide-y" id="transactionsList">
                @forelse ($transactions as $transaction)
                    @php
                        $isIncome = in_array($transaction->type, ['deposit', 'income', 'refund']);
                        $typeLabels = [
                            'deposit' => '充值',
                            'payment' => '支出',
                            'income' => '收入',
                            'refund' => '退款',
                        ];
                        $iconBg = $isIncome ? 'bg-rose-50 text-rose-500' : 'bg-gray-100 text-gray-600';
                        $amountClass = $isIncome ? 'text-rose-500' : 'text-gray-800';
                        $operator = $isIncome ? '+' : '-';
                    @endphp
                    <div class="flex items-center justify-between px-6 py-4 transaction-row transaction-item"
                        data-type="{{ $transaction->type === 'payment' ? 'expense' : 'income' }}">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $iconBg }}">
                                @if ($isIncome)
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 19V5"></path>
                                        <path d="m5 12 7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 5v14"></path>
                                        <path d="m19 12-7 7-7-7"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $transaction->description ?? ($typeLabels[$transaction->type] ?? '账单') }}</p>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-[11px]">
                                        {{ $typeLabels[$transaction->type] ?? '记录' }}
                                    </span>
                                    <span>{{ $transaction->created_at->format('Y-m-d H:i') }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold {{ $amountClass }}">{{ $operator }}{{ number_format($transaction->amount, 2) }}</p>
                            @if ($transaction->reference_id)
                                <p class="text-xs text-gray-400">单号：{{ $transaction->reference_id }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div id="transactionsEmptyState" class="text-center py-14">
                        <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 8v8"></path>
                                <path d="M8 12h8"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                        <p class="text-gray-600 font-medium">暂时还没有账单记录</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $transactions->links('pagination.simple-tailwind') }}
            </div>
        </div>
    </div>

    {{-- 充值弹窗 --}}
    <div id="rechargeModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl p-8 relative">
            <button id="closeRechargeModal"
                class="absolute top-5 right-5 text-gray-400 hover:text-gray-600 transition" aria-label="关闭弹窗">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-900">余额充值</h3>
                <p class="text-sm text-gray-500 mt-1">选择金额与支付方式，完成后即刻到账</p>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-600 mb-3">快捷金额</p>
                <div class="grid grid-cols-3 gap-3" id="presetAmountButtons">
                    @foreach ([20, 50, 100, 200, 500, 1000] as $preset)
                        <button type="button"
                            class="preset-btn rounded-2xl border border-gray-200 px-4 py-3 text-lg font-semibold text-gray-700 bg-white hover:border-gray-400 transition"
                            data-amount="{{ $preset }}">
                            ￥{{ number_format($preset, 0) }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mt-6">
                <p class="text-sm font-medium text-gray-600 mb-3">自定义金额</p>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-semibold pointer-events-none">￥</span>
                    <input type="number" min="0" step="0.01" id="customAmountInput"
                        class="w-full pl-10 pr-4 py-3 rounded-2xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 text-lg"
                        placeholder="输入金额，至少 0.01">
                </div>
            </div>

            <div class="mt-8">
                <p class="text-sm font-medium text-gray-600 mb-3">支付方式</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3" id="paymentMethodButtons">
                    <button type="button" class="payment-method-card active" data-method="wechat">
                        <div class="icon-circle bg-green-100 text-green-600">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.94 10.62c.2-.49.31-1 .31-1.51C19.25 6.5 16.48 4 13 4S6.75 6.5 6.75 9.11 9.51 13.21 13 13.21c.71 0 1.4-.07 2.05-.2l2.34 1.41-.45-1.8zM9.5 8.5a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5zm5 0a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-800">微信支付</span>
                    </button>

                    <button type="button" class="payment-method-card" data-method="alipay">
                        <div class="icon-circle bg-blue-100 text-blue-600">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm6.8 10.6c.8.3 1.5.5 2.2.7a8.1 8.1 0 0 0 2.3-2.2l-1-.36-.64 1.06a13.6 13.6 0 0 1-2.53-.78zM16 8.19c.29 0 .52.23.52.52a.52.52 0 0 1-.52.53h-2.98v1.07h2.54c.29 0 .53.23.53.52 0 .3-.24.53-.53.53h-2.54V13h-1.35v-1.64H10.2c-.29 0-.53-.23-.53-.53 0-.29.24-.52.53-.52h1.65V9.35H9.96c-.29 0-.52-.24-.52-.53a.52.52 0 0 1 .52-.52h2V7h1.35v1.09z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-800">支付宝</span>
                    </button>

                    <button type="button" class="payment-method-card" data-method="bank_card">
                        <div class="icon-circle bg-rose-100 text-rose-600">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 7a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v3H3zm0 5h18v5a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3zm4 3v2h2v-2zm5 0v2h4v-2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-800">银行卡</span>
                    </button>

                    <button type="button" class="payment-method-card" data-method="campus_card">
                        <div class="icon-circle bg-amber-100 text-amber-600">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4 6h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm1 3v2h14V9zm0 4v3h5v-3z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-800">校园卡</span>
                    </button>
                </div>
            </div>

            <div class="mt-8">
                <button id="confirmRechargeButton"
                    class="w-full py-3.5 rounded-2xl bg-gray-900 text-white font-semibold shadow-lg hover:-translate-y-0.5 transition flex items-center justify-center gap-3">
                    <span class="spinner hidden">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4z"></path>
                        </svg>
                    </span>
                    <span class="success hidden">
                        <svg class="w-5 h-5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5"></path>
                        </svg>
                    </span>
                    <span id="confirmButtonLabel">请选择金额与方式</span>
                </button>
                <p id="rechargeFeedback" class="text-center text-sm mt-3 text-gray-500"></p>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <style>
        .payment-method-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 1.25rem;
            padding: 1rem;
            background: #fff;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .payment-method-card:hover {
            border-color: #9ca3af;
        }

        .payment-method-card.active {
            border-color: #111827;
            box-shadow: 0 0 0 4px rgba(17, 24, 39, 0.1);
            transform: scale(1.02);
        }

        .icon-circle {
            width: 3rem;
            height: 3rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .animate-slide-down {
            animation: slideDownFade 0.4s ease forwards;
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.35s ease forwards;
        }

        @keyframes slideDownFade {
            0% {
                opacity: 0;
                transform: translateY(-12px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(6px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('rechargeModal');
            const openBtn = document.getElementById('openRechargeModal');
            const closeBtn = document.getElementById('closeRechargeModal');
            const confirmBtn = document.getElementById('confirmRechargeButton');
            const spinner = confirmBtn.querySelector('.spinner');
            const successIcon = confirmBtn.querySelector('.success');
            const confirmLabel = document.getElementById('confirmButtonLabel');
            const feedbackEl = document.getElementById('rechargeFeedback');
            const balanceDisplay = document.getElementById('balanceDisplay');
            const transactionsList = document.getElementById('transactionsList');
            let emptyState = document.getElementById('transactionsEmptyState');
            const presetButtons = document.querySelectorAll('.preset-btn');
            const paymentMethodButtons = document.querySelectorAll('.payment-method-card');
            const customInput = document.getElementById('customAmountInput');
            const filterButtons = document.querySelectorAll('.filter-switch-btn');
            const filterSwitchBg = document.getElementById('filterSwitchBg');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const rechargeUrl = "{{ route('user.wallet.recharge') }}";

            let selectedPresetAmount = null;
            let selectedMethod = 'wechat';
            let activeFilter = 'all';
            let activeFilterButton = filterButtons[0] || null;

            const methodLabelMap = {
                wechat: '微信支付',
                alipay: '支付宝',
                bank_card: '银行卡',
                campus_card: '校园卡',
            };

            const openModal = () => {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                resetState();
            };

            const resetState = () => {
                spinner.classList.add('hidden');
                successIcon.classList.add('hidden');
                confirmBtn.disabled = false;
                confirmLabel.textContent = '请选择金额与方式';
                feedbackEl.textContent = '';
                selectedPresetAmount = null;
                customInput.value = '';
                highlightPreset(null);
                updateConfirmLabel();
            };

            const highlightPreset = (button) => {
                presetButtons.forEach(btn => btn.classList.remove('ring-2', 'ring-gray-900'));
                if (button) {
                    button.classList.add('ring-2', 'ring-gray-900');
                }
            };

            const highlightMethod = () => {
                paymentMethodButtons.forEach(btn => {
                    if (btn.dataset.method === selectedMethod) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            };

            const getSelectedAmount = () => {
                const customValue = parseFloat(customInput.value);
                return selectedPresetAmount ?? customValue;
            };

            const updateConfirmLabel = () => {
                const amount = getSelectedAmount();
                const methodLabel = methodLabelMap[selectedMethod] || '当前方式';
                if (amount && amount >= 0.01) {
                    confirmLabel.textContent = `使用${methodLabel}支付 ${amount.toFixed(2)} 元`;
                } else {
                    confirmLabel.textContent = `请选择金额并使用${methodLabel}`;
                }
            };

            const animateBalance = (from, to) => {
                const duration = 800;
                const start = performance.now();
                const step = (now) => {
                    const progress = Math.min((now - start) / duration, 1);
                    const value = from + (to - from) * progress;
                    balanceDisplay.textContent = `￥${value.toFixed(2)}`;
                    balanceDisplay.dataset.balance = value.toFixed(2);
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    }
                };
                requestAnimationFrame(step);
            };

            const buildTransactionRow = (payload) => {
                const isIncome = ['deposit', 'income', 'refund'].includes(payload.type);
                const iconBg = isIncome ? 'bg-rose-50 text-rose-500' : 'bg-gray-100 text-gray-600';
                const amountClass = isIncome ? 'text-rose-500' : 'text-gray-800';
                const icon = isIncome
                    ? `<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5"></path><path d="m5 12 7-7 7 7"></path></svg>`
                    : `<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"></path><path d="m19 12-7 7-7-7"></path></svg>`;

                const wrapper = document.createElement('div');
                wrapper.className = 'flex items-center justify-between px-6 py-4 transaction-row transaction-item animate-slide-down';
                wrapper.dataset.type = payload.type === 'payment' ? 'expense' : 'income';
                wrapper.innerHTML = `
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center ${iconBg}">
                            ${icon}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">${payload.description}</p>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-[11px]">${isIncome ? '充值' : '支出'}</span>
                                <span>${payload.created_at_formatted}</span>
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-semibold ${amountClass}">${payload.amount_formatted}</p>
                    </div>
                `;
                return wrapper;
            };

            const prependTransaction = (payload) => {
                if (emptyState) {
                    emptyState.remove();
                    emptyState = null;
                }
                const row = buildTransactionRow(payload);
                if (transactionsList) {
                    transactionsList.prepend(row);
                    filterTransactions(activeFilter);
                }
            };

            window.filterTransactions = (type, button = null) => {
                activeFilter = type;
                if (button) {
                    activeFilterButton = button;
                }

                if (filterSwitchBg && activeFilterButton) {
                    const index = Array.from(filterButtons).indexOf(activeFilterButton);
                    if (index >= 0) {
                        filterSwitchBg.style.transform = `translateX(${index * 100}%)`;
                    }
                }

                filterButtons.forEach(btn => {
                    if (btn === activeFilterButton) {
                        btn.classList.remove('text-gray-500');
                        btn.classList.add('text-gray-900');
                    } else {
                        btn.classList.remove('text-gray-900');
                        btn.classList.add('text-gray-500');
                    }
                });

                document.querySelectorAll('.transaction-item').forEach(item => {
                    const match = activeFilter === 'all' || item.dataset.type === activeFilter;
                    if (match) {
                        item.classList.remove('hidden');
                        item.classList.remove('animate-fade-in-up');
                        void item.offsetWidth;
                        item.classList.add('animate-fade-in-up');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            };

            openBtn.addEventListener('click', openModal);
            closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            presetButtons.forEach(button => {
                button.addEventListener('click', () => {
                    selectedPresetAmount = parseFloat(button.dataset.amount);
                    customInput.value = '';
                    highlightPreset(button);
                    updateConfirmLabel();
                });
            });

            customInput.addEventListener('input', () => {
                selectedPresetAmount = null;
                highlightPreset(null);
                updateConfirmLabel();
            });

            paymentMethodButtons.forEach(button => {
                button.addEventListener('click', () => {
                    selectedMethod = button.dataset.method;
                    highlightMethod();
                    updateConfirmLabel();
                });
            });

            highlightMethod();
            updateConfirmLabel();
            if (filterButtons.length) {
                filterTransactions('all', filterButtons[0]);
            }

            confirmBtn.addEventListener('click', async () => {
                const amount = getSelectedAmount();

                if (!amount || amount < 0.01) {
                    feedbackEl.textContent = '请输入有效的充值金额 (≥ 0.01)';
                    feedbackEl.classList.add('text-rose-500');
                    return;
                }

                confirmBtn.disabled = true;
                spinner.classList.remove('hidden');
                confirmLabel.textContent = '处理中...';
                feedbackEl.textContent = '正在请求充值，请稍候';
                feedbackEl.classList.remove('text-rose-500');

                try {
                    const response = await fetch(rechargeUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            amount: amount,
                            payment_method: selectedMethod,
                        }),
                    });

                    if (!response.ok) {
                        throw new Error('充值请求失败');
                    }

                    const data = await response.json();
                    spinner.classList.add('hidden');
                    successIcon.classList.remove('hidden');
                    confirmLabel.textContent = '充值成功';
                    feedbackEl.textContent = '余额已更新';

                    const currentBalance = parseFloat(balanceDisplay.dataset.balance);
                    animateBalance(currentBalance, parseFloat(data.new_balance));
                    prependTransaction(data.transaction);

                    setTimeout(() => {
                        closeModal();
                    }, 1200);
                } catch (error) {
                    spinner.classList.add('hidden');
                    confirmBtn.disabled = false;
                    confirmLabel.textContent = '重新尝试';
                    feedbackEl.textContent = error.message || '网络异常，请稍后重试';
                    feedbackEl.classList.add('text-rose-500');
                }
            });
        });
    </script>
@endpush

