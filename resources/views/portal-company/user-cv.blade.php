@include('portal-company.partials.header')

<!-- main content -->
<main class="flex-1 p-4 lg:p-8">
    <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm">
        <div class="p-4 lg:p-6 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center">
            <div>
                <h2 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white">CV</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $student->name }} {{ $student->surname }}</p>
            </div>
            <a href="{{ route('company-portal.main') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-600 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Geri Dön
            </a>
        </div>
        <div class="p-4 lg:p-6">
            <!-- İletişim Bilgileri - contact_info kapalıysa *** göster -->
            <div class="mb-8 lg:mb-10">
                <h3 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white mb-4">İletişim Bilgileri</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                    <div class="flex items-center text-gray-600 dark:text-gray-300">
                        <span class="material-symbols-outlined mr-2 text-sm">person</span>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Ad Soyad</span>
                            <p class="text-sm lg:text-base font-medium text-gray-900 dark:text-white">
                                @if($student->contact_info)
                                    {{ $student->name }} {{ $student->surname }}
                                @else
                                    ***
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($student->birth_date)
                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                            <span class="material-symbols-outlined mr-2 text-sm">cake</span>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Doğum Tarihi</span>
                                <p class="text-sm lg:text-base font-medium text-gray-900 dark:text-white">
                                    @if($student->contact_info)
                                        {{ $student->birth_date->format('d.m.Y') }}
                                    @else
                                        ***
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif
                    @if($student->gsm)
                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                            <span class="material-symbols-outlined mr-2 text-sm">phone</span>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Telefon</span>
                                <p class="text-sm lg:text-base font-medium text-gray-900 dark:text-white">
                                    @if($student->contact_info)
                                        {{ $student->gsm }}
                                    @else
                                        ***
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif
                    @if($student->email)
                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                            <span class="material-symbols-outlined mr-2 text-sm">email</span>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Email</span>
                                <p class="text-sm lg:text-base font-medium text-gray-900 dark:text-white">
                                    @if($student->contact_info)
                                        {{ $student->email }}
                                    @else
                                        ***
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif
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
                                    @if($education->degree)
                                        <p class="mt-1 text-gray-600 dark:text-gray-300 text-sm lg:text-base">Derece: {{ $education->degree }}</p>
                                    @endif
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

            <!-- Sertifikalar -->
            @if($student->userCertificates->count() > 0)
                <div class="mt-8 lg:mt-10">
                    <h4 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white mb-4">Sertifikalar</h4>
                    <div class="space-y-2">
                        @foreach($student->userCertificates as $userCertificate)
                            <div class="flex items-center text-gray-700 dark:text-gray-300">
                                <span class="material-symbols-outlined mr-2 text-primary text-sm">verified</span>
                                <span class="text-sm lg:text-base">{{ $userCertificate->certificate->certificate_name ?? 'Sertifika adı bulunamadı' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</main>

@include('portal-company.partials.footer')