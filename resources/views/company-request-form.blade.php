<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Firma Başvuru Formu</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..-50..200" rel="stylesheet"/>
    <script>
        window.tailwindConfig = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#1173d4",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
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
        if (typeof tailwind !== 'undefined' && window.tailwindConfig) {
            tailwind.config = window.tailwindConfig;
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200 min-h-screen">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-background-dark">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-4">
                        <div class="text-primary">
                            <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold">Firma Başvuru Formu</h2>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-2xl">
                <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm">
                    <!-- Title -->
                    <div class="p-6 border-b border-gray-200 dark:border-gray-800">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Firma Başvuru Formu</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Firma hesabı açmak için lütfen aşağıdaki formu doldurun.</p>
                    </div>

                    <!-- Form -->
                    <div class="p-6">
                        <!-- Success/Error Messages -->
                        <div id="message-area" class="mb-6"></div>

                        <form id="company-request-form" class="space-y-6">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ad *</label>
                                    <input type="text" 
                                        id="name" 
                                        name="name" 
                                        required
                                        value="{{ old('name') }}"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror" 
                                        placeholder="Adınız"/>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="surname" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Soyad *</label>
                                    <input type="text" 
                                        id="surname" 
                                        name="surname" 
                                        required
                                        value="{{ old('surname') }}"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white @error('surname') border-red-500 @enderror" 
                                        placeholder="Soyadınız"/>
                                    @error('surname')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-posta *</label>
                                <input type="email" 
                                    id="email" 
                                    name="email" 
                                    required
                                    value="{{ old('email') }}"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror" 
                                    placeholder="ornek@firma.com"/>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="border-t border-gray-300 dark:border-gray-600 pt-6">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Firma Bilgileri</h4>
                            </div>
                            
                            <div>
                                <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Firma Adı *</label>
                                <input type="text" 
                                    id="company_name" 
                                    name="company_name" 
                                    required
                                    value="{{ old('company_name') }}"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white @error('company_name') border-red-500 @enderror" 
                                    placeholder="Firma adınızı girin"/>
                                @error('company_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tax_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vergi Numarası *</label>
                                    <input type="text" 
                                        id="tax_number" 
                                        name="tax_number" 
                                        required
                                        value="{{ old('tax_number') }}"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white @error('tax_number') border-red-500 @enderror" 
                                        placeholder="Vergi numaranızı girin"/>
                                    @error('tax_number')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="tax_office" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vergi Dairesi</label>
                                    <input type="text" 
                                        id="tax_office" 
                                        name="tax_office" 
                                        value="{{ old('tax_office') }}"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white @error('tax_office') border-red-500 @enderror" 
                                        placeholder="Vergi dairesi adı"/>
                                    @error('tax_office')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telefon</label>
                                    <input type="tel" 
                                        id="phone" 
                                        name="phone" 
                                        value="{{ old('phone') }}"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white @error('phone') border-red-500 @enderror" 
                                        placeholder="+90 555 123 45 67"/>
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adres</label>
                                <textarea id="address" 
                                    name="address" 
                                    rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white @error('address') border-red-500 @enderror" 
                                    placeholder="Firma adresiniz">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mesaj</label>
                                <textarea id="message" 
                                    name="message" 
                                    rows="5"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white @error('message') border-red-500 @enderror" 
                                    placeholder="Eklemek istediğiniz bilgileri buraya yazabilirsiniz...">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="flex justify-end gap-4">
                                <button type="submit" 
                                    id="submit-btn" 
                                    class="bg-primary text-white py-3 px-6 rounded-lg font-medium hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                    Başvuru Gönder
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('company-request-form');
            const submitBtn = document.getElementById('submit-btn');
            const messageArea = document.getElementById('message-area');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                submitBtn.disabled = true;
                submitBtn.textContent = 'Gönderiliyor...';
                
                const formData = new FormData(form);
                
                fetch('{{ route("company-request.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.message, 'success');
                        form.reset();
                    } else {
                        showMessage(data.message || 'Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Başvuru Gönder';
                });
            });
            
            function showMessage(message, type) {
                messageArea.innerHTML = '';
                
                const banner = document.createElement('div');
                banner.className = type === 'success' 
                    ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4'
                    : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4';
                
                banner.innerHTML = `
                    <div class="text-sm ${type === 'success' ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'}">
                        ${message}
                    </div>
                `;
                
                messageArea.appendChild(banner);
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                setTimeout(() => {
                    if (banner.parentElement) {
                        banner.remove();
                    }
                }, 5000);
            }
        });
    </script>
</body>
</html>

