<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Portal Company</title>
        <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
        <link href="https://fonts.googleapis.com" rel="preconnect"/>
        <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
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
                        borderRadius: {
                            "DEFAULT": "0.25rem",
                            "lg": "0.5rem",
                            "xl": "0.75rem",
                            "full": "9999px"
                        },
                    },
                },
            }
        </script>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
        <style>
            .material-symbols-outlined {
                font-variation-settings:
                    'FILL' 0,
                    'wght' 400,
                    'GRAD' 0,
                    'opsz' 24
            }
        </style>
    </head>
    <body class="bg-background-light dark:bg-background-dark font-display">
        <div class="flex min-h-screen">
        <!-- Mobil overlay -->
        <div id="mobile-overlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

            <!-- left panel - Desktop normal, mobile hamburger menu -->
            <aside id="sidebar" class="w-64 bg-white dark:bg-background-dark border-r border-gray-200 dark:border-gray-800 flex flex-col fixed lg:static inset-y-0 left-0 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Portal Company</h1>
                </div>
                <nav class="flex-1 px-4 py-2">
                    @if(session('student_id'))
                        <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('portal.student.cv') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('portal.student.cv', ['userId' => session('student_id')]) }}">
                            <span class="material-symbols-outlined">person</span>
                            <span class="text-sm font-medium">CV Görüntüle</span>
                        </a>
                        <a class="mt-2 flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('portal.career-sequence') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('portal.career-sequence') }}">
                            <span class="material-symbols-outlined">leaderboard</span>
                            <span class="text-sm font-medium">Kariyer Sıralaması</span>
                        </a>
                        
                        @if(!session('is_company_auth'))
                            <a class="mt-2 flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('portal.partner-company') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('portal.partner-company') }}">
                                <span class="material-symbols-outlined">business</span>
                                <span class="text-sm font-medium">Partner Firma Ol</span>
                            </a>
                        @endif
                    @else
                        <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('portal-login') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('portal-login') }}">
                            <span class="material-symbols-outlined">search</span>
                            <span class="text-sm font-medium">Sertifika Sorgula</span>
                        </a>
                    @endif
                </nav>
                
                @if(session('student_id'))
                    <div class="p-4 border-t border-gray-200 dark:border-gray-800">
                        <a href="{{ route('portal-login') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                            <span class="material-symbols-outlined">logout</span>
                            <span class="text-sm font-medium">Çıkış</span>
                        </a>
                    </div>
                @endif
            </aside>

            <div class="flex-1 flex flex-col lg:ml-0 min-w-0">
                <!-- Header - Mobile hamburger menu -->
                <header class="flex items-center justify-between h-16 px-4 lg:px-6 bg-white dark:bg-background-dark border-b border-gray-200 dark:border-gray-800">
                    <!-- Mobile title -->
                    <div class="lg:hidden">
                        <h1 class="text-lg font-bold text-gray-800 dark:text-white">Portal Company</h1>
                    </div>

                    <!-- Mobile hamburger menu - Right aligned -->
                    <button id="mobile-menu-button" class="lg:hidden p-2 text-gray-600 dark:text-gray-300 ml-auto">
                        <span class="material-symbols-outlined text-2xl">menu</span>
                    </button>
                </header>
