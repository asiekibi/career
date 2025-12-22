@include('portal-company.partials.header')

<!-- main content -->
<main class="flex-1 p-4 lg:p-8">

    @if($loginType == 'company')
        <!-- CV Listesi - Firma Girişi -->
        <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm">
            <div class="p-4 lg:p-6 border-b border-gray-200 dark:border-gray-800">
                <h2 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white mb-4">CV Listesi</h2>
                
                <!-- Filtreleme Bölümü -->
                <div class="mt-4 space-y-4">
                    <!-- Arama Kutusu -->
                    <div class="relative">
                        <input type="text" 
                               id="search-input" 
                               placeholder="Öğrenci ara (isim, email)..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">search</span>
                    </div>
                    
                    <!-- Filtreler -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Puan Sıralama -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Puan Sıralama</label>
                            <select id="point-sort" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white">
                                <option value="none">Sıralama Yok</option>
                                <option value="high-to-low">En Yüksekten En Düşüğe</option>
                                <option value="low-to-high">En Düşükten En Yükseğe</option>
                            </select>
                        </div>
                        
                        <!-- Ülke Filtresi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ülke</label>
                            <select id="country-filter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white">
                                <option value="">Tüm Ülkeler</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- İl Filtresi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">İl</label>
                            <select id="location-filter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white">
                                <option value="">Tüm İller</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->location }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Sertifika Filtresi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sertifikalar</label>
                            <div class="max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-lg p-2 dark:bg-gray-800">
                                @foreach($certificates as $certificate)
                                    <label class="flex items-center gap-2 py-1 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 px-2 rounded">
                                        <input type="checkbox" 
                                               class="certificate-filter" 
                                               value="{{ $certificate->id }}" 
                                               data-certificate-id="{{ $certificate->id }}">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $certificate->certificate_name }}</span>
                                    </label>
                                @endforeach
                                @if($certificates->count() == 0)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 py-2">Sertifika bulunamadı</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtreleri Temizle -->
                    <button id="clear-filters" class="text-sm text-primary hover:text-primary/80 font-medium">
                        Filtreleri Temizle
                    </button>
                </div>
            </div>
            
            <div class="p-4 lg:p-6">
                <!-- Desktop Table -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3" scope="col">Öğrenci</th>
                                <th class="px-6 py-3" scope="col">Rozetler</th>
                                <th class="px-6 py-3" scope="col">Toplam Puan</th>
                                <th class="px-6 py-3" scope="col">Ülke</th>
                                <th class="px-6 py-3" scope="col">İl</th>
                                <th class="px-6 py-3" scope="col">Telefon</th>
                                <th class="px-6 py-3 text-center" scope="col">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr class="student-row bg-white border-b dark:bg-background-dark dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-background-dark/90"
                                    data-student-name="{{ strtolower($user->name . ' ' . $user->surname) }}"
                                    data-student-email="{{ strtolower($user->email ?? '') }}"
                                    data-student-status="{{ $user->is_active ? 'active' : 'inactive' }}"
                                    data-student-certificates="{{ $user->userCertificates->pluck('certificate_id')->implode(',') }}"
                                    data-student-point="{{ $user->point ?? 0 }}"
                                    data-student-country="{{ $user->country_id ?? ($user->location->country_id ?? '') }}"
                                    data-student-location="{{ $user->location && $user->location->parent_id != 0 && $user->location->parent ? $user->location->parent->id : ($user->location_id ?? '') }}">
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
                                        <span class="font-semibold text-lg">{{ $user->point ?? 0 }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $user->country->name ?? ($user->location->country->name ?? '-') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->location)
                                            @if($user->location->parent_id != 0 && $user->location->parent)
                                                {{ $user->location->parent->location }}
                                            @else
                                                {{ $user->location->location }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->contact_info)
                                            {{ $user->gsm ?? '-' }}
                                        @else
                                            ***
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($user->cvs->count() > 0)
                                            <a href="{{ route('company-portal.student.cv', $user->id) }}" 
                                               class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-primary/10 dark:hover:bg-primary/20 rounded-lg transition-colors inline-flex items-center gap-1" 
                                               title="CV Detayını Gör">
                                                <span class="material-symbols-outlined text-lg">visibility</span>
                                                <span class="text-sm">Görüntüle</span>
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-sm">CV yok</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Henüz öğrenci bulunmuyor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="lg:hidden space-y-4">
                    @forelse($users as $user)
                        <div class="student-row bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700"
                             data-student-name="{{ strtolower($user->name . ' ' . $user->surname) }}"
                             data-student-email="{{ strtolower($user->email ?? '') }}"
                             data-student-status="{{ $user->is_active ? 'active' : 'inactive' }}"
                             data-student-certificates="{{ $user->userCertificates->pluck('certificate_id')->implode(',') }}"
                             data-student-point="{{ $user->point ?? 0 }}"
                             data-student-country="{{ $user->country_id ?? ($user->location->country_id ?? '') }}"
                             data-student-location="{{ $user->location && $user->location->parent_id != 0 && $user->location->parent ? $user->location->parent->id : ($user->location_id ?? '') }}">
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
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm mb-3">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Toplam Puan:</span>
                                    <span class="ml-2 font-semibold text-gray-900 dark:text-white">{{ $user->point ?? 0 }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Ülke:</span>
                                    <span class="ml-2 font-semibold text-gray-900 dark:text-white">{{ $user->country->name ?? ($user->location->country->name ?? '-') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">İl:</span>
                                    <span class="ml-2 font-semibold text-gray-900 dark:text-white">
                                        @if($user->location)
                                            @if($user->location->parent_id != 0 && $user->location->parent)
                                                {{ $user->location->parent->location }}
                                            @else
                                                {{ $user->location->location }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Telefon:</span>
                                    <span class="ml-2 font-semibold text-gray-900 dark:text-white">
                                        @if($user->contact_info)
                                            {{ $user->gsm ?? '-' }}
                                        @else
                                            ***
                                        @endif
                                    </span>
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
                            
                            @if($user->cvs->count() > 0)
                                <a href="{{ route('company-portal.student.cv', $user->id) }}" 
                                   class="w-full flex items-center justify-center gap-2 bg-primary text-black px-4 py-2 rounded-lg font-semibold text-sm hover:bg-opacity-90 transition-colors">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                    CV Detayını Gör
                                </a>
                    @else
                                <div class="text-center text-gray-400 text-sm py-2">CV yok</div>
                    @endif
                        </div>
                    @empty
                        <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-8 text-center text-gray-500 dark:text-gray-400">
                            Henüz öğrenci bulunmuyor.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</main>

@include('portal-company.partials.footer')

@if($loginType == 'company')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const certificateFilters = document.querySelectorAll('.certificate-filter');
    const pointSort = document.getElementById('point-sort');
    const countryFilter = document.getElementById('country-filter');
    const locationFilter = document.getElementById('location-filter');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const studentRows = Array.from(document.querySelectorAll('.student-row'));
    
    function filterAndSortStudents() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedCertificates = Array.from(certificateFilters)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        const sortValue = pointSort.value;
        const selectedCountry = countryFilter ? countryFilter.value : '';
        const selectedLocation = locationFilter ? locationFilter.value : '';
        
        const tbody = document.querySelector('tbody');
        const mobileContainer = document.querySelector('.lg\\:hidden.space-y-4');
        
        // Önce filtrele
        const visibleRows = [];
        studentRows.forEach(row => {
            const studentName = row.getAttribute('data-student-name') || '';
            const studentEmail = row.getAttribute('data-student-email') || '';
            const studentCertificates = row.getAttribute('data-student-certificates') || '';
            const studentCertArray = studentCertificates ? studentCertificates.split(',') : [];
            const studentCountry = row.getAttribute('data-student-country') || '';
            const studentLocation = row.getAttribute('data-student-location') || '';
            
            // Arama filtresi
            const matchesSearch = !searchTerm || 
                studentName.includes(searchTerm) || 
                studentEmail.includes(searchTerm);
            
            // Sertifika filtresi - seçili sertifikaların HEPSİNE sahip olmalı
            let matchesCertificates = true;
            if (selectedCertificates.length > 0) {
                matchesCertificates = selectedCertificates.every(certId => 
                    studentCertArray.includes(certId)
                );
            }
            
            // Ülke filtresi
            const matchesCountry = !selectedCountry || studentCountry === selectedCountry;
            
            // İl filtresi
            const matchesLocation = !selectedLocation || studentLocation === selectedLocation;
            
            if (matchesSearch && matchesCertificates && matchesCountry && matchesLocation) {
                visibleRows.push(row);
            }
        });
        
        // Sonra sırala (sadece görünür satırlar için)
        if (sortValue === 'high-to-low') {
            visibleRows.sort((a, b) => {
                const pointA = parseInt(a.getAttribute('data-student-point') || 0);
                const pointB = parseInt(b.getAttribute('data-student-point') || 0);
                return pointB - pointA; // Yüksekten düşüğe
            });
        } else if (sortValue === 'low-to-high') {
            visibleRows.sort((a, b) => {
                const pointA = parseInt(a.getAttribute('data-student-point') || 0);
                const pointB = parseInt(b.getAttribute('data-student-point') || 0);
                return pointA - pointB; // Düşükten yükseğe
            });
        }
        
        // Tüm satırları gizle
        studentRows.forEach(row => {
            row.style.display = 'none';
        });
        
        // Görünür satırları göster ve sırala
        if (sortValue !== 'none') {
            // Desktop tablo için
            if (tbody) {
                const tableRows = visibleRows.filter(row => row.tagName === 'TR');
                // Önce tüm satırları DOM'dan çıkar
                tableRows.forEach(row => {
                    if (row.parentNode) {
                        row.parentNode.removeChild(row);
                    }
                });
                // Sonra sıralı şekilde tekrar ekle
                tableRows.forEach(row => {
                    tbody.appendChild(row);
                    row.style.display = '';
                });
            }
            
            // Mobile kartlar için
            if (mobileContainer) {
                const mobileCards = visibleRows.filter(row => row.tagName === 'DIV');
                // Önce tüm kartları DOM'dan çıkar
                mobileCards.forEach(row => {
                    if (row.parentNode) {
                        row.parentNode.removeChild(row);
                    }
                });
                // Sonra sıralı şekilde tekrar ekle
                mobileCards.forEach(row => {
                    mobileContainer.appendChild(row);
                    row.style.display = '';
                });
            }
        } else {
            // Sıralama yoksa görünür satırları göster (orijinal sırada)
            visibleRows.forEach(row => {
                row.style.display = '';
            });
        }
    }
    
    // Event listeners
    if (searchInput) {
        searchInput.addEventListener('input', filterAndSortStudents);
    }
    certificateFilters.forEach(cb => {
        cb.addEventListener('change', filterAndSortStudents);
    });
    if (pointSort) {
        pointSort.addEventListener('change', filterAndSortStudents);
    }
    if (countryFilter) {
        countryFilter.addEventListener('change', filterAndSortStudents);
    }
    if (locationFilter) {
        locationFilter.addEventListener('change', filterAndSortStudents);
    }
    
    // Filtreleri temizle
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (pointSort) pointSort.value = 'none';
            if (countryFilter) countryFilter.value = '';
            if (locationFilter) locationFilter.value = '';
            certificateFilters.forEach(cb => {
                cb.checked = false;
            });
            filterAndSortStudents();
        });
    }
});
</script>
@endif