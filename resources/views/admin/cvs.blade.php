@include('admin.partials.header')

<!-- main content -->
<main class="flex-1 p-8">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">CV'ler</h2>
        <!-- Search input -->
        <div class="relative">
            <input type="text" 
                   id="cv-search" 
                   placeholder="Öğrenci ara..." 
                   class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white">
            <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">search</span>
        </div>
    </div>
    
    <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3" scope="col">Öğrenci</th>
                        <th class="px-6 py-3" scope="col">Rozetler</th>
                        <th class="px-6 py-3" scope="col">Toplam Puan</th>
                        <th class="px-6 py-3" scope="col">Çalışma Durumu</th>
                        <th class="px-6 py-3" scope="col">Güncelleme Tarihi</th>
                        <th class="px-6 py-3" scope="col"><span class="sr-only">İşlemler</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="bg-white border-b dark:bg-background-dark/80 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-background-dark/90">
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
                            <td class="px-6 py-4 text-right">
                                <div class="relative">
                                    <button id="action-btn-{{ $user->id }}" class="text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary" onclick="toggleDropdown({{ $user->id }})">
                                        <span class="material-symbols-outlined">more_vert</span>
                                    </button>
                                    
                                    <!-- Dropdown Menu -->
                                    <div id="dropdown-{{ $user->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 border border-gray-200 dark:border-gray-700">
                                        <div class="py-1">
                                            <a href="{{ route('admin.students.assign-certificate', $user->id) }}" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                                <span class="material-symbols-outlined text-sm">workspace_premium</span>
                                                Sertifika Ata
                                            </a>
                                            <a href="{{ route('admin.students.assign-badge', $user->id) }}" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-yellow-600 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20">
                                                <span class="material-symbols-outlined text-sm">military_tech</span>
                                                Rozet Ata
                                            </a>
                                        </div>
                                    </div>
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
</main>

@include('admin.partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('cv-search');
    const tableRows = document.querySelectorAll('tbody tr');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        tableRows.forEach(row => {
            const studentName = row.querySelector('th').textContent.toLowerCase();
            
            if (studentName.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // dropdown toggle function
    window.toggleDropdown = function(userId) {
        // Close all other dropdowns
        document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
            if (dropdown.id !== `dropdown-${userId}`) {
                dropdown.classList.add('hidden');
            }
        });
        
        // Toggle current dropdown
        const dropdown = document.getElementById(`dropdown-${userId}`);
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.relative')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });
});
</script>