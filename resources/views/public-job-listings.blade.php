<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İş İlanları | ASI Kariyer</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.04);
        }

        .gradient-text {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .job-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        }

        .asi-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .asi-modal {
            width: 100%;
            max-width: 440px;
            background: white;
            border-radius: 24px;
            padding: 40px;
            position: relative;
            animation: modalFadeUp 0.3s ease-out;
        }

        @keyframes modalFadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="antialiased">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 w-full glass-card py-4">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <a href="{{ route('certificate-query.page') }}" class="flex items-center gap-2">
                <img src="{{ asset('logo/logo.png') }}" alt="ASI Logo" class="h-10">
                <span class="font-extrabold text-xl tracking-tight text-slate-800">ASI Kariyer</span>
            </a>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('user.dashboard') }}"
                        class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors">Panelim</a>
                @else
                    <button onclick="openAuthModal()"
                        class="px-5 py-2.5 rounded-full bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition-all shadow-sm">Giriş
                        Yap / Kayıt Ol</button>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Header & Search -->
    <header class="py-16 px-6 bg-white border-b border-slate-100">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-6 gradient-text">ASI Kariyer İş Fırsatları</h1>
            <p class="text-slate-500 text-lg mb-10 font-medium">Sertifikanızla fark yaratın, hayalinizdeki işe bir adım
                daha yaklaşın.</p>

            <form action="{{ route('public.job-listings') }}" method="GET"
                class="flex flex-col md:flex-row gap-3 p-3 glass-card rounded-2xl md:rounded-full shadow-lg">
                <div class="flex-1 relative group">
                    <input type="text" name="position" value="{{ $position }}" placeholder="Pozisyon ara..."
                        class="w-full pl-6 pr-4 py-4 rounded-full bg-slate-50/50 border border-slate-200/60 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all text-sm font-medium">
                </div>
                <div class="flex-1 relative group">
                    <input type="text" name="city" value="{{ $city }}" placeholder="Şehir ara..."
                        class="w-full pl-6 pr-4 py-4 rounded-full bg-slate-50/50 border border-slate-200/60 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all text-sm font-medium">
                </div>
                <button type="submit"
                    class="bg-slate-900 text-white px-8 py-4 rounded-full font-bold hover:bg-slate-800 transition-all shadow-md active:scale-95">İlanları
                    Listele</button>
            </form>
        </div>
    </header>

    <!-- Listings Section -->
    <main class="py-20 px-6 max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-12">
            <h2 class="text-2xl font-extrabold text-slate-800">
                @if($position || $city)
                    Arama Sonuçları ({{ $jobListings->count() }})
                @else
                    Tüm İlanlar
                @endif
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($jobListings as $job)
                <div class="job-card glass-card rounded-[32px] p-8 flex flex-col transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center">
                            <img src="{{ asset('logo/logo.png') }}" alt="Logo" class="h-6 opacity-50 grayscale">
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-400 tracking-widest uppercase">İŞ FIRSATI</span>
                            <div class="text-[10px] text-slate-400 font-bold mt-0.5">{{ $job->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <h3 class="text-xl font-extrabold text-slate-800 mb-4">{{ $job->job_title }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-8 flex-grow">
                        {{ Str::limit($job->job_description, 160) }}
                    </p>

                    <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                        <button
                            onclick='@auth openJobDetailModal({{ $job->id }}, {!! json_encode($job->job_title) !!}, {!! json_encode($job->job_description) !!}, "{{ $job->created_at->format("d.m.Y") }}") @else openAuthModal() @endauth'
                            class="px-5 py-2.5 rounded-full bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-all shadow-sm">
                            Detay Gör
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center glass-card rounded-[32px]">
                    <div class="text-slate-300 mb-4 flex justify-center">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Aradığınız kriterlerde ilan bulunamadı</h3>
                    <p class="text-slate-500">Lütfen farklı kelimelerle tekrar deneyin.</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-20 px-6 border-t border-slate-100 text-center text-slate-400 text-sm font-medium">
        <p>© 2024 ASI Kariyer Portalı. Tüm Hakları Saklıdır.</p>
    </footer>

    <!-- Auth Modal -->
    <div class="asi-modal-overlay" id="authModalOverlay">
        <div class="asi-modal">
            <button onclick="closeAuthModal()"
                class="absolute top-6 right-6 p-2 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <div class="text-center mb-8">
                <img src="{{ asset('logo/logo.png') }}" alt="Logo" class="h-12 mx-auto mb-6">
                <h3 class="text-2xl font-extrabold text-slate-900 mb-2">İlan Detaylarını Görün</h3>
                <p class="text-slate-500 font-medium text-sm px-4">İlan veren firma bilgilerine ve tam açıklamalara
                    ulaşmak için üye olmanız gerekmektedir.</p>
            </div>

            <div class="space-y-4">
                <a href="{{ route('google.redirect') }}"
                    class="flex items-center justify-center gap-3 w-full py-4 rounded-2xl border border-slate-200 font-bold text-slate-700 hover:bg-slate-50 transition-all">
                    <img src="https://www.google.com/favicon.ico" class="w-5 h-5">
                    Google ile Devam Et
                </a>
                <div class="flex items-center gap-4 py-2">
                    <div class="h-px bg-slate-100 flex-1"></div>
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-widest">veya</span>
                    <div class="h-px bg-slate-100 flex-1"></div>
                </div>
                <a href="{{ route('login') }}"
                    class="block w-full py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition-all text-center">Giriş
                    Yap</a>
                <p class="text-center text-xs text-slate-400 font-medium">Hesabınız yok mu? <a
                        href="{{ route('login') }}?register=true" class="text-slate-900 font-bold underline">Kayıt
                        Ol</a></p>
            </div>
        </div>
    </div>

    <!-- Job Detail Modal -->
    <div id="jobDetailModal" class="asi-modal-overlay">
        <div class="asi-modal !max-w-2xl">
            <button onclick="closeJobDetailModal()"
                class="absolute top-6 right-6 p-2 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <div class="mb-8">
                <span class="text-xs font-bold text-slate-400 tracking-widest uppercase mb-2 block">İLAN DETAYI</span>
                <h3 class="text-2xl font-extrabold text-slate-900" id="modalJobTitle"></h3>
            </div>

            <div class="space-y-6">
                <div>
                    <h4 class="text-sm font-bold text-slate-700 mb-2">İş Açıklaması</h4>
                    <p class="text-slate-500 text-sm leading-relaxed whitespace-pre-wrap" id="modalJobDescription"></p>
                </div>
                <div class="flex flex-wrap gap-6 pt-4 border-t border-slate-100">
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Yayın Tarihi</h4>
                        <span class="text-sm font-bold text-slate-900" id="modalJobDate"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('authModalOverlay');
        const detailModal = document.getElementById('jobDetailModal');

        function openAuthModal() {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeAuthModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function openJobDetailModal(id, title, description, date) {
            document.getElementById('modalJobTitle').textContent = title;
            document.getElementById('modalJobDescription').textContent = description;
            document.getElementById('modalJobDate').textContent = date;

            detailModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeJobDetailModal() {
            detailModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close on backdrop click
        window.addEventListener('click', (e) => {
            if (e.target === modal) closeAuthModal();
            if (e.target === detailModal) closeJobDetailModal();
        });

        // Close on ESC
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeAuthModal();
                closeJobDetailModal();
            }
        });
    </script>
</body>

</html>