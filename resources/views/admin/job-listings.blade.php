@include('admin.partials.header')

<!-- main content -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<main class="flex-1 p-4 lg:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white">İlanlar</h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
            <!-- New Job Listing button -->
            <a href="{{ route('admin.job-listings.create') }}" class="flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined">
                    add
                </span>
                <span class="hidden sm:inline">Yeni İlan</span>
                <span class="sm:hidden">Yeni İlan</span>
            </a>
        </div>
    </div>
    
    <div class="mt-6 lg:mt-8">
        <!-- Desktop Table (Hidden on Mobile) -->
        <div class="hidden lg:block bg-white dark:bg-background-dark/50 rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3" scope="col">İlan Başlığı</th>
                            <th class="px-6 py-3" scope="col">Telefon</th>
                            <th class="px-6 py-3" scope="col">Açıklama</th>
                            <th class="px-6 py-3" scope="col">Oluşturulma Tarihi</th>
                            <th class="px-6 py-3" scope="col"><span class="sr-only">İşlemler</span></th>
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
                                <td class="px-6 py-4">{{ $listing->phone }}</td>
                                <td class="px-6 py-4">
                                    <div class="max-w-md truncate" title="{{ $listing->job_description }}">
                                        {{ Str::limit($listing->job_description, 100) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $listing->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.job-listings.edit', $listing->id) }}" class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Düzenle">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                        <button onclick="confirmDelete({{ $listing->id }})" class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Sil">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Form (Hidden) -->
                                    <form id="delete-form-{{ $listing->id }}" action="{{ route('admin.job-listings.destroy', $listing->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
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
                    <!-- Listing Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $listing->job_title }}</h3>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $listing->phone }}</div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.job-listings.edit', $listing->id) }}" class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Düzenle">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <button onclick="confirmDelete({{ $listing->id }})" class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Sil">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Listing Details -->
                    <div class="text-sm">
                        <div class="mb-2">
                            <span class="text-gray-500 dark:text-gray-400">Açıklama:</span>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ Str::limit($listing->job_description, 150) }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Oluşturulma:</span>
                            <span class="ml-2 text-gray-900 dark:text-white">{{ $listing->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-8 text-center text-gray-500 dark:text-gray-400">
                    Henüz ilan bulunmuyor.
                </div>
            @endforelse
        </div>
    </div>
</main>

<script>
    // Delete confirmation function
    window.confirmDelete = function(listingId) {
        Swal.fire({
            title: 'Emin misiniz?',
            text: "Bu ilanı silmek istediğinizden emin misiniz?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Evet, Sil!',
            cancelButtonText: 'İptal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById(`delete-form-${listingId}`);
                if (form) {
                    form.submit();
                }
            }
        });
    }
</script>
@include('admin.partials.footer')

