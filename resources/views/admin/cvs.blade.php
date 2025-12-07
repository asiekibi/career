@include('admin.partials.header')

<!-- main content -->
<main class="flex-1 p-4 lg:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 lg:mb-8">
        <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white">CV'ler</h2>
        <!-- Search input -->
        <div class="relative w-full sm:w-auto">
            <input type="text" 
                   id="cv-search" 
                   placeholder="Öğrenci ara..." 
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white">
            <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">search</span>
        </div>
    </div>
    
    <!-- Desktop Table (Hidden on Mobile) -->
    <div class="hidden lg:block bg-white dark:bg-background-dark/50 rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3" scope="col">Öğrenci</th>
                        <th class="px-6 py-3" scope="col">Rozetler</th>
                        <th class="px-6 py-3" scope="col">Toplam Puan</th>
                        <th class="px-6 py-3" scope="col">Çalışma Durumu</th>
                        <th class="px-6 py-3" scope="col">Güncelleme Tarihi</th>
                        <th class="px-6 py-3 text-center" scope="col">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="bg-white border-b dark:bg-background-dark/80 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-background-dark/90" data-student-name="{{ $user->name }} {{ $user->surname }}">
                            <th class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white" scope="row">
                                @if($user->profile_photo_url)
                                    <img alt="{{ $user->name }} {{ $user->surname }}" class="w-10 h-10 rounded-full" src="{{ asset($user->profile_photo_url) }}"/>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-500">person</span>
                                    </div>
                                @endif
                                <div class="pl-3">
                                    <div class="text-base font-semibold">{{ $user->name }} {{ $user->surname }}</div>
                                </div>
                            </th>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @foreach($user->userBadges as $userBadge)
                                        @if($userBadge->badge && $userBadge->badge->badge_icon_url)
                                            <img alt="{{ $userBadge->badge->badge_name }}" class="w-6 h-6 rounded-full" src="{{ asset($userBadge->badge->badge_icon_url) }}" title="{{ $userBadge->badge->badge_name }}"/>
                                        @else
                                            <span class="material-symbols-outlined text-yellow-500">star</span>
                                        @endif
                                    @endforeach
                                    @if($user->userBadges->count() == 0)
                                        <span class="text-gray-400 text-sm">Rozet yok</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-lg">{{ $user->point ?? 0 }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->is_active)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Aktif</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Pasif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $user->updated_at->format('d.m.Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.students.assign-certificate', $user->id) }}" 
                                       class="p-2 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" 
                                       title="Sertifika Ata">
                                        <span class="material-symbols-outlined text-lg">workspace_premium</span>
                                    </a>
                                    <a href="{{ route('admin.students.assign-badge', $user->id) }}" 
                                       class="p-2 text-gray-500 dark:text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-colors" 
                                       title="Rozet Ata">
                                        <span class="material-symbols-outlined text-lg">military_tech</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Henüz öğrenci bulunmuyor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards (Visible on Mobile) -->
    <div class="lg:hidden space-y-4">
        @forelse($users as $user)
            <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700" data-student-name="{{ $user->name }} {{ $user->surname }}">
                <!-- Student Header -->
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        @if($user->profile_photo_url)
                            <img alt="{{ $user->name }} {{ $user->surname }}" class="w-12 h-12 rounded-full" src="{{ asset($user->profile_photo_url) }}"/>
                        @else
                            <div class="w-12 h-12 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-gray-500">person</span>
                            </div>
                        @endif
                        <div class="ml-3">
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }} {{ $user->surname }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->updated_at->format('d.m.Y') }}</div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.students.assign-certificate', $user->id) }}" 
                           class="p-2 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" 
                           title="Sertifika Ata">
                            <span class="material-symbols-outlined text-lg">workspace_premium</span>
                        </a>
                        <a href="{{ route('admin.students.assign-badge', $user->id) }}" 
                           class="p-2 text-gray-500 dark:text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-colors" 
                           title="Rozet Ata">
                            <span class="material-symbols-outlined text-lg">military_tech</span>
                        </a>
                    </div>
                </div>
                
                <!-- Student Details -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Toplam Puan:</span>
                        <span class="ml-2 font-semibold text-gray-900 dark:text-white">{{ $user->point ?? 0 }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Durum:</span>
                        <div class="inline-flex items-center ml-2">
                            @if($user->is_active)
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Aktif</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Pasif</span>
                            @endif
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-gray-500 dark:text-gray-400">Rozetler:</span>
                        <div class="flex items-center gap-2 mt-1">
                            @foreach($user->userBadges as $userBadge)
                                @if($userBadge->badge && $userBadge->badge->badge_icon_url)
                                    <img alt="{{ $userBadge->badge->badge_name }}" class="w-6 h-6 rounded-full" src="{{ asset($userBadge->badge->badge_icon_url) }}" title="{{ $userBadge->badge->badge_name }}"/>
                                @else
                                    <span class="material-symbols-outlined text-yellow-500">star</span>
                                @endif
                            @endforeach
                            @if($user->userBadges->count() == 0)
                                <span class="text-gray-400 text-sm">Rozet yok</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-8 text-center text-gray-500 dark:text-gray-400">
                Henüz öğrenci bulunmuyor.
            </div>
        @endforelse
    </div>
</main>

@include('admin.partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('cv-search');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            // Tüm öğrenci elementlerini bul (hem desktop hem mobile)
            const allStudentElements = document.querySelectorAll('[data-student-name]');
            
            allStudentElements.forEach(element => {
                const studentName = element.getAttribute('data-student-name').toLowerCase();
                if (studentName.includes(searchTerm)) {
                    element.style.display = '';
                } else {
                    element.style.display = 'none';
                }
            });
        });
        
    });
</script>