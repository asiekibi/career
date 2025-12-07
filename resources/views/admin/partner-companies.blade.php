@include('admin.partials.header')
    
    <!-- main content -->
    <main class="flex-1 p-4 lg:p-8">
        <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm">
            <!-- Header -->
            <div class="p-4 lg:p-6 border-b border-gray-200 dark:border-gray-800">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white">Partner Firmalar</h2>
                        <p class="mt-1 text-sm lg:text-base text-gray-600 dark:text-gray-400">Partner firma başvurularını yönetin</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4 lg:p-6">
                <!-- Desktop Table -->
                <div class="hidden lg:block">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3" scope="col">Firma</th>
                                    <th class="px-6 py-3" scope="col">İletişim</th>
                                    <th class="px-6 py-3" scope="col">Telefon</th>
                                    <th class="px-6 py-3" scope="col">Email</th>
                                    <th class="px-6 py-3" scope="col">Vergi No</th>
                                    <th class="px-6 py-3 text-center" scope="col">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($partnerCompanies as $company)
                                    <tr class="bg-white border-b dark:bg-background-dark dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-background-dark/90">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            <div class="max-w-xs truncate" title="{{ $company->company_name }}">
                                                {{ $company->company_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="max-w-xs truncate" title="{{ $company->contact_person }}">
                                                {{ $company->contact_person }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="max-w-xs truncate" title="{{ $company->phone }}">
                                                {{ $company->phone }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="max-w-xs truncate" title="{{ $company->email }}">
                                                {{ $company->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="max-w-xs truncate" title="{{ $company->tax_number }}">
                                                {{ $company->tax_number }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center">
                                                @if($company->has_permission)
                                                    <button onclick="updatePermission({{ $company->id }}, false)" 
                                                            class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Geri Çek">
                                                        <span class="material-symbols-outlined text-lg">block</span>
                                                    </button>
                                                @else
                                                    <button onclick="updatePermission({{ $company->id }}, true)" 
                                                            class="p-2 text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors" title="İzin Ver">
                                                        <span class="material-symbols-outlined text-lg">check_circle</span>
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
                    @foreach($partnerCompanies as $company)
                        <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $company->company_name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $company->contact_person }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($company->has_permission)
                                        <button onclick="updatePermission({{ $company->id }}, false)" 
                                                class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Geri Çek">
                                            <span class="material-symbols-outlined text-lg">block</span>
                                        </button>
                                    @else
                                        <button onclick="updatePermission({{ $company->id }}, true)" 
                                                class="p-2 text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors" title="İzin Ver">
                                            <span class="material-symbols-outlined text-lg">check_circle</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Telefon:</span>
                                    <span class="ml-2 text-gray-900 dark:text-white">{{ $company->phone }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Email:</span>
                                    <span class="ml-2 text-gray-900 dark:text-white">{{ $company->email }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Vergi No:</span>
                                    <span class="ml-2 text-gray-900 dark:text-white">{{ $company->tax_number }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>

@include('admin.partials.footer')

<script>
    function updatePermission(companyId, hasPermission) {
        const title = hasPermission ? 'İzin Verme Onayı' : 'İzni Geri Çekme Onayı';
        const text = hasPermission ? 'Bu firmaya izin vermek istediğinizden emin misiniz?' : 'Bu firmadan izni geri çekmek istediğinizden emin misiniz?';
        const confirmButtonText = hasPermission ? 'İzin Ver' : 'Geri Çek';
        const confirmButtonColor = hasPermission ? '#10b981' : '#ef4444';

        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'İptal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                 document.querySelector('input[name="_token"]')?.value ||
                                 '{{ csrf_token() }}';

                fetch(`/admin/partner-companies/${companyId}/permission`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        has_permission: hasPermission
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Başarılı!',
                            text: hasPermission ? 'İzin başarıyla verildi.' : 'İzin başarıyla geri çekildi.',
                            icon: 'success',
                            confirmButtonText: 'Tamam'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Hata!',
                            text: data.message || 'Bir hata oluştu.',
                            icon: 'error',
                            confirmButtonText: 'Tamam'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Bir hata oluştu.',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                });
            }
        });
    }
</script>