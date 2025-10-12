@include('admin.partials.header')

<main class="flex-1 p-8">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $user->name }} {{ $user->surname }} için Rozet Ata</h2>
        <a class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors" href="{{ route('admin.cvs') }}">
            <span class="material-symbols-outlined">
                arrow_back
            </span>
            Geri Dön
        </a>
    </div>
    
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="mt-8 bg-white dark:bg-background-dark/50 p-6 rounded-lg shadow-sm">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Yeni Rozet Ata</h3>
        <form action="{{ route('admin.students.assign-badge.store', $user->id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="badge_id">Rozet Seç</label>
                    <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" id="badge_id" name="badge_id" required>
                        <option value="">Rozet seçiniz...</option>
                        @foreach($badges as $badge)
                            <option value="{{ $badge->id }}">{{ $badge->badge_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:outline-none focus:ring-primary/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary dark:hover:bg-primary/90 dark:focus:ring-primary/80" type="submit">Kaydet</button>
            </div>
        </form>
    </div>
    <div class="mt-12">
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Atanmış Rozetler</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @forelse($userBadges as $userBadge)
                <div class="relative bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-4 flex flex-col items-center justify-center text-center">
                    <button type="button" 
                            class="absolute top-2 right-2 text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 transition-colors"
                            onclick="confirmDeleteBadge({{ $userBadge->id }}, '{{ $user->name }}', '{{ $user->surname }}')">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                    
                    <!-- Gizli form -->
                    <form id="delete-badge-form-{{ $userBadge->id }}" 
                          action="{{ route('admin.students.remove-badge') }}" 
                          method="POST" 
                          style="display: none;">
                        @csrf
                        <input type="hidden" name="id" value="{{ $userBadge->id }}">
                    </form>
                    
                    @if($userBadge->badge && $userBadge->badge->badge_icon_url)
                        <img src="{{ asset($userBadge->badge->badge_icon_url) }}" alt="{{ $userBadge->badge->badge_name }}" class="w-12 h-12 rounded-full">
                    @else
                        <span class="material-symbols-outlined text-5xl text-yellow-500">
                            star
                        </span>
                    @endif
                    <p class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ $userBadge->badge->badge_name ?? 'Bilinmeyen Rozet' }}</p>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-8">
                    Henüz atanmış rozet bulunmuyor.
                </div>
            @endforelse
        </div>
    </div>
</main>

@include('admin.partials.footer')

<script>
// SweetAlert2 with badge delete confirmation
function confirmDeleteBadge(badgeId, userName, userSurname) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: `${userName} ${userSurname} kullanıcısının rozetini silmek istediğinizden emin misiniz?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Evet, Sil!',
        cancelButtonText: 'İptal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Loading show
            Swal.fire({
                title: 'Siliniyor...',
                text: 'Rozet siliniyor, lütfen bekleyin.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Form submit
            document.getElementById('delete-badge-form-' + badgeId).submit();
        }
    });
}
</script>