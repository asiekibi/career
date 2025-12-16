<!DOCTYPE html>
<html lang="tr">
    <!--admin login page-->
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title>ASI Panel Login</title>
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
                        ASI Panel Girişi
                    </h2>
                    <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                        Hesabınıza giriş yapın
                    </p>
                </div>

                <!-- Tabs -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-1 shadow-sm border border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-1" aria-label="Tabs" role="tablist">
                        <button type="button" 
                            onclick="switchTab('user')" 
                            id="user-tab"
                            role="tab"
                            aria-selected="true"
                            class="tab-button active-tab flex-1 whitespace-nowrap rounded-md py-3 px-4 text-sm font-semibold text-primary bg-primary/10 border-2 border-primary transition-all duration-200">
                            Kullanıcı Girişi
                        </button>
                        <button type="button" 
                            onclick="switchTab('company')" 
                            id="company-tab"
                            role="tab"
                            aria-selected="false"
                            class="tab-button inactive-tab flex-1 whitespace-nowrap rounded-md py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 border-2 border-transparent transition-all duration-200">
                            Firma Girişi
                        </button>
                    </nav>
                </div>
                
                <!-- Kullanıcı Girişi Form -->
                <form action="{{ route('login') }}" class="mt-8 space-y-6 tab-content" id="user-loginForm" method="POST">
                    @csrf

                    <!--login page body content form inputs-->
                    <div class="space-y-4">
                        <div>
                            <label class="sr-only" for="user-email-address">Email address</label>
                            <input autocomplete="email" 
                                class="relative block w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm @error('email') border-red-500 @enderror" 
                                id="user-email-address" 
                                name="email" 
                                placeholder="Email adresi" 
                                required="" 
                                type="email"
                                value="{{ old('email') }}"/>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="sr-only" for="user-password">Password</label>
                            <div class="relative">
                                <input autocomplete="current-password" 
                                    class="relative block w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-3 pr-12 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm @error('password') border-red-500 @enderror" 
                                    id="user-password" 
                                    name="password" 
                                    placeholder="Şifre" 
                                    required="" 
                                    type="password"/>
                                <button type="button" 
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors duration-200"
                                    onclick="togglePassword('user-password', 'user-eye-icon', 'user-eye-off-icon')">
                                    <!-- Açık göz ikonu - basit -->
                                    <svg id="user-eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <!-- Kapalı göz ikonu - basit -->
                                    <svg id="user-eye-off-icon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!--login page body content form remember me and forgot password-->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            
                        </div>
                        <div class="text-sm">
                            <a class="font-medium text-primary hover:text-primary/80" href="{{ route('password.request') }}">
                                Şifrenizi mi unuttunuz?
                            </a>
                        </div>
                    </div>

                    <!-- Sertifika doğrulama yönlendirmesi -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Sertifikanızı doğrulamak için 
                            <a class="font-medium text-primary hover:text-primary/80" href="{{ url('/student-portal') }}">
                                devam edin
                            </a>
                        </p>
                    </div>

                    <!--login page body content form submit button-->
                    <div>
                        <button class="group relative flex w-full justify-center rounded-lg border border-transparent bg-primary py-3 px-4 text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark" 
                                type="submit">
                            Giriş Yap
                        </button>
                    </div>
                </form>

                <!-- Firma Girişi Form -->
                <form action="{{ route('company-portal.login') }}" class="mt-8 space-y-6 tab-content hidden" id="company-loginForm" method="POST">
                    @csrf

                    <!--login page body content form inputs-->
                    <div class="space-y-4">
                        <div>
                            <label class="sr-only" for="company-email-address">Email address</label>
                            <input autocomplete="email" 
                                class="relative block w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm @error('email') border-red-500 @enderror" 
                                id="company-email-address" 
                                name="email" 
                                placeholder="Email adresi" 
                                required="" 
                                type="email"
                                value="{{ old('email') }}"/>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="sr-only" for="company-password">Password</label>
                            <div class="relative">
                                <input autocomplete="current-password" 
                                    class="relative block w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-3 pr-12 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm @error('password') border-red-500 @enderror" 
                                    id="company-password" 
                                    name="password" 
                                    placeholder="Şifre" 
                                    required="" 
                                    type="password"/>
                                <button type="button" 
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors duration-200"
                                    onclick="togglePassword('company-password', 'company-eye-icon', 'company-eye-off-icon')">
                                    <!-- Açık göz ikonu - basit -->
                                    <svg id="company-eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <!-- Kapalı göz ikonu - basit -->
                                    <svg id="company-eye-off-icon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Partner firma başvuru linki -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Henüz partner firma olmadınız mı? 
                            <a class="font-medium text-primary hover:text-primary/80" href="{{ route('company-request.form') }}" target="_blank" rel="noopener noreferrer">
                                Hemen başvur
                            </a>
                        </p>
                    </div>

                    <!--login page body content form remember me and forgot password-->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            
                        </div>
                        <div class="text-sm">
                            <a class="font-medium text-primary hover:text-primary/80" href="{{ route('password.request') }}">
                                Şifrenizi mi unuttunuz?
                            </a>
                        </div>
                    </div>

                    <!--login page body content form submit button-->
                    <div>
                        <button class="group relative flex w-full justify-center rounded-lg border border-transparent bg-primary py-3 px-4 text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark" 
                                type="submit">
                            Giriş Yap
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>
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

        function switchTab(tab) {
            // Tüm tab içeriklerini gizle
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Tüm tab butonlarını resetle
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active-tab', 'bg-primary/10', 'border-primary', 'text-primary');
                button.classList.add('inactive-tab', 'border-transparent', 'text-gray-600', 'dark:text-gray-400');
                button.setAttribute('aria-selected', 'false');
            });

            // Seçilen tab'ı göster
            if (tab === 'user') {
                document.getElementById('user-loginForm').classList.remove('hidden');
                const userTab = document.getElementById('user-tab');
                userTab.classList.remove('inactive-tab', 'border-transparent', 'text-gray-600', 'dark:text-gray-400');
                userTab.classList.add('active-tab', 'bg-primary/10', 'border-primary', 'text-primary');
                userTab.setAttribute('aria-selected', 'true');
            } else if (tab === 'company') {
                document.getElementById('company-loginForm').classList.remove('hidden');
                const companyTab = document.getElementById('company-tab');
                companyTab.classList.remove('inactive-tab', 'border-transparent', 'text-gray-600', 'dark:text-gray-400');
                companyTab.classList.add('active-tab', 'bg-primary/10', 'border-primary', 'text-primary');
                companyTab.setAttribute('aria-selected', 'true');
            }
        }

        // Form submit loading state
        document.getElementById('user-loginForm').addEventListener('submit', function(e) {
            const form = this;
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Giriş yapılıyor...';
        });

        document.getElementById('company-loginForm').addEventListener('submit', function(e) {
            const form = this;
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Giriş yapılıyor...';
        });
    </script>
