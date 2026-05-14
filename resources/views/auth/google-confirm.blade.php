<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Google Giriş İşleniyor</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: '#1173d4',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes pulse-custom {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(0.98); }
        }
        .animate-pulse-custom {
            animation: pulse-custom 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        .loader {
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top: 3px solid #1173d4;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="bg-[#f8fafc] font-sans antialiased">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-[440px] text-center">
            <!-- Logo Section -->
            <div class="mb-8 flex justify-center">
                <div class="relative">
                    <div class="absolute inset-0 animate-ping rounded-full bg-primary/10"></div>
                    <img src="{{ asset('logo/logo.png') }}" alt="Logo" class="relative h-16 w-auto">
                </div>
            </div>

            <!-- Content Card -->
            <div class="rounded-3xl bg-white p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                <div class="mb-6">
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                        @if ($existingUser)
                            Hesabınıza devam edin
                        @else
                            Hesabınız oluşturuluyor
                        @endif
                    </h1>
                    <p class="mt-2 text-gray-500 font-medium">
                        @if ($existingUser)
                            <span class="text-amber-600 font-bold">Bu e-posta adresi zaten kayıtlı.</span><br>
                            Mevcut hesabınıza yönlendiriliyorsunuz...
                        @else
                            Sizin için bir kariyer hesabı hazırlıyoruz...
                        @endif
                    </p>
                </div>

                <!-- User Info Profile -->
                <div class="flex items-center gap-4 rounded-2xl bg-gray-50 p-4 mb-8 border border-gray-100">
                    @if (!empty($googleUser['avatar']))
                        <img class="h-12 w-12 rounded-full border-2 border-white shadow-sm" src="{{ $googleUser['avatar'] }}" alt="Avatar">
                    @else
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary text-white font-bold">
                            {{ strtoupper(mb_substr($googleUser['name'] ?: $googleUser['email'], 0, 1)) }}
                        </div>
                    @endif
                    <div class="text-left overflow-hidden">
                        <p class="truncate text-sm font-bold text-gray-900">
                            {{ $googleUser['name'] ?: 'Google Kullanıcısı' }}
                        </p>
                        <p class="truncate text-xs text-gray-500 font-medium">
                            {{ $googleUser['email'] }}
                        </p>
                    </div>
                </div>

                <!-- Hidden form for auto-submit -->
                <form id="confirmForm" action="{{ route('google.confirm.store') }}" method="POST">
                    @csrf
                    <div class="flex items-center justify-center gap-3 text-primary font-bold text-sm">
                        <div class="loader"></div>
                        <span>Lütfen bekleyin...</span>
                    </div>
                </form>

                <p class="mt-8 text-xs text-gray-400 font-medium italic">
                    Otomatik olarak yönlendirileceksiniz.
                </p>
            </div>
        </div>
    </div>

    <script>
        // Sayfa yüklendikten 1.5 saniye sonra formu otomatik gönder
        window.onload = function() {
            setTimeout(function() {
                document.getElementById('confirmForm').submit();
            }, 1500);
        };
    </script>
</body>
</html>
