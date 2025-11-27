@include('admin.partials.header')
    
    <!-- main content -->
    <main class="flex-1 p-4 lg:p-8">
        <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm">
            <!-- Header -->
            <div class="p-4 lg:p-6 border-b border-gray-200 dark:border-gray-800">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white">Eğitmen Kimlik Kartı Talepleri</h2>
                        <p class="mt-1 text-sm lg:text-base text-gray-600 dark:text-gray-400">Eğitmen kimlik kartı başvurularını yönetin</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4 lg:p-6">
                @if(count($requests) > 0)
                    <!-- Desktop Table -->
                    <div class="hidden lg:block">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-6 py-3" scope="col">Eğitmen Adı</th>
                                        <th class="px-6 py-3" scope="col">Email</th>
                                        <th class="px-6 py-3" scope="col">Telefon</th>
                                        <th class="px-6 py-3" scope="col">Başvuru Tarihi</th>
                                        <th class="px-6 py-3" scope="col">Durum</th>
                                        <th class="px-6 py-3" scope="col">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr class="bg-white border-b dark:bg-background-dark dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-background-dark/90">
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                                <div class="max-w-xs truncate" title="{{ $request->instructor_name ?? 'Belirtilmemiş' }}">
                                                    {{ $request->instructor_name ?? 'Belirtilmemiş' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="max-w-xs truncate" title="{{ $request->email ?? 'Belirtilmemiş' }}">
                                                    {{ $request->email ?? 'Belirtilmemiş' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="max-w-xs truncate" title="{{ $request->phone ?? 'Belirtilmemiş' }}">
                                                    {{ $request->phone ?? 'Belirtilmemiş' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $request->created_at ? \Carbon\Carbon::parse($request->created_at)->format('d.m.Y') : 'Belirtilmemiş' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if(isset($request->status))
                                                    @if($request->status == 'approved')
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            Onaylandı
                                                        </span>
                                                    @elseif($request->status == 'rejected')
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                            Reddedildi
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                            Beklemede
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        Beklemede
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <button onclick="viewRequest({{ $request->id ?? 0 }})" 
                                                            class="bg-primary text-white px-3 py-1 rounded text-xs hover:bg-primary/90 transition-colors">
                                                        Detay
                                                    </button>
                                                    @if(!isset($request->status) || $request->status == 'pending')
                                                        <button onclick="approveRequest({{ $request->id ?? 0 }})" 
                                                                class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600 transition-colors">
                                                            Onayla
                                                        </button>
                                                        <button onclick="rejectRequest({{ $request->id ?? 0 }})" 
                                                                class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600 transition-colors">
                                                            Reddet
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="lg:hidden space-y-4">
                        @foreach($requests as $request)
                            <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $request->instructor_name ?? 'Belirtilmemiş' }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $request->email ?? 'Belirtilmemiş' }}</p>
                                    </div>
                                    <div>
                                        @if(isset($request->status))
                                            @if($request->status == 'approved')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Onaylandı
                                                </span>
                                            @elseif($request->status == 'rejected')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    Reddedildi
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    Beklemede
                                                </span>
                                            @endif
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                Beklemede
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm mb-3">
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Telefon:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ $request->phone ?? 'Belirtilmemiş' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Başvuru Tarihi:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">
                                            {{ $request->created_at ? \Carbon\Carbon::parse($request->created_at)->format('d.m.Y') : 'Belirtilmemiş' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <button onclick="viewRequest({{ $request->id ?? 0 }})" 
                                            class="bg-primary text-white px-3 py-1 rounded text-xs hover:bg-primary/90 transition-colors">
                                        Detay
                                    </button>
                                    @if(!isset($request->status) || $request->status == 'pending')
                                        <button onclick="approveRequest({{ $request->id ?? 0 }})" 
                                                class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600 transition-colors">
                                            Onayla
                                        </button>
                                        <button onclick="rejectRequest({{ $request->id ?? 0 }})" 
                                                class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600 transition-colors">
                                            Reddet
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-6xl text-gray-400 dark:text-gray-600 mb-4">
                            badge
                        </span>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Henüz başvuru bulunmuyor</h3>
                        <p class="text-gray-600 dark:text-gray-400">Eğitmen kimlik kartı talepleri burada görüntülenecek.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

@include('admin.partials.footer')

<script>
    function viewRequest(requestId) {
        // TODO: Detay sayfası veya modal gösterilecek
        Swal.fire({
            title: 'Başvuru Detayı',
            text: 'Başvuru detayları yakında eklenecek.',
            icon: 'info',
            confirmButtonText: 'Tamam'
        });
    }

    function approveRequest(requestId) {
        Swal.fire({
            title: 'Onaylama',
            text: 'Bu başvuruyu onaylamak istediğinizden emin misiniz?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Onayla',
            cancelButtonText: 'İptal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // TODO: API çağrısı yapılacak
                Swal.fire({
                    title: 'Başarılı!',
                    text: 'Başvuru onaylandı.',
                    icon: 'success',
                    confirmButtonText: 'Tamam'
                }).then(() => {
                    location.reload();
                });
            }
        });
    }

    function rejectRequest(requestId) {
        Swal.fire({
            title: 'Reddetme',
            text: 'Bu başvuruyu reddetmek istediğinizden emin misiniz?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Reddet',
            cancelButtonText: 'İptal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // TODO: API çağrısı yapılacak
                Swal.fire({
                    title: 'Başarılı!',
                    text: 'Başvuru reddedildi.',
                    icon: 'success',
                    confirmButtonText: 'Tamam'
                }).then(() => {
                    location.reload();
                });
            }
        });
    }
</script>

