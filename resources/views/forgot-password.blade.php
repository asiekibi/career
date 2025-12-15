<!DOCTYPE html>
    <html lang="tr">
    <!--forgot password page-->
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title>Şifremi Unuttum</title>
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

    <!--forgot password page body-->
    <body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
        <div class="flex min-h-screen items-center justify-center">
            <div class="w-full max-w-md space-y-8 p-8">
                <div class="text-center">
                        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    Şifrenizi mi unuttunuz?
                        </h2>
                        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                            Endişelenmeyin. Şifrenizi sıfırlamak için kayıtlı email adresinizi girin.
                        </p>
                </div>
                @if (session('status'))
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                                    Başarılı!
                                </h3>
                                <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                                    {{ session('status') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <form action="{{ route('password.email') }}" class="mt-8 space-y-6" method="POST">
                        @csrf
                        <input name="remember" type="hidden" value="true"/>
                        <div class="space-y-4 rounded-lg"><div>
                        <label class="sr-only" for="email-address">Email address</label>
                        <input autocomplete="email" class="relative block w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm @error('email') border-red-500 @enderror" id="email-address" name="email" placeholder="Email adresi" required="" type="email" value="{{ old('email') }}"/>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    
                        <div class="mt-4">
                            <button class="group relative flex w-full justify-center rounded-lg border border-transparent bg-primary py-3 px-4 text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark" type="submit">
                                        Şifremi Sıfırla
                            </button>
                        </div>
                        <div class="text-center text-sm mt-4">
                            <a class="font-medium text-primary hover:text-primary/80" href="{{ route('login') }}">
                                            Giriş ekranına geri dön
                            </a>
                        </div>
                </form>
             
            </div>
        </div>

    </body>
</html>