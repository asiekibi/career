<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ASI Panel Login</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script>
        function configureTailwind() {
            if (typeof tailwind !== 'undefined') {
                tailwind.config = {
                    darkMode: "class",
                    theme: {
                        extend: {
                            colors: {
                                "primary": "#7c3aed",
                                "secondary": "#3b82f6",
                                "background-light": "#f8fafc",
                                "background-dark": "#0f172a",
                            },
                            fontFamily: {
                                "display": ["'Plus Jakarta Sans'", "sans-serif"]
                            },
                            borderRadius: {
                                "2xl": "1rem",
                                "3xl": "1.5rem",
                                "4xl": "2rem",
                            },
                        },
                    },
                }
            } else {
                setTimeout(configureTailwind, 100);
            }
        }
    </script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries" onload="configureTailwind()"></script>
    <style>
        :root {
            --primary: #7c3aed;
            --primary-dark: #6d28d9;
            --secondary: #3b82f6;
            --accent: #f472b6;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        .mesh-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background:
                radial-gradient(circle at 0% 0%, rgba(124, 58, 237, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 100% 0%, rgba(59, 130, 246, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(244, 114, 182, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 0% 100%, rgba(124, 58, 237, 0.05) 0%, transparent 50%);
            filter: blur(60px);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 1);
            box-shadow:
                0 10px 15px -3px rgba(0, 0, 0, 0.02),
                0 25px 30px -5px rgba(0, 0, 0, 0.04),
                0 0 50px rgba(124, 58, 237, 0.04);
        }

        .input-glow:focus {
            box-shadow: 0 0 15px rgba(124, 58, 237, 0.1);
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-slate-50 font-display text-slate-800 antialiased">
    <div class="mesh-gradient"></div>

    <div class="flex min-h-screen items-center justify-center px-4 py-12 sm:py-20">
        <div class="w-full max-w-[480px]">
            <div class="mb-10 text-center float-animation">
                <div
                    class="inline-flex h-20 w-20 sm:h-24 sm:w-24 items-center justify-center rounded-3xl bg-white shadow-2xl shadow-violet-200/50 mb-6 border border-white p-4">
                    <img class="h-full w-full object-contain" src="{{ asset('logo/logo.png') }}" alt="ASI Logo">
                </div>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-slate-900 mb-3">
                    ASI Kariyer <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-indigo-600">Portalı</span>
                </h1>
                <p class="text-slate-500 text-sm sm:text-base font-medium max-w-xs mx-auto">
                    Geleceğinize yön veren profesyonel kariyer ekosistemi.
                </p>
            </div>

            @if (session('status') || session('success'))
                <div class="mb-8 rounded-3xl border border-emerald-100 bg-emerald-50/50 p-5 shadow-sm backdrop-blur-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="ml-3 text-sm font-semibold text-emerald-800">
                            {{ session('status') ?? session('success') }}
                        </p>
                    </div>
                </div>
            @endif

            <div class="glass-card rounded-[3rem] p-8 sm:p-12">
                <div class="bg-slate-100/80 p-1.5 rounded-2xl mb-10">
                    <nav class="flex space-x-1" aria-label="Tabs" role="tablist">
                        <button type="button" onclick="switchTab('user')" id="user-tab" role="tab" aria-selected="true"
                            class="tab-button active-tab flex-1 whitespace-nowrap rounded-xl py-3.5 px-4 text-sm font-bold transition-all duration-300 bg-white shadow-md text-violet-600 border border-slate-200/50">
                            Kullanıcı
                        </button>
                        <button type="button" onclick="switchTab('company')" id="company-tab" role="tab"
                            aria-selected="false"
                            class="tab-button inactive-tab flex-1 whitespace-nowrap rounded-xl py-3.5 px-4 text-sm font-bold transition-all duration-300 text-slate-500 hover:text-slate-700">
                            Firma
                        </button>
                    </nav>
                </div>
                <form action="{{ route('login') }}" class="space-y-5 tab-content" id="user-loginForm" method="POST">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 ml-1"
                                for="user-email-address">Email Adresi</label>
                            <input autocomplete="email"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:ring-0 transition-all sm:text-sm @error('email') border-red-500 @enderror"
                                id="user-email-address" name="email" placeholder="ahmet@example.com" required
                                type="email" value="{{ old('form_type') === 'register' ? '' : old('email') }}" />
                            @if (old('form_type') !== 'register')
                                @error('email')
                                    <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 ml-1"
                                for="user-password">Şifre</label>
                            <div class="relative">
                                <input autocomplete="current-password"
                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:ring-0 transition-all sm:text-sm @error('password') border-red-500 @enderror"
                                    id="user-password" name="password" placeholder="••••••••" required
                                    type="password" />
                                <button type="button"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-violet-600 transition-colors"
                                    onclick="togglePassword('user-password', 'user-eye-icon', 'user-eye-off-icon')">
                                    <svg id="user-eye-icon" class="h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg id="user-eye-off-icon" class="h-5 w-5 hidden" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path
                                            d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                        </path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                            @if (old('form_type') !== 'register')
                                @error('password')
                                    <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1 text-xs">
                        <button type="button" class="font-bold text-primary hover:text-primary/80 transition-colors"
                            onclick="switchTab('register')">
                            Kayıt Olun
                        </button>
                        <a class="font-bold text-gray-400 hover:text-primary transition-colors"
                            href="{{ route('password.request') }}">
                            Şifremi Unuttum
                        </a>
                    </div>

                    <button
                        class="flex w-full justify-center rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 py-4 px-4 text-sm font-bold text-white shadow-lg shadow-violet-200 hover:shadow-violet-300 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200"
                        type="submit">
                        Giriş Yap
                    </button>

                    <div class="relative py-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-100"></div>
                        </div>
                        <div
                            class="relative flex justify-center text-[11px] font-black uppercase tracking-[0.3em] text-slate-300">
                            <span class="bg-white/50 px-5 backdrop-blur-sm">veya</span>
                        </div>
                    </div>

                    <a href="{{ route('google.redirect') }}"
                        class="flex w-full items-center justify-center gap-4 rounded-2xl border border-slate-200 bg-white py-4 px-4 text-sm font-bold text-slate-700 transition-all hover:bg-slate-50 hover:border-slate-300 hover:shadow-md active:scale-[0.98]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#4285F4"
                                d="M21.6 12.23c0-.78-.07-1.53-.2-2.23H12v4.22h5.38a4.6 4.6 0 0 1-2 3.02v2.51h3.24c1.89-1.74 2.98-4.31 2.98-7.52z" />
                            <path fill="#34A853"
                                d="M12 22c2.7 0 4.96-.89 6.62-2.42l-3.24-2.51c-.9.6-2.04.95-3.38.95-2.6 0-4.8-1.75-5.59-4.11H3.06v2.59A10 10 0 0 0 12 22z" />
                            <path fill="#FBBC05"
                                d="M6.41 13.91A6 6 0 0 1 6.1 12c0-.66.11-1.31.31-1.91V7.5H3.06A10 10 0 0 0 2 12c0 1.61.39 3.14 1.06 4.5l3.35-2.59z" />
                            <path fill="#EA4335"
                                d="M12 5.98c1.47 0 2.79.51 3.83 1.5l2.87-2.87C16.96 2.99 14.7 2 12 2A10 10 0 0 0 3.06 7.5l3.35 2.59C7.2 7.73 9.4 5.98 12 5.98z" />
                        </svg>
                        Google ile devam et
                    </a>
                </form>

                <form action="{{ route('company-portal.login') }}" class="space-y-5 tab-content hidden"
                    id="company-loginForm" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 ml-1"
                                for="company-email-address">Firma Email</label>
                            <input autocomplete="email"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:ring-0 transition-all sm:text-sm @error('email') border-red-500 @enderror"
                                id="company-email-address" name="email" placeholder="sirket@domain.com" required
                                type="email" value="{{ old('email') }}" />
                            @error('email')
                                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 ml-1"
                                for="company-password">Şifre</label>
                            <div class="relative">
                                <input autocomplete="current-password"
                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:ring-0 transition-all sm:text-sm @error('password') border-red-500 @enderror"
                                    id="company-password" name="password" placeholder="••••••••" required
                                    type="password" />
                                <button type="button"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-violet-600 transition-colors"
                                    onclick="togglePassword('company-password', 'company-eye-icon', 'company-eye-off-icon')">
                                    <svg id="company-eye-icon" class="h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg id="company-eye-off-icon" class="h-5 w-5 hidden" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path
                                            d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                        </path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1 text-xs">
                        <a class="font-bold text-primary hover:text-primary/80 transition-colors"
                            href="{{ route('company-request.form') }}" target="_blank" rel="noopener noreferrer">
                            Firma Başvurusu
                        </a>
                        <a class="font-bold text-gray-400 hover:text-primary transition-colors"
                            href="{{ route('password.request') }}">
                            Şifremi Unuttum
                        </a>
                    </div>

                    <button
                        class="flex w-full justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 py-4 px-4 text-sm font-bold text-white shadow-lg shadow-blue-100 hover:shadow-blue-200 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200"
                        type="submit">
                        Firma Girişi
                    </button>
                </form>

                <form action="{{ route('register') }}" class="space-y-5 tab-content hidden" id="registerForm"
                    method="POST">
                    @csrf
                    <input type="hidden" name="form_type" value="register">

                    <div class="flex items-center justify-between mb-2 px-1">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Yeni Hesap
                        </h3>
                        <button type="button"
                            class="text-xs font-bold text-primary hover:text-primary/80 transition-colors"
                            onclick="switchTab('user')">
                            Giriş Yap
                        </button>
                    </div>

                    <div class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1 ml-1"
                                    for="register-full-name">Ad Soyad</label>
                                <input
                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:ring-0 transition-all text-sm @error('full_name') border-red-500 @enderror"
                                    id="register-full-name" name="full_name" placeholder="Ad Soyad" required type="text"
                                    value="{{ old('full_name') }}" />
                                @error('full_name')
                                    <p class="mt-1 text-[10px] text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1 ml-1"
                                    for="register-email-address">Email</label>
                                <input autocomplete="email"
                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:ring-0 transition-all text-sm @error('email') border-red-500 @enderror"
                                    id="register-email-address" name="email" placeholder="Email adresi" required
                                    type="email" value="{{ old('form_type') === 'register' ? old('email') : '' }}" />
                                @if (old('form_type') === 'register')
                                    @error('email')
                                        <p class="mt-1 text-[10px] text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1 ml-1"
                                    for="register-gsm">Telefon</label>
                                <input autocomplete="tel"
                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:ring-0 transition-all text-sm @error('gsm') border-red-500 @enderror"
                                    id="register-gsm" name="gsm" placeholder="05xx xxx xx xx" required type="tel"
                                    value="{{ old('gsm') }}" />
                                @error('gsm')
                                    <p class="mt-1 text-[10px] text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1 ml-1"
                                    for="register-birth-date">Doğum Tarihi</label>
                                <input
                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:ring-0 transition-all text-sm @error('birth_date') border-red-500 @enderror"
                                    id="register-birth-date" name="birth_date" required type="date"
                                    value="{{ old('birth_date') }}" />
                                @error('birth_date')
                                    <p class="mt-1 text-[10px] text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1 ml-1"
                                    for="register-password">Şifre</label>
                                <div class="relative">
                                    <input autocomplete="new-password"
                                        class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 pr-12 text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:ring-0 transition-all text-sm @error('password') border-red-500 @enderror"
                                        id="register-password" name="password" placeholder="••••••••" required
                                        type="password" />
                                    <button type="button"
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-violet-600 transition-colors"
                                        onclick="togglePassword('register-password', 'register-eye-icon', 'register-eye-off-icon')">
                                        <svg id="register-eye-icon" class="h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <svg id="register-eye-off-icon" class="h-4 w-4 hidden" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path
                                                d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                            </path>
                                            <line x1="1" y1="1" x2="23" y2="23"></line>
                                        </svg>
                                    </button>
                                </div>
                                @if (old('form_type') === 'register')
                                    @error('password')
                                        <p class="mt-1 text-[10px] text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                @endif
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1 ml-1"
                                    for="register-password-confirmation">Şifre Tekrar</label>
                                <input autocomplete="new-password"
                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:ring-0 transition-all text-sm"
                                    id="register-password-confirmation" name="password_confirmation"
                                    placeholder="••••••••" required type="password" />
                            </div>
                        </div>
                    </div>

                    <button
                        class="flex w-full justify-center rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 py-4 px-4 text-sm font-bold text-white shadow-lg shadow-violet-100 hover:shadow-violet-200 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 mt-4"
                        type="submit">
                        Kaydı Tamamla
                    </button>
                </form>

            </div>
        </div>

        <script>
            function togglePassword(passwordId, eyeIconId, eyeOffIconId) {
                const passwordInput = document.getElementById(passwordId);
                const eyeIcon = document.getElementById(eyeIconId);
                const eyeOffIcon = document.getElementById(eyeOffIconId);

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.classList.add('hidden');
                    eyeOffIcon.classList.remove('hidden');
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.classList.remove('hidden');
                    eyeOffIcon.classList.add('hidden');
                }
            }

            function setTabButtonState(button, isActive) {
                button.classList.toggle('active-tab', isActive);
                button.classList.toggle('bg-primary/10', isActive);
                button.classList.toggle('border-primary', isActive);
                button.classList.toggle('text-primary', isActive);
                button.classList.toggle('inactive-tab', !isActive);
                button.classList.toggle('border-transparent', !isActive);
                button.classList.toggle('text-gray-600', !isActive);
                button.classList.toggle('dark:text-gray-400', !isActive);
                button.setAttribute('aria-selected', isActive ? 'true' : 'false');
            }

            function switchTab(tab) {
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                document.querySelectorAll('.tab-button').forEach(button => {
                    setTabButtonState(button, false);
                });

                if (tab === 'user') {
                    document.getElementById('user-loginForm').classList.remove('hidden');
                    setTabButtonState(document.getElementById('user-tab'), true);
                } else if (tab === 'company') {
                    document.getElementById('company-loginForm').classList.remove('hidden');
                    setTabButtonState(document.getElementById('company-tab'), true);
                } else if (tab === 'register') {
                    document.getElementById('registerForm').classList.remove('hidden');
                    setTabButtonState(document.getElementById('user-tab'), true);
                }
            }

            document.getElementById('user-loginForm').addEventListener('submit', function () {
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Giriş yapılıyor...';
            });

            document.getElementById('company-loginForm').addEventListener('submit', function () {
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Giriş yapılıyor...';
            });

            document.getElementById('registerForm').addEventListener('submit', function () {
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Kayıt oluşturuluyor...';
            });

            if (@json(old('form_type')) === 'register') {
                switchTab('register');
            }
        </script>
</body>

</html>