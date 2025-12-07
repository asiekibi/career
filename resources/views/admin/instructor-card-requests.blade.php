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
                                        <th class="px-6 py-3" scope="col">Kimlik Kartı</th>
                                        <th class="px-6 py-3" scope="col">Kalan Başvuru Hakkı</th>
                                        <th class="px-6 py-3 text-center" scope="col">İşlemler</th>
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
                                            <td class="px-6 py-4" id="cardStatusCell_{{ $request->id }}">
                                                @php
                                                    $hasCertificates = $request->certificates()->count() > 0;
                                                @endphp
                                                @if($hasCertificates)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        Verildi
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                        Verilmedi
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4" id="remainingRequestsCell_{{ $request->id }}" data-user-id="{{ $request->user_id }}">
                                                @php
                                                    $remainingRequests = $request->remaining_requests ?? 0;
                                                @endphp
                                                @if($remainingRequests > 0)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        {{ $remainingRequests }} Hakkı Var
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Hakkı Yok
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <button onclick="viewRequest({{ $request->id ?? 0 }})" 
                                                            class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Detay">
                                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                                    </button>
                                                    @if(!isset($request->status) || $request->status == 'pending')
                                                        <button onclick="approveRequest({{ $request->id ?? 0 }})" 
                                                                class="p-2 text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors" title="Onayla">
                                                            <span class="material-symbols-outlined text-lg">check_circle</span>
                                                        </button>
                                                        <button onclick="rejectRequest({{ $request->id ?? 0 }})" 
                                                                class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Reddet">
                                                            <span class="material-symbols-outlined text-lg">cancel</span>
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
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Kimlik Kartı:</span>
                                        <span class="ml-2" id="cardStatusCellMobile_{{ $request->id }}">
                                            @php
                                                $hasCertificates = $request->certificates()->count() > 0;
                                            @endphp
                                            @if($hasCertificates)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    Verildi
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    Verilmedi
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Kalan Başvuru Hakkı:</span>
                                        <span class="ml-2" id="remainingRequestsCellMobile_{{ $request->id }}" data-user-id="{{ $request->user_id }}">
                                            @php
                                                $remainingRequests = $request->remaining_requests ?? 0;
                                            @endphp
                                            @if($remainingRequests > 0)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    {{ $remainingRequests }} Hakkı Var
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    Hakkı Yok
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button onclick="viewRequest({{ $request->id ?? 0 }})" 
                                            class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Detay">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </button>
                                    @if(!isset($request->status) || $request->status == 'pending')
                                        <button onclick="approveRequest({{ $request->id ?? 0 }})" 
                                                class="p-2 text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors" title="Onayla">
                                            <span class="material-symbols-outlined text-lg">check_circle</span>
                                        </button>
                                        <button onclick="rejectRequest({{ $request->id ?? 0 }})" 
                                                class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Reddet">
                                            <span class="material-symbols-outlined text-lg">cancel</span>
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

