<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Portal Company</title>
        <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}"/>
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
        <div class="flex flex-col min-h-screen">
            <!-- Header -->
            <header class="flex items-center justify-between h-16 px-4 lg:px-6 bg-white dark:bg-background-dark border-b border-gray-200 dark:border-gray-800">
                <div>
                    <h1 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white">Portal Company</h1>
                </div>
                
                @if(Auth::check() && Auth::user()->role === 'company')
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ Auth::user()->name }} {{ Auth::user()->surname }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <span class="material-symbols-outlined text-lg">logout</span>
                                <span class="text-sm font-medium">Çıkış</span>
                            </button>
                        </form>
                    </div>
                @elseif(session('student_id'))
                    <a href="{{ route('login') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <span class="material-symbols-outlined text-lg">logout</span>
                        <span class="text-sm font-medium">Çıkış</span>
                    </a>
                @endif
            </header>
            
            <div class="flex-1">
