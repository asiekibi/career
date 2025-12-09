<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Sertifika Doğrulama</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // Tailwind config'i önce tanımla
        window.tailwindConfig = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#11d411",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102210",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        };
    </script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        // Tailwind yüklendikten sonra config'i uygula
        if (typeof tailwind !== 'undefined' && window.tailwindConfig) {
            tailwind.config = window.tailwindConfig;
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Fallback stiller */
        .bg-background-light {
            background-color: #f6f8f6;
        }
        .bg-background-dark {
            background-color: #102210;
        }
        .text-primary {
            color: #11d411;
        }
        .bg-primary {
            background-color: #11d411;
        }
    </style>
</head>

<!-- main content -->
<body class="bg-background-light dark:bg-background-dark font-display text-gray-900 dark:text-gray-100">
    <div class="flex flex-col min-h-screen">
        <header class="border-b border-gray-200 dark:border-gray-800">
            <!-- header -->
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-4">
                        <div class="text-primary">
                            <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold">SertifikaSorgula</h2>
                    </div>
                </div>
            </div>
        </header>

        <!-- main content -->
        <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md space-y-8">
                <div class="text-center">
                    <h1 class="text-4xl font-extrabold tracking-tight">Firma Girişi</h1>
                    <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">Firma hesabınıza giriş yapın</p>
                </div>
                
                <form action="{{ route('company-portal.login') }}" method="POST" class="space-y-6" id="loginForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="sr-only" for="email">Email</label>
                            <input autocomplete="email" 
                                id="email" 
                                name="email" 
                                type="email" 
                                placeholder="Email adresi" 
                                required
                                value="{{ old('email') }}"
                                class="w-full h-14 pl-4 pr-4 bg-background-light dark:bg-background-dark border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-primary focus:border-primary text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 @error('email') border-red-500 @enderror"/>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="relative">
                            <input autocomplete="current-password" 
                                id="password" 
                                name="password" 
                                type="password" 
                                placeholder="Şifre" 
                                required
                                class="w-full h-14 pl-4 pr-12 bg-background-light dark:bg-background-dark border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-primary focus:border-primary text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 @error('password') border-red-500 @enderror"/>
                            <button type="button" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors duration-200"
                                onclick="togglePassword()">
                                <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg id="eye-off-icon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-black bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Giriş Yap
                    </button>
                </form>
                
                <!-- Hata mesajı -->
                <div id="errorMessage" class="text-center space-y-6 pt-6 hidden">
                    <div class="flex justify-center">
                        <svg class="h-16 w-16 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <p id="errorTitle" class="text-xl font-bold">Girilen bilgiler ile kayıt bulunamadı.</p>
                        <p id="errorDescription" class="text-gray-500 dark:text-gray-400">Lütfen bilgilerinizi kontrol edip tekrar deneyin.</p>
                    </div>
                </div>
                
                <!-- Başarılı sonuç -->
                <div id="successResult" class="pt-6 hidden">
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
                        <div class="relative h-48 w-full bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD5P26mZNldhffbC9SQb803JfXreNDhcj168GOtC_C4vt1wSUR96bzYR5MQj5kQd3gL_zdJUo8OizY1IFb9mIwQljWxDgvgAFti32RrH2J3AWtpoAuYGl48giyynttJM-GQS0OXGgL9S1S27sWT4o23-DpFgudSsJjZ-wn-wHD5T8oKywJ6NoLqkX30GSdCI3UIRYk3kU879sov8gAtuVGMEnn1QMs8O7UXoYy8MlNtqCgt2sGF7evnpOuGplvUT6ePBcDbQ_plP3g");'>
                            <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                            <div class="absolute bottom-4 left-4">
                                <img id="studentPhoto" alt="Profil Fotoğrafı" class="h-24 w-24 rounded-full border-4 border-white dark:border-gray-800" src=""/>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 id="studentName" class="text-2xl font-bold"></h3>
                            <p class="text-primary font-semibold">Öğrenci</p>
                            <p class="mt-4 text-gray-600 dark:text-gray-300">
                                Sertifika başarıyla doğrulandı. Öğrencinin bilgilerini aşağıda görebilirsiniz.
                            </p>
                            <div class="mt-6">
                                <button id="viewCvBtn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-black bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    Özgeçmişini Gör
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');
            
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

        // Form submit loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const form = this;
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Giriş yapılıyor...';
        });
    </script>
</body>
</html>