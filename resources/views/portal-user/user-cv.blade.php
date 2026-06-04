@include('portal-user.partials.header')

<!-- main content -->
<main class="flex-1 p-4 lg:p-8 max-w-7xl mx-auto w-full">
    <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm">
        <div class="p-4 lg:p-6 border-b border-gray-200 dark:border-gray-800">
            <!-- title -->
            <div>
                <h2 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white">Cv</h2>
            </div>
        </div>
        <div class="p-4 lg:p-6">
            <!-- Responsive Profile Section -->
            <div class="flex flex-col sm:flex-row items-start gap-4 lg:gap-6">
                @if($student->profile_photo_url)
                    <img alt="Profil Resmi" class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover mx-auto sm:mx-0" src="{{ asset($student->profile_photo_url) }}"/>
                @else
                    <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center mx-auto sm:mx-0">
                        <span class="material-symbols-outlined text-gray-500 text-4xl sm:text-6xl">person</span>
                    </div>
                @endif
                <div class="flex-1 text-center sm:text-left">
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white">{{ $student->name }} {{ $student->surname }}</h3>
                    <div class="mt-4 space-y-2 lg:space-y-3">
                        <div class="flex items-center text-gray-600 dark:text-gray-300 justify-center sm:justify-start">
                            <span class="material-symbols-outlined mr-2 text-sm">cake</span>
                            <span class="text-sm lg:text-base">{{ $student->birth_date ? $student->birth_date->format('d.m.Y') : 'Belirtilmemiş' }}</span>
                        </div>
                        <div class="flex items-center text-gray-600 dark:text-gray-300 justify-center sm:justify-start">
                            <span class="material-symbols-outlined mr-2 text-sm">phone</span>
                            <span class="text-sm lg:text-base">
                                @if(session('is_company_auth'))
                                    {{ $student->gsm ?? 'Belirtilmemiş' }}
                                @else
                                    @if($student->contact_info)
                                        {{ $student->gsm ?? 'Belirtilmemiş' }}
                                    @else
                                        ***
                                    @endif
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center text-gray-600 dark:text-gray-300 justify-center sm:justify-start">
                            <span class="material-symbols-outlined mr-2 text-sm">email</span>
                            <span class="text-sm lg:text-base">
                                @if(session('is_company_auth'))
                                    {{ $student->email }}
                                @else
                                    @if($student->contact_info)
                                        {{ $student->email }}
                                    @else
                                        ***
                                    @endif
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center text-gray-600 dark:text-gray-300 justify-center sm:justify-start">
                            <span class="material-symbols-outlined mr-2 text-sm">star</span>
                            <span class="text-sm lg:text-base">
                                {{ $student->point ?? 0 }} Puan
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($student->cvs->count() > 0)
                @php $cv = $student->cvs->first(); @endphp
                
                @if($cv->experiences->count() > 0)
                    <div class="mt-8 lg:mt-10">
                        <h4 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white mb-4">Deneyimler</h4>
                        <div class="space-y-4 lg:space-y-6">
                            @foreach($cv->experiences as $experience)
                                <div class="pl-3 lg:pl-4 border-l-2 border-primary">
                                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-400">
                                        {{ $experience->start_date ? \Carbon\Carbon::parse($experience->start_date)->format('m.Y') : 'Belirtilmemiş' }} - 
                                        {{ $experience->end_date ? \Carbon\Carbon::parse($experience->end_date)->format('m.Y') : 'Halen' }}
                                    </p>
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200 text-sm lg:text-base">{{ $experience->company_name }} - {{ $experience->position }}</h5>
                                    <p class="mt-1 text-gray-600 dark:text-gray-300 text-sm lg:text-base">{{ $experience->description ?? 'Açıklama yok' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($cv->educations->count() > 0)
                    <div class="mt-8 lg:mt-10">
                        <h4 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white mb-4">Eğitimler</h4>
                        <div class="space-y-4 lg:space-y-6">
                            @foreach($cv->educations as $education)
                                <div class="pl-3 lg:pl-4 border-l-2 border-primary">
                                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-400">
                                        {{ $education->start_date ? \Carbon\Carbon::parse($education->start_date)->format('m.Y') : 'Belirtilmemiş' }} - 
                                        {{ $education->end_date ? \Carbon\Carbon::parse($education->end_date)->format('m.Y') : 'Halen' }}
                                    </p>
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200 text-sm lg:text-base">{{ $education->school_name }} - {{ $education->field_of_study }}</h5>
                                    @if($education->description)
                                        <p class="mt-1 text-gray-600 dark:text-gray-300 text-sm lg:text-base">{{ $education->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

               
                @if($cv->abilities->count() > 0 || $cv->languages->count() > 0)
                    <div class="mt-8 lg:mt-10">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                            <!-- Yetenekler -->
                            @if($cv->abilities->count() > 0)
                                <div>
                                    <h4 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white mb-4">Yetenekler</h4>
                                    <div class="space-y-3 lg:space-y-4">
                                        @foreach($cv->abilities as $ability)
                                            <div>
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-sm lg:text-base font-medium text-gray-700 dark:text-gray-300">{{ $ability->abilities_name }}</span>
                                                    <span class="text-xs lg:text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        @switch($ability->level)
                                                            @case('beginner')
                                                                Başlangıç
                                                                @break
                                                            @case('intermediate')
                                                                Orta
                                                                @break
                                                            @case('advanced')
                                                                İleri
                                                                @break
                                                            @case('expert')
                                                                Uzman
                                                                @break
                                                            @default
                                                                {{ $ability->level }}
                                                        @endswitch
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2 lg:h-2.5 dark:bg-gray-700">
                                                    @php
                                                        $width = match($ability->level) {
                                                            'beginner' => '25%',
                                                            'intermediate' => '50%',
                                                            'advanced' => '75%',
                                                            'expert' => '100%',
                                                            default => '50%'
                                                        };
                                                    @endphp
                                                    <div class="bg-primary h-2 lg:h-2.5 rounded-full" style="width: {{ $width }}"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Diller -->
                            @if($cv->languages->count() > 0)
                                <div>
                                    <h4 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white mb-4">Diller</h4>
                                    <div class="space-y-3 lg:space-y-4">
                                        @foreach($cv->languages as $language)
                                            <div>
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-sm lg:text-base font-medium text-gray-700 dark:text-gray-300">{{ $language->language_name }}</span>
                                                    <span class="text-xs lg:text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        @switch($language->level)
                                                            @case('basic')
                                                                Temel
                                                                @break
                                                            @case('conversational')
                                                                Konuşma
                                                                @break
                                                            @case('fluent')
                                                                Akıcı
                                                                @break
                                                            @case('native')
                                                                Ana Dil
                                                                @break
                                                            @default
                                                                {{ $language->level }}
                                                        @endswitch
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2 lg:h-2.5 dark:bg-gray-700">
                                                    @php
                                                        $width = match($language->level) {
                                                            'basic' => '25%',
                                                            'conversational' => '50%',
                                                            'fluent' => '75%',
                                                            'native' => '100%',
                                                            default => '50%'
                                                        };
                                                    @endphp
                                                    <div class="bg-primary h-2 lg:h-2.5 rounded-full" style="width: {{ $width }}"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif

            @if($student->userBadges->count() > 0)
                <div class="mt-8 lg:mt-10">
                    <h4 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white mb-4">Kazanılan Rozetler</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 lg:gap-6">
                        @foreach($student->userBadges as $userBadge)
                            <div class="flex flex-col items-center p-3 lg:p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800/50 text-center">
                                @if($userBadge->badge && $userBadge->badge->badge_icon_url)
                                    <img alt="{{ $userBadge->badge->badge_name }}" 
                                         class="w-10 h-10 lg:w-12 lg:h-12 rounded-full object-cover" 
                                         src="{{ asset($userBadge->badge->badge_icon_url) }}" 
                                         title="{{ $userBadge->badge->badge_name }}"/>
                                @else
                                    <span class="material-symbols-outlined text-4xl lg:text-5xl text-yellow-500">military_tech</span>
                                @endif
                                <h5 class="mt-2 font-semibold text-gray-800 dark:text-gray-200 text-xs lg:text-sm">{{ $userBadge->badge->badge_name }}</h5>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Sorgulanan Sertifika -->
            @if($searchedCertificate)
                <div class="mt-8 lg:mt-10">
                    <h4 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white mb-4">Sorguladığınız Sertifika</h4>
                    <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm border border-primary/20">
                        <div class="bg-primary/10 dark:bg-primary/20 p-4 border-b border-primary/20 rounded-t-lg">
                            <h5 class="font-semibold text-gray-900 dark:text-white">{{ $searchedCertificate->certificate->certificate_name ?? 'Bilinmeyen Sertifika' }}</h5>
                        </div>
                        <div class="p-4 lg:p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Register No:</span>
                                    <p class="text-gray-900 dark:text-white font-semibold mt-1">{{ $searchedCertificate->register_no ?? 'Belirtilmemiş' }}</p>
                                </div>
                                @if($searchedCertificate->acquisition_date)
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Veriliş Tarihi:</span>
                                    <p class="text-gray-900 dark:text-white font-semibold mt-1">{{ \Carbon\Carbon::parse($searchedCertificate->acquisition_date)->format('d.m.Y') }}</p>
                                </div>
                                @endif

                                @if($searchedCertificate->issuing_institution)
                                <div class="md:col-span-2">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Veren Kurum:</span>
                                    <p class="text-gray-900 dark:text-white font-semibold mt-1">{{ $searchedCertificate->issuing_institution }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                <a href="{{ route('certificate.download', $searchedCertificate->id) }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-lg">download</span>
                                    <span>İndir</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Diğer Sertifikalar -->
            <div class="mt-8 lg:mt-10">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white">Diğer Sertifikalar</h4>
                    @if($loginType === 'student')
                        <button id="addCustomCertificateBtn" class="bg-primary text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-primary/90 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">add</span>
                            Sertifika Ekle
                        </button>
                    @endif
                </div>
                
                @if($otherCertificates && $otherCertificates->count() > 0)
                    <!-- Desktop Table (Hidden on Mobile) -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3" scope="col">Sertifika Adı</th>
                                    <th class="px-6 py-3" scope="col">Register No</th>
                                    <th class="px-6 py-3" scope="col">Kurum</th>
                                    <th class="px-6 py-3" scope="col">Şifre</th>
                                    <th class="px-6 py-3" scope="col">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($otherCertificates as $userCertificate)
                                    <tr class="bg-white border-b dark:bg-background-dark dark:border-gray-700">
                                        <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" scope="row">
                                            {{ $userCertificate->certificate->certificate_name ?? $userCertificate->custom_certificate_name ?? 'Sertifika adı bulunamadı' }}
                                        </th>
                                        <td class="px-6 py-4">{{ $userCertificate->register_no ?? 'Belirtilmemiş' }}</td>
                                        <td class="px-6 py-4">{{ $userCertificate->issuing_institution ?? 'Belirtilmemiş' }}</td>
                                        <td class="px-6 py-4">{{ $userCertificate->password ?? 'Belirtilmemiş' }}</td>
                                        <td class="px-6 py-4 flex gap-2">
                                            <a href="{{ route('certificate.download', $userCertificate->id) }}" 
                                               target="_blank"
                                               class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 rounded-lg transition-colors">
                                                <span class="material-symbols-outlined text-lg">download</span>
                                                <span>İndir</span>
                                            </a>
                                            @if($loginType === 'student' && $userCertificate->file_path)
                                                <button data-id="{{ $userCertificate->id }}"
                                                        class="delete-custom-certificate-btn inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                                    <span class="material-symbols-outlined text-lg">delete</span>
                                                    <span>Sil</span>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards (Visible on Mobile) -->
                    <div class="lg:hidden space-y-4">
                        @foreach($otherCertificates as $userCertificate)
                            <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">Sertifika Adı:</span>
                                        <p class="text-gray-900 dark:text-white font-medium">{{ $userCertificate->certificate->certificate_name ?? $userCertificate->custom_certificate_name ?? 'Sertifika adı bulunamadı' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">Register No:</span>
                                        <p class="text-gray-900 dark:text-white">{{ $userCertificate->register_no ?? 'Belirtilmemiş' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">Şifre:</span>
                                        <p class="text-gray-900 dark:text-white">{{ $userCertificate->password ?? 'Belirtilmemiş' }}</p>
                                    </div>
                                    @if($userCertificate->issuing_institution)
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">Kurum:</span>
                                        <p class="text-gray-900 dark:text-white">{{ $userCertificate->issuing_institution }}</p>
                                    </div>
                                    @endif
                                    <div class="pt-2 border-t border-gray-200 dark:border-gray-600 flex gap-2">
                                        <a href="{{ route('certificate.download', $userCertificate->id) }}" 
                                           target="_blank"
                                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 rounded-lg transition-colors flex-1 justify-center">
                                            <span class="material-symbols-outlined text-lg">download</span>
                                            <span>İndir</span>
                                        </a>
                                        @if($loginType === 'student' && $userCertificate->file_path)
                                            <button data-id="{{ $userCertificate->id }}"
                                                    class="delete-custom-certificate-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors justify-center">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg text-gray-500 dark:text-gray-400">
                        Henüz eklenmiş bir sertifika bulunmamaktadır.
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

@if($loginType === 'student')
<!-- Custom Certificate Add Modal -->
<div id="customCertificateModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay with backdrop blur -->
        <div class="fixed inset-0 bg-gray-500/50 dark:bg-black/70 backdrop-blur-sm transition-opacity" aria-hidden="true" id="closeCustomCertificateModalBg"></div>

        <!-- Modal panel (max-w-2xl for spacious 2-column layout) -->
        <div class="inline-block align-bottom bg-white dark:bg-background-dark rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-200/85 dark:border-gray-800">
            <div class="p-6">
                <!-- Modal Header -->
                <div class="flex justify-between items-center pb-4 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-2xl">workspace_premium</span>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modal-title">Yeni Sertifika Ekle</h3>
                    </div>
                    <button type="button" id="closeCustomCertificateModalBtn" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>
                
                <form id="addCustomCertificateForm" class="mt-5" enctype="multipart/form-data">
                    @csrf

                    <!-- Stepper Header -->
                    <div class="flex items-center justify-center mb-6">
                        <div class="flex items-center gap-3">
                            <!-- Step 1 Indicator -->
                            <div id="step-1-indicator" class="flex items-center gap-2 transition-all duration-300">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm transition-all duration-300">1</div>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">Adım 1: Sertifika Yükle</span>
                            </div>
                            <!-- Line -->
                            <div class="w-12 h-0.5 bg-gray-200 dark:bg-gray-700 transition-all duration-300" id="step-line"></div>
                            <!-- Step 2 Indicator -->
                            <div id="step-2-indicator" class="flex items-center gap-2 opacity-50 transition-all duration-300">
                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-800 text-gray-500 dark:text-gray-400 flex items-center justify-center font-bold text-sm border border-gray-300 dark:border-gray-700 transition-all duration-300">2</div>
                                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Adım 2: Kontrol Et & Onayla</span>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 1 CONTAINER -->
                    <div id="step-1-container" class="space-y-5">
                        <!-- AI Scanning Status Panel -->
                        <div id="ocrStatusPanel" class="hidden p-4 rounded-2xl border transition-all duration-300 flex items-start gap-3 bg-primary/5 border-primary/20 text-primary shadow-sm">
                            <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0" id="ocrStatusIconContainer">
                                <span class="material-symbols-outlined text-xl animate-spin text-primary" id="ocrStatusIcon">refresh</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold" id="ocrStatusTitle">Tarama Aktif</h4>
                                <p class="text-xs mt-0.5 opacity-90 leading-relaxed" id="ocrStatusMessage">Sertifikanız taranıyor, bilgiler otomatik doldurulacak...</p>
                            </div>
                        </div>

                        <!-- Drag & Drop File Upload Zone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Sertifika Dosyası (PDF veya Resim)</label>
                            <div id="dropzone" class="relative group border-2 border-dashed border-gray-200 dark:border-gray-800 hover:border-primary dark:hover:border-primary rounded-2xl p-8 transition-all duration-300 bg-gray-50/50 dark:bg-gray-900/30 hover:bg-primary/5 dark:hover:bg-primary/5 cursor-pointer flex flex-col items-center justify-center text-center">
                                <!-- Hidden native input -->
                                <input type="file" name="certificate_file" id="certificate_file" accept=".pdf,image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                
                                <div id="dropzone-prompt" class="space-y-3">
                                    <div class="w-14 h-14 rounded-2xl bg-primary/10 dark:bg-primary/20 flex items-center justify-center text-primary mx-auto group-hover:scale-110 transition-transform duration-300">
                                        <span class="material-symbols-outlined text-3xl">cloud_upload</span>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                            Sertifika dosyasını sürükleyin veya seçin
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            PDF, JPG, JPEG, PNG formatları (Maks. 5MB)
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- File preview container (hidden by default) -->
                                <div id="dropzone-preview" class="hidden w-full flex items-center justify-between bg-white dark:bg-gray-900 p-4 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm z-20">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined text-2xl" id="preview-file-icon">description</span>
                                        </div>
                                        <div class="text-left">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white truncate max-w-[200px]" id="preview-file-name">DosyaAdi.pdf</div>
                                            <div class="text-xs font-medium text-gray-400 dark:text-gray-500" id="preview-file-size">1.2 MB</div>
                                        </div>
                                    </div>
                                    <button type="button" id="remove-file-btn" class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition-all duration-200">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2 CONTAINER -->
                    <div id="step-2-container" class="hidden space-y-5">
                        <!-- Verification Alert Banner -->
                        <div class="p-4 rounded-2xl border border-green-500/20 bg-green-500/5 dark:bg-green-500/10 text-green-600 dark:text-green-400 flex items-start gap-3 shadow-sm">
                            <div class="w-9 h-9 rounded-xl bg-green-500/10 dark:bg-green-500/20 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-xl">info</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold">Lütfen Bilgileri Kontrol Ediniz</h4>
                                <p class="text-xs mt-0.5 opacity-90 leading-relaxed">Sertifikanız taranmıştır. Lütfen aşağıdaki bilgilerin doğruluğunu kontrol edip onaylayınız.</p>
                            </div>
                        </div>

                        <!-- Fields Grid (2-column layout for desktop) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Sertifika Adı (Span 2) -->
                            <div class="md:col-span-2">
                                <label for="custom_certificate_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Sertifika Adı</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">workspace_premium</span>
                                    <input type="text" name="custom_certificate_name" id="custom_certificate_name" required
                                           class="pl-11 pr-4 py-2.5 w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-primary/10 focus:border-primary dark:text-white transition-all duration-200 placeholder-gray-400 dark:placeholder-gray-500 sm:text-sm"
                                           placeholder="Örn: İleri Düzey Web Geliştirme">
                                </div>
                            </div>

                            <!-- Veren Kurum -->
                            <div>
                                <label for="issuing_institution" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Veren Kurum</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">corporate_fare</span>
                                    <input type="text" name="issuing_institution" id="issuing_institution" required
                                           class="pl-11 pr-4 py-2.5 w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-primary/10 focus:border-primary dark:text-white transition-all duration-200 placeholder-gray-400 dark:placeholder-gray-500 sm:text-sm"
                                           placeholder="Örn: Avrasya Spor Enstitüsü">
                                </div>
                            </div>

                            <!-- Veriliş Tarihi -->
                            <div>
                                <label for="acquisition_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Veriliş Tarihi</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">calendar_month</span>
                                    <input type="date" name="acquisition_date" id="acquisition_date" required
                                           class="pl-11 pr-4 py-2.5 w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-primary/10 focus:border-primary dark:text-white transition-all duration-200 sm:text-sm">
                                </div>
                            </div>


                            <!-- Sertifika Numarası (Register No) -->
                            <div>
                                <label for="register_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Sertifika Numarası (Register No)</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">pin</span>
                                    <input type="text" name="register_no" id="register_no"
                                           class="pl-11 pr-4 py-2.5 w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-primary/10 focus:border-primary dark:text-white transition-all duration-200 placeholder-gray-400 dark:placeholder-gray-500 sm:text-sm"
                                           placeholder="Taramadan sonra otomatik dolar">
                                </div>
                            </div>

                            <!-- Şifre -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Şifre</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">lock</span>
                                        <input type="text" name="password" id="password" maxlength="6"
                                               class="pl-11 pr-4 py-2.5 w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-primary/10 focus:border-primary dark:text-white transition-all duration-200 placeholder-gray-400 dark:placeholder-gray-500 sm:text-sm"
                                               placeholder="6 haneli şifre">
                                    </div>
                                    <button type="button" onclick="generatePassword()"
                                            class="px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary/90 transition-colors">
                                        Üret
                                    </button>
                                </div>
                            </div>

                            <!-- İçerik 1 (Eğitim İçeriği / Dersler) - Span 2 -->
                            <div class="md:col-span-2">
                                <label for="content1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">İçerik 1 (Eğitim İçeriği / Dersler)</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">list_alt</span>
                                    <input type="text" name="content1" id="content1"
                                           class="pl-11 pr-4 py-2.5 w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-primary/10 focus:border-primary dark:text-white transition-all duration-200 placeholder-gray-400 dark:placeholder-gray-500 sm:text-sm"
                                           placeholder="Örn: 17 Hours Theoretical">
                                </div>
                            </div>

                            <!-- İçerik 2 (Açıklama / Süre vb.) - Span 2 -->
                            <div class="md:col-span-2">
                                <label for="content2" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">İçerik 2 (Açıklama / Süre vb.)</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">description</span>
                                    <input type="text" name="content2" id="content2"
                                           class="pl-11 pr-4 py-2.5 w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-primary/10 focus:border-primary dark:text-white transition-all duration-200 placeholder-gray-400 dark:placeholder-gray-500 sm:text-sm"
                                           placeholder="Örn: 28-29-30 November Pratical 2025">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions Footer -->
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-end gap-3">
                        <button type="button" id="cancelCustomCertificateBtn" class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">İptal</button>
                        <button type="button" id="nextStepBtn" disabled class="opacity-50 cursor-not-allowed bg-primary text-white px-5 py-2 rounded-lg font-semibold text-sm hover:bg-primary/90 transition-all flex items-center gap-2">
                            Devam Et
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </button>
                        <button type="button" id="backStepBtn" class="hidden bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">arrow_back</span>
                            Geri
                        </button>
                        <button type="submit" id="submitBtn" class="hidden bg-primary text-white px-5 py-2 rounded-lg font-semibold text-sm hover:bg-primary/90 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">save</span>
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function generatePassword() {
    const password = Math.floor(100000 + Math.random() * 900000).toString();
    const passwordInput = document.querySelector('#customCertificateModal #password');
    if (passwordInput) {
        passwordInput.value = password;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('customCertificateModal');
    const openBtn = document.getElementById('addCustomCertificateBtn');
    const closeBtn = document.getElementById('closeCustomCertificateModalBtn');
    const closeBg = document.getElementById('closeCustomCertificateModalBg');
    const cancelBtn = document.getElementById('cancelCustomCertificateBtn');
    const form = document.getElementById('addCustomCertificateForm');

    // Stepper Wizard Buttons & States
    let currentStep = 1;
    const nextBtn = document.getElementById('nextStepBtn');
    const backBtn = document.getElementById('backStepBtn');
    const submitBtn = document.getElementById('submitBtn');

    if (openBtn) {
        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setStep(1);
        });
    }

    const hideModal = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        resetForm();
    };

    if (closeBtn) closeBtn.addEventListener('click', hideModal);
    if (closeBg) closeBg.addEventListener('click', hideModal);
    if (cancelBtn) cancelBtn.addEventListener('click', hideModal);

    // Stepper function
    function setStep(step) {
        currentStep = step;
        const step1Container = document.getElementById('step-1-container');
        const step2Container = document.getElementById('step-2-container');
        const step1Ind = document.getElementById('step-1-indicator');
        const step1IndCircle = step1Ind.querySelector('div');
        const step2Ind = document.getElementById('step-2-indicator');
        const step2IndCircle = step2Ind.querySelector('div');
        const stepLine = document.getElementById('step-line');

        if (step === 1) {
            step1Container.classList.remove('hidden');
            step2Container.classList.add('hidden');

            // Stepper indicator updates
            step1Ind.classList.remove('opacity-50');
            step1IndCircle.className = "w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm transition-all duration-300";
            step1IndCircle.innerHTML = "1";

            step2Ind.classList.add('opacity-50');
            step2IndCircle.className = "w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-800 text-gray-500 dark:text-gray-400 flex items-center justify-center font-bold text-sm border border-gray-300 dark:border-gray-700 transition-all duration-300";
            step2IndCircle.innerHTML = "2";

            stepLine.className = "w-12 h-0.5 bg-gray-200 dark:bg-gray-700 transition-all duration-300";

            // Footer controls
            nextBtn.classList.remove('hidden');
            backBtn.classList.add('hidden');
            submitBtn.classList.add('hidden');
        } else if (step === 2) {
            step1Container.classList.add('hidden');
            step2Container.classList.remove('hidden');

            // Stepper indicator updates
            step1Ind.classList.add('opacity-50');
            step1IndCircle.className = "w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-bold text-sm transition-all duration-300 animate-pulse";
            step1IndCircle.innerHTML = '<span class="material-symbols-outlined text-base">check</span>';

            step2Ind.classList.remove('opacity-50');
            step2IndCircle.className = "w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm transition-all duration-300";
            step2IndCircle.innerHTML = "2";

            stepLine.className = "w-12 h-0.5 bg-primary transition-all duration-300";

            // Footer controls
            nextBtn.classList.add('hidden');
            backBtn.classList.remove('hidden');
            submitBtn.classList.remove('hidden');
        }
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            if (currentStep === 1) setStep(2);
        });
    }

    if (backBtn) {
        backBtn.addEventListener('click', () => {
            if (currentStep === 2) setStep(1);
        });
    }

    // Form submit AJAX
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            
            const submitBtnEl = document.getElementById('submitBtn');
            const originalContent = submitBtnEl.innerHTML;
            submitBtnEl.innerHTML = '<span class="material-symbols-outlined text-base animate-spin">refresh</span> Kaydediliyor...';
            submitBtnEl.disabled = true;

            fetch('{{ route("portal.custom-certificate.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Başarılı!',
                        text: 'Sertifikanız başarıyla eklendi.',
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
                    text: 'Sertifika yüklenirken bir hata oluştu.',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                });
            })
            .finally(() => {
                submitBtnEl.innerHTML = originalContent;
                submitBtnEl.disabled = false;
            });
        });
    }

    // Custom certificate delete logic
    document.querySelectorAll('.delete-custom-certificate-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Emin misiniz?',
                text: "Bu sertifikayı silmek istediğinize emin misiniz?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, Sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("portal.custom-certificate.delete") }}', {
                        method: 'POST',
                        body: JSON.stringify({ id: id }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Silindi!',
                                text: 'Sertifikanız başarıyla silindi.',
                                icon: 'success',
                                confirmButtonText: 'Tamam'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Hata!',
                                text: data.message || 'Silme işlemi başarısız oldu.',
                                icon: 'error',
                                confirmButtonText: 'Tamam'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Hata!',
                            text: 'Silme işlemi sırasında bir hata oluştu.',
                            icon: 'error',
                            confirmButtonText: 'Tamam'
                        });
                    });
                }
            });
        });
    });

    // Drag & Drop + AJAX Certificate Parsing using backend Gemini API
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('certificate_file');
    const registerNoInput = document.getElementById('register_no');
    
    const certNameInput = document.getElementById('custom_certificate_name');
    const certInstitutionInput = document.getElementById('issuing_institution');
    const certDateInput = document.getElementById('acquisition_date');
    const content1Input = document.getElementById('content1');
    const content2Input = document.getElementById('content2');

    if (dropzone && fileInput) {
        // Prevent default behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Highlight drop zone on dragover
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => {
                dropzone.classList.add('border-primary', 'bg-primary/5', 'dark:bg-primary/5', 'scale-[1.02]', 'shadow-lg', 'shadow-primary/5');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => {
                dropzone.classList.remove('border-primary', 'bg-primary/5', 'dark:bg-primary/5', 'scale-[1.02]', 'shadow-lg', 'shadow-primary/5');
            }, false);
        });

        // Handle dropped files
        dropzone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });

        // Native file input change
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            handleFileSelect(file);
        });

        // Remove file button
        const removeBtn = document.getElementById('remove-file-btn');
        if (removeBtn) {
            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                e.preventDefault();
                resetDropzone();
            });
        }
    }

    function handleFileSelect(file) {
        if (!file) {
            resetDropzone();
            return;
        }

        // Show preview card
        const prompt = document.getElementById('dropzone-prompt');
        const preview = document.getElementById('dropzone-preview');
        const nameText = document.getElementById('preview-file-name');
        const sizeText = document.getElementById('preview-file-size');
        const fileIcon = document.getElementById('preview-file-icon');

        if (prompt) prompt.classList.add('hidden');
        if (preview) preview.classList.remove('hidden');

        if (nameText) nameText.textContent = file.name;
        
        if (sizeText) {
            const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
            sizeText.textContent = `${sizeInMB} MB`;
        }

        if (fileIcon) {
            if (file.type === 'application/pdf' || file.name.endsWith('.pdf')) {
                fileIcon.textContent = 'picture_as_pdf';
            } else {
                fileIcon.textContent = 'image';
            }
        }



        // Start parsing
        parseCertificateAJAX(file);
    }

    function resetDropzone() {
        if (fileInput) fileInput.value = '';
        const prompt = document.getElementById('dropzone-prompt');
        const preview = document.getElementById('dropzone-preview');
        if (prompt) prompt.classList.remove('hidden');
        if (preview) preview.classList.add('hidden');

        const panel = document.getElementById('ocrStatusPanel');
        if (panel) panel.classList.add('hidden');

        if (nextBtn) {
            nextBtn.disabled = true;
            nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    function resetForm() {
        if (form) form.reset();
        resetDropzone();
        setStep(1);
    }

    function updateOcrStatus(state, message = '') {
        const panel = document.getElementById('ocrStatusPanel');
        const iconContainer = document.getElementById('ocrStatusIconContainer');
        const icon = document.getElementById('ocrStatusIcon');
        const title = document.getElementById('ocrStatusTitle');
        const msg = document.getElementById('ocrStatusMessage');

        if (!panel) return;

        panel.classList.remove('hidden');

        // Reset classes
        panel.className = "mb-5 p-4 rounded-2xl border transition-all duration-300 flex items-start gap-3 shadow-sm";
        iconContainer.className = "w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0";
        icon.className = "material-symbols-outlined text-xl";

        if (state === 'loading') {
            panel.classList.add('bg-primary/5', 'border-primary/20', 'text-primary');
            iconContainer.classList.add('bg-primary/10');
            icon.classList.add('animate-spin', 'text-primary');
            icon.textContent = 'refresh';
            title.textContent = 'Tarama Aktif';
            msg.textContent = message || 'Sertifikanız taranıyor, bilgiler otomatik doldurulacak...';
        } else if (state === 'success') {
            panel.classList.add('bg-green-500/10', 'border-green-500/20', 'text-green-600', 'dark:text-green-400');
            iconContainer.classList.add('bg-green-500/20');
            icon.classList.add('text-green-600', 'dark:text-green-400');
            icon.textContent = 'check_circle';
            title.textContent = 'Tarama Başarılı';
            msg.textContent = message || 'Sertifika bilgileri başarıyla dolduruldu. Lütfen alanları kontrol ediniz.';
        } else if (state === 'warning') {
            panel.classList.add('bg-amber-500/10', 'border-amber-500/20', 'text-amber-600', 'dark:text-amber-400');
            iconContainer.classList.add('bg-amber-500/20');
            icon.classList.add('text-amber-600', 'dark:text-amber-400');
            icon.textContent = 'warning';
            title.textContent = 'Tarama Uyarısı';
            msg.textContent = message || 'Sertifika otomatik okunamadı, bilgileri kendiniz doldurabilirsiniz.';
        } else if (state === 'error') {
            panel.classList.add('bg-red-500/10', 'border-red-500/20', 'text-red-600', 'dark:text-red-400');
            iconContainer.classList.add('bg-red-500/20');
            icon.classList.add('text-red-600', 'dark:text-red-400');
            icon.textContent = 'error';
            title.textContent = 'Hata';
            msg.textContent = message || 'Tarama sırasında bir hata oluştu.';
        }
    }

    function parseCertificateAJAX(file) {
        const formData = new FormData();
        formData.append('certificate_file', file);

        // Disable next step button while scanning
        if (nextBtn) {
            nextBtn.disabled = true;
            nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }

        updateOcrStatus('loading');
        if (registerNoInput) registerNoInput.placeholder = "Tarama yapılıyor...";
        if (certNameInput) certNameInput.placeholder = "Tarama yapılıyor...";
        if (certInstitutionInput) certInstitutionInput.placeholder = "Tarama yapılıyor...";
        if (content1Input) content1Input.placeholder = "Tarama yapılıyor...";
        if (content2Input) content2Input.placeholder = "Tarama yapılıyor...";

        fetch('{{ route("portal.custom-certificate.parse") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success && res.data) {
                const data = res.data;
                
                if (registerNoInput && data.register_no) registerNoInput.value = data.register_no;
                if (certNameInput && data.certificate_name) certNameInput.value = data.certificate_name;
                if (certInstitutionInput && data.issuing_institution) certInstitutionInput.value = data.issuing_institution;
                if (certDateInput && data.acquisition_date) certDateInput.value = data.acquisition_date;
                if (content1Input && data.content1) content1Input.value = data.content1;
                if (content2Input && data.content2) content2Input.value = data.content2;

                updateOcrStatus('success', 'Sertifika bilgileri başarıyla dolduruldu. Lütfen alanları kontrol ediniz.');
                
                setTimeout(() => {
                    setStep(2);
                }, 800);
            } else {
                updateOcrStatus('warning', res.message || 'Sertifika otomatik okunamadı, bilgileri kendiniz doldurabilirsiniz.');
                
                Swal.fire({
                    title: 'Hata!',
                    text: res.message || 'Sertifika otomatik okunamadı.',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                });
            }
        })
        .catch(error => {
            console.error('Parsing error:', error);
            updateOcrStatus('error', 'Bağlantı hatası veya beklenmeyen bir hata oluştu. Bilgileri kendiniz girebilirsiniz.');
        })
        .finally(() => {
            if (registerNoInput) registerNoInput.placeholder = "Taramadan sonra otomatik dolar";
            if (certNameInput) certNameInput.placeholder = "Örn: İleri Düzey Web Geliştirme";
            if (certInstitutionInput) certInstitutionInput.placeholder = "Örn: Avrasya Spor Enstitüsü";
            if (content1Input) content1Input.placeholder = "Taramadan sonra otomatik dolar";
            if (content2Input) content2Input.placeholder = "Taramadan sonra otomatik dolar";

            // Enable next step button when scanning finishes
            if (nextBtn) {
                nextBtn.disabled = false;
                nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }
});
</script>
@endif

@include('portal-user.partials.footer')