<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-background-dark rounded-xl shadow-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 dark:border-gray-800">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Başvuru Detayları</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
        <div class="p-6">
            <div id="detailContent" class="space-y-6">
                <!-- Loading state -->
                <div class="text-center py-8">
                    <span class="material-symbols-outlined animate-spin text-4xl text-primary">sync</span>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Yükleniyor...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function viewRequest(requestId) {
        // Modal'ı göster
        document.getElementById('detailModal').classList.remove('hidden');
        
        // Loading göster
        document.getElementById('detailContent').innerHTML = `
            <div class="text-center py-8">
                <span class="material-symbols-outlined animate-spin text-4xl text-primary">sync</span>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Yükleniyor...</p>
            </div>
        `;
        
        // API çağrısı
        fetch(`/admin/instructor-card-requests/${requestId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const request = data.data;
                let statusBadge = '';
                if (request.status == 'approved') {
                    statusBadge = '<span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Onaylandı</span>';
                } else if (request.status == 'rejected') {
                    statusBadge = '<span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Reddedildi</span>';
                } else {
                    statusBadge = '<span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Beklemede</span>';
                }
                
                let certificatesHtml = '';
                if (request.certificates && request.certificates.length > 0) {
                    certificatesHtml = `
                        <div id="certificatesSection" class="mt-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Seçilen Sertifikalar</h4>
                            <div class="space-y-3">
                                ${request.certificates.map(cert => `
                                    <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h5 class="font-medium text-gray-900 dark:text-white">${cert.certificate_name}</h5>
                                                <div class="mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                                                    <p><span class="font-medium">Kod:</span> ${cert.certificate_code}</p>
                                                    <p><span class="font-medium">Kurum:</span> ${cert.issuing_institution}</p>
                                                    <p><span class="font-medium">Alınma Tarihi:</span> ${cert.acquisition_date}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-lg font-bold text-primary">${cert.achievement_score} Puan</span>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                } else {
                    certificatesHtml = `
                        <div id="certificatesSection" class="mt-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Seçilen Sertifikalar</h4>
                            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Bu başvuruda sertifika seçilmemiş.</p>
                            </div>
                        </div>
                    `;
                }
                
                document.getElementById('detailContent').innerHTML = `
                    <div class="space-y-6">
                        <!-- Başlık ve Durum -->
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">${request.instructor_name}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">${request.user.name} ${request.user.surname}</p>
                            </div>
                            <div id="modalStatusBadge">${statusBadge}</div>
                        </div>
                        
                        <!-- İletişim Bilgileri -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">İletişim Bilgileri</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                                    <p class="text-base font-medium text-gray-900 dark:text-white mt-1">${request.email}</p>
                                </div>
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Telefon</p>
                                    <p class="text-base font-medium text-gray-900 dark:text-white mt-1">${request.phone}</p>
                                </div>
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Başvuru Tarihi</p>
                                    <p class="text-base font-medium text-gray-900 dark:text-white mt-1">${request.created_at}</p>
                                </div>
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Kimlik Kartı Durumu</p>
                                            <p class="text-base font-medium text-gray-900 dark:text-white mt-1" id="cardStatusBadge">
                                                ${request.certificates && request.certificates.length > 0 
                                                    ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Verildi</span>'
                                                    : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Verilmedi</span>'
                                                }
                                            </p>
                                        </div>
                                        <button onclick="toggleCardStatus(${request.id})" 
                                                class="ml-4 px-3 py-1 text-xs font-medium rounded-lg ${request.certificates && request.certificates.length > 0 
                                                    ? 'bg-red-500 text-white hover:bg-red-600' 
                                                    : 'bg-blue-500 text-white hover:bg-blue-600'
                                                } transition-colors">
                                            ${request.certificates && request.certificates.length > 0 ? 'Verilmedi Yap' : 'Verildi Yap'}
                                        </button>
                                    </div>
                                </div>
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Kalan Başvuru Hakkı</p>
                                            <p class="text-base font-medium text-gray-900 dark:text-white mt-1" id="remainingRequestsBadge">
                                                ${request.remaining_requests > 0
                                                    ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">' + request.remaining_requests + ' Hakkı Var</span>'
                                                    : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Hakkı Yok</span>'
                                                }
                                            </p>
                                        </div>
                                        <button onclick="increaseRequestRights(${request.id}, ${request.user_id})" 
                                                class="ml-4 px-3 py-1 text-xs font-medium rounded-lg bg-green-500 text-white hover:bg-green-600 transition-colors">
                                            Hakkı Arttır
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        ${certificatesHtml}
                        
                        ${request.notes ? `
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Notlar</h4>
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">${request.notes}</p>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `;
            } else {
                Swal.fire({
                    title: 'Hata!',
                    text: 'Başvuru detayları yüklenirken bir hata oluştu.',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                });
                closeDetailModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Hata!',
                text: 'Başvuru detayları yüklenirken bir hata oluştu.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            });
            closeDetailModal();
        });
    }
    
    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
    
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });

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
                fetch(`/admin/instructor-card-requests/${requestId}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Modal açıksa modal içindeki durumu güncelle
                        const modalStatusBadge = document.getElementById('modalStatusBadge');
                        if (modalStatusBadge && data.status === 'approved') {
                            modalStatusBadge.innerHTML = '<span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Onaylandı</span>';
                        }
                        
                Swal.fire({
                    title: 'Başarılı!',
                            text: data.message || 'Başvuru onaylandı.',
                    icon: 'success',
                    confirmButtonText: 'Tamam'
                }).then(() => {
                    location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Hata!',
                            text: data.message || 'Başvuru onaylanırken bir hata oluştu.',
                            icon: 'error',
                            confirmButtonText: 'Tamam'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Başvuru onaylanırken bir hata oluştu.',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
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
                fetch(`/admin/instructor-card-requests/${requestId}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Modal açıksa modal içindeki durumu güncelle
                        const modalStatusBadge = document.getElementById('modalStatusBadge');
                        if (modalStatusBadge && data.status === 'rejected') {
                            modalStatusBadge.innerHTML = '<span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Reddedildi</span>';
                        }
                        
                        Swal.fire({
                            title: 'Başarılı!',
                            text: data.message || 'Başvuru reddedildi.',
                            icon: 'success',
                            confirmButtonText: 'Tamam'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Hata!',
                            text: data.message || 'Başvuru reddedilirken bir hata oluştu.',
                            icon: 'error',
                            confirmButtonText: 'Tamam'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Başvuru reddedilirken bir hata oluştu.',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                });
            }
        });
    }

    function toggleCardStatus(requestId) {
        const button = event.target.closest('button');
        const originalText = button.textContent;
        button.disabled = true;
        button.textContent = 'İşleniyor...';
        
        fetch(`/admin/instructor-card-requests/${requestId}/toggle-card-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const badgeElement = document.getElementById('cardStatusBadge');
                const isIssued = data.is_issued;
                
                if (isIssued) {
                    badgeElement.innerHTML = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Verildi</span>';
                    button.textContent = 'Verilmedi Yap';
                    button.className = 'ml-4 px-3 py-1 text-xs font-medium rounded-lg bg-red-500 text-white hover:bg-red-600 transition-colors';
                } else {
                    badgeElement.innerHTML = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Verilmedi</span>';
                    button.textContent = 'Verildi Yap';
                    button.className = 'ml-4 px-3 py-1 text-xs font-medium rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-colors';
                }
                
                // Tablodaki kimlik kartı durumunu güncelle
                const cardStatusCell = document.getElementById('cardStatusCell_' + requestId);
                const cardStatusCellMobile = document.getElementById('cardStatusCellMobile_' + requestId);
                
                if (cardStatusCell) {
                    if (isIssued) {
                        cardStatusCell.innerHTML = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Verildi</span>';
                    } else {
                        cardStatusCell.innerHTML = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Verilmedi</span>';
                    }
                }
                
                if (cardStatusCellMobile) {
                    if (isIssued) {
                        cardStatusCellMobile.innerHTML = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Verildi</span>';
                    } else {
                        cardStatusCellMobile.innerHTML = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Verilmedi</span>';
                    }
                }
                
                // Sertifikalar listesini güncelle
                const certificatesSection = document.getElementById('certificatesSection');
                
                if (data.certificates && data.certificates.length > 0) {
                    let certificatesHtml = `
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Seçilen Sertifikalar</h4>
                        <div class="space-y-3">
                            ${data.certificates.map(cert => `
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h5 class="font-medium text-gray-900 dark:text-white">${cert.certificate_name}</h5>
                                            <div class="mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                                                <p><span class="font-medium">Kod:</span> ${cert.certificate_code}</p>
                                                <p><span class="font-medium">Kurum:</span> ${cert.issuing_institution}</p>
                                                <p><span class="font-medium">Alınma Tarihi:</span> ${cert.acquisition_date}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-primary">${cert.achievement_score} Puan</span>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                    
                    if (certificatesSection) {
                        certificatesSection.innerHTML = certificatesHtml;
                    }
                } else {
                    if (certificatesSection) {
                        certificatesSection.innerHTML = `
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Seçilen Sertifikalar</h4>
                            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Bu başvuruda sertifika seçilmemiş.</p>
                            </div>
                        `;
                    }
                }
                
                Swal.fire({
                    title: 'Başarılı!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'Tamam',
                    timer: 2000
                });
            } else {
                Swal.fire({
                    title: 'Hata!',
                    text: data.message || 'Kimlik kartı durumu değiştirilirken bir hata oluştu.',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Hata!',
                text: 'Kimlik kartı durumu değiştirilirken bir hata oluştu.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            });
        })
        .finally(() => {
            button.disabled = false;
        });
    }

    function increaseRequestRights(requestId, userId) {
        Swal.fire({
            title: 'Başvuru Hakkı Arttırma',
            text: 'Bu kullanıcının başvuru hakkını arttırmak istediğinizden emin misiniz? (En eski reddedilmiş veya bekleyen başvuru sayıdan çıkarılacaktır)',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Evet, Arttır',
            cancelButtonText: 'İptal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const button = document.querySelector(`button[onclick*="increaseRequestRights(${requestId}"]`);
                const originalText = button ? button.textContent : 'Hakkı Arttır';
                if (button) {
                    button.disabled = true;
                    button.textContent = 'İşleniyor...';
                }
                
                fetch(`/admin/instructor-card-requests/${requestId}/increase-rights`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Modal içindeki kalan hak sayısını güncelle
                        const remainingRequestsBadge = document.getElementById('remainingRequestsBadge');
                        if (remainingRequestsBadge) {
                            if (data.remaining_requests > 0) {
                                remainingRequestsBadge.innerHTML = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">' + data.remaining_requests + ' Hakkı Var</span>';
                            } else {
                                remainingRequestsBadge.innerHTML = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Hakkı Yok</span>';
                            }
                        }
                        
                        // Tablodaki kalan hak sayısını güncelle (tüm kullanıcının başvuruları için)
                        document.querySelectorAll('[id^="remainingRequestsCell_"]').forEach(cell => {
                            if (cell.getAttribute('data-user-id') == userId) {
                                if (data.remaining_requests > 0) {
                                    cell.innerHTML = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">' + data.remaining_requests + ' Hakkı Var</span>';
                                } else {
                                    cell.innerHTML = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Hakkı Yok</span>';
                                }
                            }
                        });
                        
                        // Mobile kartlardaki kalan hak sayısını güncelle
                        document.querySelectorAll('[id^="remainingRequestsCellMobile_"]').forEach(cell => {
                            if (cell.getAttribute('data-user-id') == userId) {
                                if (data.remaining_requests > 0) {
                                    cell.innerHTML = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">' + data.remaining_requests + ' Hakkı Var</span>';
                                } else {
                                    cell.innerHTML = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Hakkı Yok</span>';
                                }
                            }
                        });
                        
                        Swal.fire({
                            title: 'Başarılı!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'Tamam',
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Hata!',
                            text: data.message || 'Başvuru hakkı arttırılırken bir hata oluştu.',
                            icon: 'error',
                            confirmButtonText: 'Tamam'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Başvuru hakkı arttırılırken bir hata oluştu.',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                })
                .finally(() => {
                    if (button) {
                        button.disabled = false;
                        button.textContent = originalText;
                    }
                });
            }
        });
    }

</script>

