<!DOCTYPE html>
<html lang="tr">
    <!--öğrenci portal login page-->
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sertifika Doğrulama</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
    <script>
            function configureTailwind() {
                if (typeof tailwind !== 'undefined') {
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
                } else {
                    setTimeout(configureTailwind, 100);
                }
        }
    </script>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries" onload="configureTailwind()"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

    <!-- login page body-->
    <body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
        <!--login page body content-->
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="w-full max-w-md space-y-8">

                <!--login page body content title-->
                <div class="text-center">
                    <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Sertifika Doğrulama
                    </h2>
                    <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                        Sertifikanızı doğrulamak için bilgilerinizi girin
                    </p>
                </div>
                
                <!-- Hata mesajı gösterimi -->
                <div id="errorMessage" class="hidden mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-sm text-red-600 dark:text-red-400" id="errorText"></p>
                </div>
                
                <!--login page body content form-->
                <form action="{{ route('portal.search') }}" class="mt-8 space-y-6" method="POST" id="searchForm">
                    @csrf

                    <!--login page body content form inputs-->
                    <div class="space-y-4">
                        <div>
                            <label class="sr-only" for="full_name">Ad Soyad</label>
                            <input autocomplete="name" 
                                class="relative block w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm @error('full_name') border-red-500 @enderror" 
                                id="full_name" 
                                name="full_name" 
                                placeholder="Ad Soyad" 
                                required="" 
                                type="text"
                                value="{{ old('full_name') }}"/>
                            @error('full_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="sr-only" for="register_no">Register No</label>
                            <input 
                                class="relative block w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm @error('register_no') border-red-500 @enderror" 
                                id="register_no" 
                                name="register_no" 
                                placeholder="Register No" 
                                required="" 
                                type="text"
                                value="{{ old('register_no') }}"/>
                            @error('register_no')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            </div>
                        <div class="hidden">
                            <label class="sr-only" for="tax_number">TC Kimlik No (Opsiyonel)</label>
                            <input 
                                class="relative block w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm @error('tax_number') border-red-500 @enderror" 
                                id="tax_number" 
                                name="tax_number" 
                                placeholder="TC Kimlik No (Opsiyonel)" 
                                type="text"
                                value="{{ old('tax_number') }}"/>
                            @error('tax_number')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!--login page body content form submit button-->
                    <div>
                        <button class="group relative flex w-full justify-center rounded-lg border border-transparent bg-primary py-3 px-4 text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark disabled:opacity-50 disabled:cursor-not-allowed" 
                                type="submit" id="submitButton">
                            Sertifikayı Doğrula
                        </button>
                        </div>
                </form>
                            </div>
                        </div>
    </body>
    <script>
        // CSRF token al
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                         document.querySelector('input[name="_token"]')?.value;

        // Form submit AJAX ile
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const submitButton = document.getElementById('submitButton');
            const errorMessage = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            
            // Hata mesajını gizle
            errorMessage.classList.add('hidden');
            
            // Butonu devre dışı bırak
            submitButton.disabled = true;
            submitButton.textContent = 'Doğrulanıyor...';
            
            // Form verilerini al
            const formData = new FormData(form);
            
            // AJAX isteği
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                return response.json().then(data => {
                    if (!response.ok) {
                        // Validation hataları veya diğer hatalar
                        let errorMsg = data.message || 'Bir hata oluştu.';
                        if (data.errors) {
                            // Validation hatalarını birleştir
                            const errorMessages = Object.values(data.errors).flat();
                            errorMsg = errorMessages.join(' ');
                        }
                        throw new Error(errorMsg);
                    }
                    return data;
                });
            })
            .then(data => {
                if (data.success) {
                    // Başarılı - company portal main sayfasına yönlendir
                    window.location.href = '/company-portal/main';
                } else {
                    // Hata mesajını göster
                    errorText.textContent = data.message || 'Bir hata oluştu. Lütfen tekrar deneyin.';
                    errorMessage.classList.remove('hidden');
                    submitButton.disabled = false;
                    submitButton.textContent = 'Sertifikayı Doğrula';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorText.textContent = error.message || 'Bir hata oluştu. Lütfen tekrar deneyin.';
                errorMessage.classList.remove('hidden');
                submitButton.disabled = false;
                submitButton.textContent = 'Sertifikayı Doğrula';
            });
        });
    </script>
</html>