</html>
                    </div>

                </form>



                <!-- Firma Girişi Form -->

                <form action="{{ route('company-portal.login') }}" class="mt-8 space-y-6 tab-content hidden" id="company-loginForm" method="POST">

                    @csrf



                    <!--login page body content form inputs-->

                    <div class="space-y-4">

                        <div>

                            <label class="sr-only" for="company-email-address">Email address</label>

                            <input autocomplete="email" 

                                class="relative block w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm @error('email') border-red-500 @enderror" 

                                id="company-email-address" 

                                name="email" 

                                placeholder="Email adresi" 

                                required="" 

                                type="email"

                                value="{{ old('email') }}"/>

                            @error('email')

                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>

                            @enderror

                        </div>

                        <div>

                            <label class="sr-only" for="company-password">Password</label>

                            <div class="relative">

                                <input autocomplete="current-password" 

                                    class="relative block w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-3 pr-12 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm @error('password') border-red-500 @enderror" 

                                    id="company-password" 

                                    name="password" 

                                    placeholder="Şifre" 

                                    required="" 

                                    type="password"/>

                                <button type="button" 

                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors duration-200"

                                    onclick="togglePassword('company-password', 'company-eye-icon', 'company-eye-off-icon')">

                                    <!-- Açık göz ikonu - basit -->

                                    <svg id="company-eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">

                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>

                                        <circle cx="12" cy="12" r="3"></circle>

                                    </svg>

                                    <!-- Kapalı göz ikonu - basit -->

                                    <svg id="company-eye-off-icon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">

                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>

                                        <line x1="1" y1="1" x2="23" y2="23"></line>

                                    </svg>

                                </button>

                            </div>

                            @error('password')

                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>

                            @enderror

                        </div>

                    </div>



                    <!-- Partner firma başvuru linki -->

                    <div class="text-center">

                        <p class="text-sm text-gray-600 dark:text-gray-400">

                            Henüz partner firma olmadınız mı? 

                            <a class="font-medium text-primary hover:text-primary/80" href="{{ route('company-request.form') }}" target="_blank" rel="noopener noreferrer">

                                Hemen başvur

                            </a>

                        </p>

                    </div>



                    <!--login page body content form remember me and forgot password-->

                    <div class="flex items-center justify-between">

                        <div class="flex items-center">

                            

                        </div>

                        <div class="text-sm">

                            <a class="font-medium text-primary hover:text-primary/80" href="{{ route('password.request') }}">

                                Şifrenizi mi unuttunuz?

                            </a>

                        </div>

                    </div>



                    <!--login page body content form submit button-->

                    <div>

                        <button class="group relative flex w-full justify-center rounded-lg border border-transparent bg-primary py-3 px-4 text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark" 

                                type="submit">

                            Giriş Yap

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </body>

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



        function switchTab(tab) {

            // Tüm tab içeriklerini gizle

            document.querySelectorAll('.tab-content').forEach(content => {

                content.classList.add('hidden');

            });



            // Tüm tab butonlarını resetle

            document.querySelectorAll('.tab-button').forEach(button => {

                button.classList.remove('active-tab', 'bg-primary/10', 'border-primary', 'text-primary');

                button.classList.add('inactive-tab', 'border-transparent', 'text-gray-600', 'dark:text-gray-400');

                button.setAttribute('aria-selected', 'false');

            });



            // Seçilen tab'ı göster

            if (tab === 'user') {

                document.getElementById('user-loginForm').classList.remove('hidden');

                const userTab = document.getElementById('user-tab');

                userTab.classList.remove('inactive-tab', 'border-transparent', 'text-gray-600', 'dark:text-gray-400');

                userTab.classList.add('active-tab', 'bg-primary/10', 'border-primary', 'text-primary');

                userTab.setAttribute('aria-selected', 'true');

            } else if (tab === 'company') {

                document.getElementById('company-loginForm').classList.remove('hidden');

                const companyTab = document.getElementById('company-tab');

                companyTab.classList.remove('inactive-tab', 'border-transparent', 'text-gray-600', 'dark:text-gray-400');

                companyTab.classList.add('active-tab', 'bg-primary/10', 'border-primary', 'text-primary');

                companyTab.setAttribute('aria-selected', 'true');

            }

        }



        // Form submit loading state

        document.getElementById('user-loginForm').addEventListener('submit', function(e) {

            const form = this;

            const submitButton = form.querySelector('button[type="submit"]');

            submitButton.disabled = true;

            submitButton.textContent = 'Giriş yapılıyor...';

        });



        document.getElementById('company-loginForm').addEventListener('submit', function(e) {

            const form = this;

            const submitButton = form.querySelector('button[type="submit"]');

            submitButton.disabled = true;

            submitButton.textContent = 'Giriş yapılıyor...';

        });

    </script>

</html>
