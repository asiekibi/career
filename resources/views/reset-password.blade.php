<!DOCTYPE html>
<html class="" lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Şifre Sıfırla</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
            extend: {
                colors: {
                "primary": "#1173d4",
                "background-light": "#f6f7f8",
                "background-dark": "#101922",
                },
                fontFamily: {
                "display": ["Inter"]
                },
                borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
            },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<!--reset password page body-->
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <div class="flex min-h-screen items-center justify-center">
        <div class="w-full max-w-md space-y-8 p-8">
            <!--reset password page body content title-->
            <div class="text-center">
                <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Yeni Şifre Belirleyin
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                    Yeni şifrenizi girin ve onaylayın.
                </p>
            </div>

            <!-- Success Message -->
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!--reset password page body content form-->
            <form action="{{ route('password.update') }}" class="mt-8 space-y-6" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="space-y-4 rounded-lg">
                    <div>
                        <label class="sr-only" for="email">Email</label>
                        <input autocomplete="email" 
                            class="relative block w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm" 
                            id="email" 
                            name="email" 
                            type="email"
                            value="{{ $email }}"
                            readonly/>
                    </div>
                    
                    <div>
                        <label class="sr-only" for="password">Yeni Şifre</label>
                        <div class="relative">
                            <input autocomplete="new-password" 
                                class="relative block w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-3 pr-12 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm @error('password') border-red-500 @enderror" 
                                id="password" 
                                name="password" 
                                placeholder="Yeni şifre" 
                                required="" 
                                type="password"/>
                            <button type="button" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors duration-200"
                                onclick="togglePassword('password')">
                                <!-- Açık göz ikonu -->
                                <svg id="eye-icon-password" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <!-- Kapalı göz ikonu -->
                                <svg id="eye-off-icon-password" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="sr-only" for="password_confirmation">Şifre Onayı</label>
                        <div class="relative">
                            <input autocomplete="new-password" 
                                class="relative block w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-3 pr-12 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm @error('password_confirmation') border-red-500 @enderror" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="Şifre onayı" 
                                required="" 
                                type="password"/>
                            <button type="button" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors duration-200"
                                onclick="togglePassword('password_confirmation')">
                                <!-- Açık göz ikonu -->
                                <svg id="eye-icon-password_confirmation" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <!-- Kapalı göz ikonu -->
                                <svg id="eye-off-icon-password_confirmation" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <button class="group relative flex w-full justify-center rounded-lg border border-transparent bg-primary py-3 px-4 text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark" type="submit">
                            Şifreyi Sıfırla
                        </button>
                    </div>
                    
                    <div class="text-center text-sm">
                        <a class="font-medium text-primary hover:text-primary/80" href="{{ route('login') }}">
                            Giriş ekranına geri dön
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById('eye-icon-' + inputId);
            const eyeOffIcon = document.getElementById('eye-off-icon-' + inputId);
            
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
    </script>
</body>
</html>
