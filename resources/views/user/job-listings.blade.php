@include('user.partials.header')

<!-- main content -->
<main class="flex-1 p-4 lg:p-8">
    <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm">
        <div class="p-4 lg:p-6 border-b border-gray-200 dark:border-gray-800">
            <h2 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white">İş İlanları</h2>
            <p class="mt-1 text-sm lg:text-base text-gray-600 dark:text-gray-400">Mevcut iş ilanlarını görüntüleyin ve başvuru yapın.</p>
        </div>
        
        <div class="p-4 lg:p-6">
            <!-- Desktop Table (Hidden on Mobile) -->
            <div class="hidden lg:block">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3" scope="col">İlan Başlığı</th>
                                <th class="px-6 py-3" scope="col">Telefon</th>
                                <th class="px-6 py-3" scope="col">Açıklama</th>
                                <th class="px-6 py-3" scope="col">Yayın Tarihi</th>
                                <th class="px-6 py-3" scope="col">Aksiyonlar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobListings as $listing)
                                <tr class="bg-white border-b dark:bg-background-dark/80 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-background-dark/90">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        <div class="max-w-xs truncate" title="{{ $listing->job_title }}">
                                            {{ $listing->job_title }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="tel:{{ $listing->phone }}" class="text-primary hover:underline">
                                            {{ $listing->phone }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-md truncate" title="{{ $listing->job_description }}">
                                            {{ Str::limit($listing->job_description, 100) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $listing->created_at->format('d.m.Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <button type="button" 
                                                onclick="openJobDetailModal({{ $listing->id }}, '{{ addslashes($listing->job_title) }}', '{{ addslashes($listing->job_description) }}', '{{ $listing->phone }}', '{{ $listing->created_at->format('d.m.Y') }}')"
                                                class="text-primary hover:text-primary/80 transition-colors cursor-pointer"
                                                title="Detayları Görüntüle">
                                            <span class="material-symbols-outlined text-base">visibility</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Henüz ilan bulunmuyor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Cards (Visible on Mobile) -->
            <div class="lg:hidden space-y-4">
                @forelse($jobListings as $listing)
                    <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                        <div class="mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $listing->job_title }}</h3>
                            <div class="mt-2">
                                <a href="tel:{{ $listing->phone }}" class="flex items-center gap-2 text-primary hover:underline">
                                    <span class="material-symbols-outlined text-sm">phone</span>
                                    <span>{{ $listing->phone }}</span>
                                </a>
                            </div>
                        </div>
                        
                        <div class="text-sm mb-3">
                            <span class="text-gray-500 dark:text-gray-400">Açıklama:</span>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ Str::limit($listing->job_description, 150) }}</p>
                        </div>
                        
                        <div class="text-sm mb-3">
                            <span class="text-gray-500 dark:text-gray-400">Yayın Tarihi:</span>
                            <span class="text-gray-900 dark:text-white">{{ $listing->created_at->format('d.m.Y') }}</span>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="button" 
                                    onclick="openJobDetailModal({{ $listing->id }}, '{{ addslashes($listing->job_title) }}', '{{ addslashes($listing->job_description) }}', '{{ $listing->phone }}', '{{ $listing->created_at->format('d.m.Y') }}')"
                                    class="text-primary hover:text-primary/80 transition-colors cursor-pointer flex items-center gap-2"
                                    title="Detayları Görüntüle">
                                <span class="material-symbols-outlined text-base">visibility</span>
                                <span class="text-sm">Detayları Gör</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-8 text-center text-gray-500 dark:text-gray-400">
                        Henüz ilan bulunmuyor.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>

@include('user.partials.footer')

<!-- Job Detail Modal -->
<div id="jobDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-background-dark rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="modalJobTitle">İlan Detayı</h3>
                <button type="button" onclick="closeJobDetailModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">İlan Başlığı</label>
                    <p class="text-gray-900 dark:text-white text-lg font-semibold" id="modalJobTitleText"></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Açıklama</label>
                    <p class="text-gray-900 dark:text-white whitespace-pre-wrap" id="modalJobDescription"></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">İletişim</label>
                    <a href="tel:" id="modalJobPhone" class="text-primary hover:underline flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">phone</span>
                        <span id="modalJobPhoneText"></span>
                    </a>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Yayın Tarihi</label>
                    <p class="text-gray-900 dark:text-white" id="modalJobDate"></p>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeJobDetailModal()" class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Kapat
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openJobDetailModal(id, title, description, phone, date) {
    document.getElementById('modalJobTitle').textContent = title;
    document.getElementById('modalJobTitleText').textContent = title;
    document.getElementById('modalJobDescription').textContent = description;
    document.getElementById('modalJobPhone').href = 'tel:' + phone;
    document.getElementById('modalJobPhoneText').textContent = phone;
    document.getElementById('modalJobDate').textContent = date;
    
    document.getElementById('jobDetailModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeJobDetailModal() {
    document.getElementById('jobDetailModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Modal dışına tıklanınca kapat
document.getElementById('jobDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeJobDetailModal();
    }
});

// ESC tuşu ile kapat
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeJobDetailModal();
    }
});
</script>
