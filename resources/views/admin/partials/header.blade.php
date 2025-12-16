<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script id="tailwind-config">
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
</head> 
<body class="bg-background-light dark:bg-background-dark font-display">
<div class="flex min-h-screen">
    <!-- Mobil overlay -->
    <div id="mobile-overlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

    <!-- left panel - Desktop normal, mobile hamburger menu -->
    <aside id="sidebar" class="w-64 bg-white dark:bg-background-dark/50 border-r border-background-light dark:border-background-dark/70 flex flex-col fixed lg:static inset-y-0 left-0 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="p-6">
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Yönetim Paneli</h1>
        </div>

        <!-- Mobile admin information - Only visible on mobile -->
        <div class="lg:hidden px-6 py-4 border-b border-background-light dark:border-background-dark/70">
            <div class="flex items-center gap-3">
                <div>
                    <div class="text-sm font-medium text-gray-800 dark:text-white">Admin</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Yönetici</div>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 py-2">
            
            <!-- Students menu - Same level as Certificates -->
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 dark:hover:bg-primary/20 hover:text-primary dark:hover:text-primary transition-colors' }}" href="{{ route('admin.dashboard') }}">
              
                Öğrenciler
            </a>
            
            <!-- CV'ler menu - Same level as Certificates -->
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('admin.cvs') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 dark:hover:bg-primary/20 hover:text-primary dark:hover:text-primary transition-colors' }}" href="{{ route('admin.cvs') }}">
               
                Sertifika ve Rozet Atama
            </a>
            
            <!-- Certificates menu - Same level as Certificates -->
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('admin.certificates') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 dark:hover:bg-primary/20 hover:text-primary dark:hover:text-primary transition-colors' }}" href="{{ route('admin.certificates') }}">
               
                Yeni Sertifika Ekle
            </a>
            
            <!-- Badges menu - Same level as Certificates -->
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('admin.badges') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 dark:hover:bg-primary/20 hover:text-primary dark:hover:text-primary transition-colors' }}" href="{{ route('admin.badges') }}">
              
                Rozet Ekle
            </a>
            
            <!-- Partner Companies menu - Yeni eklenen -->
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('admin.partner-companies') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 dark:hover:bg-primary/20 hover:text-primary dark:hover:text-primary transition-colors' }}" href="{{ route('admin.partner-companies') }}">
               
                Partner Firmalar
            </a>
            
            <!-- Instructor Card Requests menu -->
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('admin.instructor-card-requests') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 dark:hover:bg-primary/20 hover:text-primary dark:hover:text-primary transition-colors' }}" href="{{ route('admin.instructor-card-requests') }}">
             
                Eğitmen Kimlik Kartı Talepleri
            </a>
            
            <!-- Job Listings menu -->
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('admin.job-listings*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 dark:hover:bg-primary/20 hover:text-primary dark:hover:text-primary transition-colors' }}" href="{{ route('admin.job-listings.index') }}">
               
                İş İlanları
            </a>
        </nav>

        <!-- Mobile logout button - Only visible on mobile -->
        <div class="lg:hidden px-4 py-2 border-t border-background-light dark:border-background-dark/70">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 transition-colors w-full text-left">
                    <span class="material-symbols-outlined">
                                        logout
                                    </span>
                                    Çıkış Yap
                                </button>
            </form>
        </div>
    </aside>

<div class="flex-1 flex flex-col lg:ml-0">
    <header class="flex items-center justify-between lg:justify-end h-16 px-6 bg-white dark:bg-background-dark/50 border-b border-background-light dark:border-background-dark/70">
        <!-- Mobile title -->
        <div class="lg:hidden">
            <h1 class="text-lg font-bold text-gray-800 dark:text-white">
                @if(request()->routeIs('admin.students'))
                    Öğrenciler
                @elseif(request()->routeIs('admin.cvs'))
                    Sertifika ve Rozet Atama
                @elseif(request()->routeIs('admin.certificates'))
                    Sertifikalar
                @elseif(request()->routeIs('admin.badges'))
                    Rozetler
                @elseif(request()->routeIs('admin.partner-companies'))
                    Partner Firmalar
                @elseif(request()->routeIs('admin.instructor-card-requests'))
                    Eğitmen Kimlik Kartı Talepleri
                @elseif(request()->routeIs('admin.job-listings*'))
                    İş İlanları
                @else
                    Admin Panel
                @endif
            </h1>
        </div>

        <!-- Desktop admin information - Only visible on desktop -->
        <div class="hidden lg:flex items-center gap-4">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Admin</span>
            <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-gray-500">person</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">
                        logout
                    </span>
                    Çıkış Yap
                </button>
            </form>
        </div>
        <!-- Mobil hamburger menü - Header'da logout'un yerine -->
        <button id="mobile-menu-button" class="lg:hidden p-2 text-gray-600 dark:text-gray-300">
            <span class="material-symbols-outlined text-2xl">
                menu
            </span>
        </button>
    </header>