@include('user.partials.header')

<main class="flex-1 p-4 lg:p-8 overflow-x-auto">
    <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm max-w-full">
            <div class="p-4 lg:p-6 border-b border-gray-200 dark:border-gray-800">
                <h2 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white">CV Düzenle</h2>
            </div>
            <div class="p-4 lg:p-6 space-y-6 lg:space-y-8">
                <!-- CV Güncellendi Bildirimi -->
                <div id="cvUpdateNotification" class="hidden bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400 mr-3">check_circle</span>
                        <span class="text-green-800 dark:text-green-200 font-medium">Fotoğraf başarıyla güncellendi!</span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white">Kişisel Bilgiler</h3>
                    <!-- Responsive kullanıcı bilgileri alanı -->
                    <div class="flex flex-col lg:flex-row lg:items-start gap-4 lg:gap-6">
                        <!-- Profil resmi - Auth user'dan gelen bilgiler -->
                        <div class="relative mx-auto lg:mx-0 flex-shrink-0">
                            @if($user->profile_photo_url)
                                <img id="profileImage" alt="Profil Resmi" class="w-24 h-24 lg:w-32 lg:h-32 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700" src="{{ $user->profile_photo_url }}"/>
                            @else
                                <div class="w-24 h-24 lg:w-32 lg:h-32 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center border-2 border-gray-200 dark:border-gray-700">
                                    <span class="material-symbols-outlined text-gray-500 text-2xl lg:text-3xl">person</span>
                                </div>
                            @endif
                            <button id="editProfileImage" class="absolute bottom-0 right-0 bg-primary text-white w-8 h-8 lg:w-9 lg:h-9 rounded-full hover:bg-primary/90 transition-colors shadow-md border-2 border-white dark:border-gray-800 flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </button>
                            <!-- Gizli dosya input -->
                            <input type="file" id="profileImageInput" accept="image/*" class="hidden"/>
                        </div>
                        
                        <!-- Form alanları - Auth user'dan gelen veriler -->
                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 min-w-0">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="fullName">Ad Soyad</label>
                                <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm bg-gray-100 dark:bg-gray-700" 
                                   id="fullName" 
                                   type="text" 
                                   value="{{ $user->name }} {{ $user->surname }}" 
                                   readonly 
                                   disabled/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="birthDate">Doğum Tarihi</label>
                                <div class="relative">
                                    <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm pr-10 bg-gray-100 dark:bg-gray-700" 
                                           id="birthDate" 
                                           type="date" 
                                           value="{{ $user->birth_date ? $user->birth_date->format('Y-m-d') : '' }}" 
                                           readonly 
                                           disabled/>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="material-symbols-outlined text-gray-400 text-sm">calendar_today</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="phone">Telefon</label>
                                <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" 
                                       id="phone" 
                                       type="tel" 
                                       value="{{ $user->gsm ?? '' }}"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="email">E-posta</label>
                                <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" 
                                       id="email" 
                                       type="email" 
                                       value="{{ $user->email }}"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="contactInfo">İletişim Bilgisi</label>
                                <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" 
                                        id="contactInfo" 
                                        name="contact_info">
                                    <option value="1" {{ $user->contact_info ? 'selected' : '' }}>Açık</option>
                                    <option value="0" {{ !$user->contact_info ? 'selected' : '' }}>Kapalı</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="tcNum">TC Kimlik No</label>
                                <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" 
                                       id="tcNum" 
                                       type="text" 
                                       value="{{ $user->tc_num ?? '' }}"
                                       maxlength="11"
                                       pattern="[0-9]{11}"/>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                            <h3 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white">Deneyimler</h3>
                            <button id="addExperience" class="bg-green-500 text-white px-3 py-1.5 rounded-lg font-semibold text-sm hover:bg-green-600 transition-colors flex items-center gap-1 w-full sm:w-auto justify-center">
                            <span class="material-symbols-outlined text-base">add</span> Ekle
                            </button>
                        </div>
                        <div class="new-field">
                            
                        </div>
                        <!-- user's current experiences -->
                        @forelse($experiences as $experience)
                            <div class="p-4 border rounded-lg dark:border-gray-700 space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Pozisyon" type="text" value="{{ $experience->position }}" readonly/>
                                    <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Şirket" type="text" value="{{ $experience->company_name }}" readonly/>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Başlangıç Tarihi</label>
                                        <div class="relative">
                                        <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm pr-10" type="month" value="{{ $experience->start_date ? \Carbon\Carbon::parse($experience->start_date)->format('Y-m') : '' }}" readonly/>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <span class="material-symbols-outlined text-gray-400 text-sm">event</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Deneyim bölümünde bitiş tarihi -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bitiş Tarihi</label>
                                        <div class="relative">
                                            <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm pr-10" type="month" value="{{ $experience->end_date ? \Carbon\Carbon::parse($experience->end_date)->format('Y-m') : '' }}" readonly/>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <span class="material-symbols-outlined text-gray-400 text-sm">event</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <textarea class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Açıklama" rows="3" readonly>{{ $experience->description }}</textarea>
                                <div class="flex justify-end gap-2">
                                    <button type="button" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 delete-experience" data-id="{{ $experience->id }}">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                Henüz deneyim eklenmemiş.
                            </div>
                        @endforelse
                        
                        <div id="experiencesContainer" class="space-y-4">
                           
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                            <h3 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white">Eğitimler</h3>
                            <button id="addEducation" class="bg-green-500 text-white px-3 py-1.5 rounded-lg font-semibold text-sm hover:bg-green-600 transition-colors flex items-center gap-1 w-full sm:w-auto justify-center">
                                <span class="material-symbols-outlined text-base">add</span> Ekle
                            </button>
                        </div>
                        
                        <!-- Yeni eğitim ekleme alanı - başa eklenmeli -->
                        <div id="educationsContainer" class="space-y-4">
                            <!-- Yeni eğitim ekleme alanı buraya gelecek -->
                        </div>
                        
                        <!-- Kullanıcının mevcut eğitimleri -->
                        @forelse($educations as $education)
                            <div class="p-4 border rounded-lg dark:border-gray-700 space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Okul" type="text" value="{{ $education->school_name }}" readonly/>
                                    <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Bölüm" type="text" value="{{ $education->field_of_study }}" readonly/>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Başlangıç Tarihi</label>
                                        <div class="relative">
                                        <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm pr-10" type="month" value="{{ $education->start_date ? \Carbon\Carbon::parse($education->start_date)->format('Y-m') : '' }}" readonly/>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <span class="material-symbols-outlined text-gray-400 text-sm">event</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Eğitim bölümünde bitiş tarihi -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bitiş Tarihi</label>
                                        <div class="relative">
                                            <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm pr-10" type="month" value="{{ $education->end_date ? \Carbon\Carbon::parse($education->end_date)->format('Y-m') : '' }}" readonly/>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <span class="material-symbols-outlined text-gray-400 text-sm">event</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Derece" type="text" value="{{ $education->degree }}" readonly/>
                                <textarea class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Açıklama" rows="3" readonly>{{ $education->description }}</textarea>
                                <div class="flex justify-end gap-2">
                                    <button type="button" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 delete-education" data-id="{{ $education->id }}">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                Henüz eğitim eklenmemiş.
                            </div>
                        @endforelse
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h3 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white">Yetenekler</h3>
            <button id="addSkill" class="bg-green-500 text-white px-3 py-1.5 rounded-lg font-semibold text-sm hover:bg-green-600 transition-colors flex items-center gap-1 w-full sm:w-auto justify-center">
                <span class="material-symbols-outlined text-base">add</span> Ekle
            </button>
        </div>
        
        <!-- Yeni yetenek ekleme alanı - başa eklenmeli -->
        <div id="skillsContainer" class="space-y-4">
            <!-- Yeni yetenek ekleme alanı buraya gelecek -->
        </div>
        
        <!-- Kullanıcının mevcut yetenekleri -->
        @forelse($abilities as $ability)
            <div class="p-4 border rounded-lg dark:border-gray-700 space-y-3">
                <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Yetenek" type="text" value="{{ $ability->abilities_name }}" readonly/>
                <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" disabled>
                    <option {{ $ability->level == 'beginner' ? 'selected' : '' }}>Başlangıç</option>
                    <option {{ $ability->level == 'intermediate' ? 'selected' : '' }}>Orta</option>
                    <option {{ $ability->level == 'advanced' ? 'selected' : '' }}>İleri</option>
                    <option {{ $ability->level == 'expert' ? 'selected' : '' }}>Uzman</option>
                </select>
                <div class="flex justify-end gap-2">
                    <button type="button" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 delete-ability" data-id="{{ $ability->id }}">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                Henüz yetenek eklenmemiş.
            </div>
        @endforelse
    </div>
    
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h3 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white">Diller</h3>
            <button id="addLanguage" class="bg-green-500 text-white px-3 py-1.5 rounded-lg font-semibold text-sm hover:bg-green-600 transition-colors flex items-center gap-1 w-full sm:w-auto justify-center">
                <span class="material-symbols-outlined text-base">add</span> Ekle
            </button>
        </div>
        
        <!-- Yeni dil ekleme alanı - başa eklenmeli -->
        <div id="languagesContainer" class="space-y-4">
            <!-- Yeni dil ekleme alanı buraya gelecek -->
        </div>
        
        <!-- Kullanıcının mevcut dilleri -->
        @forelse($languages as $language)
            <div class="p-4 border rounded-lg dark:border-gray-700 space-y-3">
                <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Dil" type="text" value="{{ $language->language_name }}" readonly/>
                <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" disabled>
                    <option {{ $language->level == 'basic' ? 'selected' : '' }}>Temel</option>
                    <option {{ $language->level == 'conversational' ? 'selected' : '' }}>Konuşma</option>
                    <option {{ $language->level == 'fluent' ? 'selected' : '' }}>Akıcı</option>
                    <option {{ $language->level == 'native' ? 'selected' : '' }}>Ana Dil</option>
                </select>
                <div class="flex justify-end gap-2">
                    <button type="button" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 delete-language" data-id="{{ $language->id }}">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                Henüz dil eklenmemiş.
            </div>
        @endforelse
    </div>
</div>

                @if($user->userBadges->count() > 0)
                    <div class="mt-8 lg:mt-10">
                        <h3 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white mb-4">Kazanılan Rozetler</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 lg:gap-6">
                            @foreach($user->userBadges as $userBadge)
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

                <div class="mt-8 lg:mt-10">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white">Sertifikalar</h3>
                        <button id="addCustomCertificateBtn" type="button" class="bg-primary text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-primary/90 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">add</span>
                            Sertifika Ekle
                        </button>
                    </div>

                    @if($user->userCertificates->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-6 py-3" scope="col">Sertifika Adı</th>
                                        <th class="px-6 py-3" scope="col">Kodu</th>
                                        <th class="px-6 py-3" scope="col">Kurum</th>
                                        <th class="px-6 py-3" scope="col">Şifre</th>
                                        <th class="px-6 py-3" scope="col">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->userCertificates as $userCertificate)
                                        @php
                                            $certificate = $userCertificate->certificate;
                                            $courses = $certificate ? $certificate->certificateEducations : collect();
                                            $certificateLessons = $userCertificate->certificateLessons ?? collect();
                                            
                                            // certificate_lessons tablosundan puanları al
                                            $lessonScores = [];
                                            foreach ($certificateLessons as $lesson) {
                                                $lessonScores[$lesson->certificate_education_id] = $lesson->score;
                                            }
                                        @endphp
                                        <tr class="bg-white border-b dark:bg-background-dark dark:border-gray-700">
                                            <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" scope="row">
                                                {{ $userCertificate->certificate->certificate_name ?? $userCertificate->custom_certificate_name ?? 'Sertifika adı bulunamadı' }}
                                            </th>
                                            <td class="px-6 py-4">{{ $userCertificate->certificate_code ?? $userCertificate->register_no ?? 'Belirtilmemiş' }}</td>
                                            <td class="px-6 py-4">{{ $userCertificate->issuing_institution ?? 'Belirtilmemiş' }}</td>
                                            <td class="px-6 py-4">{{ $userCertificate->password ?? 'Belirtilmemiş' }}</td>
                                            <td class="px-6 py-4 flex gap-2">
                                                <a href="{{ route('certificate.download', $userCertificate->id) }}" 
                                                   target="_blank"
                                                   class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 rounded-lg transition-colors"
                                                   title="Sertifikayı İndir">
                                                    <span class="material-symbols-outlined text-lg">download</span>
                                                    <span>İndir</span>
                                                </a>
                                                @if($userCertificate->file_path)
                                                    <button type="button" data-id="{{ $userCertificate->id }}"
                                                            class="delete-custom-certificate-btn inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                                        <span class="material-symbols-outlined text-lg">delete</span>
                                                        <span>Sil</span>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($courses->count() > 0)
                                            @php
                                                $totalScore = (int)($userCertificate->achievement_score ?? 0);
                                                $courseCount = $courses->count();
                                                $scorePerCourse = $courseCount > 0 ? (int)($totalScore / $courseCount) : 0;
                                                $remainder = $courseCount > 0 ? $totalScore % $courseCount : 0;
                                            @endphp
                                            <tr class="bg-gray-50 dark:bg-gray-800/50">
                                                <td colspan="5" class="px-6 py-4">
                                                    <div class="ml-4">
                                                        <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Dersler:</h5>
                                                        <div class="space-y-1">
                                                            @foreach($courses as $index => $course)
                                                                @php
                                                                    // certificate_lessons tablosundan puan varsa onu kullan, yoksa eşit dağıt (eski kayıtlar için)
                                                                    if (isset($lessonScores[$course->id])) {
                                                                        $courseScore = $lessonScores[$course->id];
                                                                    } else {
                                                                        // Eşit dağıt (eski kayıtlar için)
                                                                        $courseScore = $scorePerCourse + ($index === $courseCount - 1 ? $remainder : 0);
                                                                    }
                                                                @endphp
                                                                <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                                                                    <span>{{ $course->course_name }}</span>
                                                                    <span class="font-medium text-gray-900 dark:text-white">{{ $courseScore }} Puan</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg text-gray-500 dark:text-gray-400">
                            Henüz eklenmiş bir sertifika bulunmamaktadır.
                        </div>
                    @endif
                </div>
                </div>
            </div>
            <div class="p-4 lg:p-6 border-t border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row justify-end gap-4">
                <button id="saveProfileBtn" class="bg-primary text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-primary/90 transition-colors flex items-center gap-2 justify-center w-full sm:w-auto">
                    <span class="material-symbols-outlined text-base">save</span>
                    Kaydet
                </button>
            </div>
    </div>
</main>

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
                            <div class="hidden">
                                <label for="acquisition_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Veriliş Tarihi</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">calendar_month</span>
                                    <input type="date" name="acquisition_date" id="acquisition_date"
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

@include('user.partials.footer')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
    // Profil resmi düzenleme
    const editProfileImage = document.getElementById('editProfileImage');
    const profileImageInput = document.getElementById('profileImageInput');
    const profileImage = document.getElementById('profileImage');

    editProfileImage.addEventListener('click', function() {
        profileImageInput.click();
    });

    profileImageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            // Dosya türü kontrolü
            if (!file.type.startsWith('image/')) {
                alert('Lütfen sadece resim dosyası seçin.');
                return;
            }
            
            // Dosya boyutu kontrolü (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Dosya boyutu 5MB\'dan küçük olmalıdır.');
                return;
            }
            
            // Resmi önizleme
            const reader = new FileReader();
            reader.onload = function(e) {
                profileImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
            
            // Backend'e gönder
            uploadProfilePhoto(file);
        }
    });

    // Profil resmi yükleme fonksiyonu
    function uploadProfilePhoto(file) {
        const formData = new FormData();
        formData.append('profile_photo', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Loading göster
        const editButton = document.getElementById('editProfileImage');
        const originalContent = editButton.innerHTML;
        editButton.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">refresh</span>';
        editButton.disabled = true;
        
        fetch('{{ route("user.profile-photo") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Toastr yerine bildirim alanını göster
                showCvUpdateNotification();
                // Resmi güncelle
                profileImage.src = data.data.profile_photo_url;
            } else {
                // Hata mesajı
                showNotification(data.message || 'Resim yüklenirken hata oluştu!', 'error');
                // Eski resmi geri yükle
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Resim yüklenirken hata oluştu!', 'error');
            // Eski resmi geri yükle
            location.reload();
        })
        .finally(() => {
            // Loading'i kaldır
            editButton.innerHTML = originalContent;
            editButton.disabled = false;
        });
    }

    // Bildirim gösterme fonksiyonu - toastr benzeri
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <span class="material-symbols-outlined mr-2">${type === 'success' ? 'check_circle' : 'error'}</span>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animasyon için başlangıç pozisyonu
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        
        // Animasyonu başlat
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        }, 100);
        
        // 3 saniye sonra kaldır
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
        setTimeout(() => {
            notification.remove();
            }, 300);
        }, 3000);
    }

    // CV güncellendi bildirimi gösterme fonksiyonu
    function showCvUpdateNotification() {
        const notification = document.getElementById('cvUpdateNotification');
        if (notification) {
            notification.classList.remove('hidden');
            
            // 3 saniye sonra gizle
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 3000);
        }
    }

    // Ekle butonları için JavaScript
    let experienceCounter = 1;
    let educationCounter = 1;
    let skillCounter = 1;
    let languageCounter = 1;


    // Deneyim ekleme
    document.getElementById('addExperience').addEventListener('click', function() {
        // new-field div'ini bul
        const newFieldContainer = document.querySelector('.new-field');
        
        if (newFieldContainer) {
            createNewFieldInContainer(newFieldContainer, 'experience');
        } 
        experienceCounter++;
    });

    // Yeni alan oluşturma fonksiyonu - container belirtilebilir
    function createNewFieldInContainer(container, fieldType) {
        const newField = document.createElement('div');
        newField.className = 'p-4 border rounded-lg dark:border-gray-700 space-y-3 mt-4'; // mt-4 eklendi
        newField.setAttribute('data-is-new', 'true');
        
        let fieldHTML = '';
        
        // createNewField fonksiyonunda - deneyim için
        if (fieldType === 'experience') {
            fieldHTML = `
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Pozisyon" type="text"/>
                    <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Şirket" type="text"/>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Başlangıç Tarihi</label>
                        <div class="relative">
                            <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" type="month"/>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bitiş Tarihi</label>
                        <div class="relative">
                            <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" type="month"/>
                        </div>
                    </div>
                </div>
                <textarea class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Açıklama" rows="3"></textarea>
            `;
        } else if (fieldType === 'education') {
            fieldHTML = `
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Okul" type="text"/>
                    <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Bölüm" type="text"/>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Başlangıç Tarihi</label>
                        <div class="relative">
                            <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" type="month"/>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bitiş Tarihi</label>
                        <div class="relative">
                            <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" type="month"/>
                        </div>
                    </div>
                </div>
                <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Derece" type="text"/>
                <textarea class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Açıklama" rows="3"></textarea>
            `;
        }else if (fieldType === 'skill') {
            fieldHTML = `
                <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Yetenek" type="text"/>
                <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm">
                    <option value="beginner">Başlangıç</option>
                    <option value="intermediate">Orta</option>
                    <option value="advanced">İleri</option>
                    <option value="expert">Uzman</option>
                </select>
            `;
        } else if (fieldType === 'language') {
            fieldHTML = `
                <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Dil" type="text"/>
                <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm">
                    <option value="basic">Temel</option>
                    <option value="conversational">Konuşma</option>
                    <option value="fluent">Akıcı</option>
                    <option value="native">Ana Dil</option>
                </select>
            `;
        }
        
        // Kaydet/İptal/Sil butonları
        fieldHTML += `
            <div class="flex justify-between items-center">
                <div class="flex gap-2">
                    <button class="bg-primary text-white px-3 py-1.5 rounded-lg text-sm hover:bg-primary/90 transition-colors flex items-center gap-1 save-btn">
                        <span class="material-symbols-outlined text-sm">save</span>
                        Kaydet
                    </button>
                    <button class="bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-sm hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors cancel-btn">
                        İptal
                    </button>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 delete-new-btn">
                    <span class="material-symbols-outlined">delete</span>
                </button>
                </div>
            </div>
        `;
        
        newField.innerHTML = fieldHTML;
        addInputFocusListeners(newField); // Bu satırı ekleyin
        
        // Yeni alanı container'ın başına ekle
        container.insertBefore(newField, container.firstChild);
        
        // Kaydet butonuna tıklama
        newField.querySelector('.save-btn').addEventListener('click', function() {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            let endpoint = '';
            
            if (fieldType === 'experience') {
                const positionInput = newField.querySelector('input[placeholder="Pozisyon"]');
                const companyInput = newField.querySelector('input[placeholder="Şirket"]');
                const dateInputs = newField.querySelectorAll('input[type="month"]');
                const descriptionInput = newField.querySelector('textarea');
                
                formData.append('position', positionInput ? positionInput.value : '');
                formData.append('company_name', companyInput ? companyInput.value : '');
                formData.append('start_date', dateInputs[0] ? dateInputs[0].value + '-01' : '');
                formData.append('end_date', dateInputs[1] && dateInputs[1].value ? dateInputs[1].value + '-01' : '');
                formData.append('description', descriptionInput ? descriptionInput.value : '');
                
                endpoint = '{{ route("user.experience.store") }}';
            } else if (fieldType === 'education') {
                const schoolInput = newField.querySelector('input[placeholder="Okul"]');
                const departmentInput = newField.querySelector('input[placeholder="Bölüm"]');
                const dateInputs = newField.querySelectorAll('input[type="month"]');
                const degreeInput = newField.querySelector('input[placeholder="Derece"]');
                const descriptionInput = newField.querySelector('textarea');
                
                formData.append('school_name', schoolInput ? schoolInput.value : '');
                formData.append('field_of_study', departmentInput ? departmentInput.value : '');
                formData.append('start_date', dateInputs[0] ? dateInputs[0].value + '-01' : '');
                formData.append('end_date', dateInputs[1] && dateInputs[1].value ? dateInputs[1].value + '-01' : '');
                formData.append('degree', degreeInput ? degreeInput.value : '');
                formData.append('description', descriptionInput ? descriptionInput.value : '');
                
                endpoint = '{{ route("user.education.store") }}';
            } else if (fieldType === 'skill') {
                const skillInput = newField.querySelector('input[placeholder="Yetenek"]');
                const levelSelect = newField.querySelector('select');
                
                formData.append('abilities_name', skillInput ? skillInput.value : '');
                formData.append('level', levelSelect ? levelSelect.value : '');
                
                endpoint = '{{ route("user.ability.store") }}';
            } else if (fieldType === 'language') {
                const languageInput = newField.querySelector('input[placeholder="Dil"]');
                const levelSelect = newField.querySelector('select');
                
                formData.append('language_name', languageInput ? languageInput.value : ''); // name yerine language_name
                formData.append('level', levelSelect ? levelSelect.value : '');
                
                endpoint = '{{ route("user.language.store") }}';
            }
            
            // Loading göster
            this.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">refresh</span>';
            this.disabled = true;
            
            fetch(endpoint, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Alanın üstüne bildirim alanı ekle
                    const notificationDiv = document.createElement('div');
                    notificationDiv.className = 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3 mb-4';
                    notificationDiv.innerHTML = `
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 mr-2">check_circle</span>
                            <span class="text-green-800 dark:text-green-200 font-medium">${getSuccessMessage(fieldType)}</span>
                        </div>
                    `;
                    
                    // Bildirimi alanın üstüne ekle
                    newField.parentNode.insertBefore(notificationDiv, newField);
                    
                    // 3 saniye sonra bildirimi kaldır
                    setTimeout(() => {
                        notificationDiv.remove();
                    }, 3000);
                    
                    newField.setAttribute('data-is-new', 'false');
                    
                    // Kaydet ve İptal butonlarını gizle
                    const buttonContainer = newField.querySelector('.flex.justify-between.items-center');
                    const leftButtons = buttonContainer.querySelector('.flex.gap-2');
                    
                    leftButtons.style.display = 'none';
                    buttonContainer.className = 'flex justify-end items-center';
                    
                    // Sil butonunu güncelle - backend'den gelen ID'yi ekle
                    const deleteBtn = newField.querySelector('.delete-new-btn');
                    
                    // Field type'a göre doğru class'ı ve data-id'yi ekle
                    let deleteClass = '';
                    switch(fieldType) {
                        case 'experience':
                            deleteClass = 'delete-experience';
                            break;
                        case 'education':
                            deleteClass = 'delete-education';
                            break;
                        case 'skill':
                            deleteClass = 'delete-ability';
                            break;
                        case 'language':
                            deleteClass = 'delete-language';
                            break;
                    }
                    
                    // Sil butonunu güncelle
                    deleteBtn.className = `text-red-500 hover:text-red-700 dark:hover:text-red-400 ${deleteClass}`;
                    deleteBtn.setAttribute('data-id', data.data.id); // Backend'den gelen ID
                    deleteBtn.classList.remove('delete-new-btn'); // Eski class'ı kaldır
                    
                    // Yeni event listener ekle - artık backend'den silme yapacak
                    deleteBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // SweetAlert ile onay al
                        const element = deleteBtn.closest('.p-4.border.rounded-lg');
                        let itemName = '';
                        
                        if (fieldType === 'experience') {
                            const position = element.querySelector('input[placeholder="Pozisyon"]').value;
                            const company = element.querySelector('input[placeholder="Şirket"]').value;
                            itemName = `${position} - ${company}`;
                        } else if (fieldType === 'education') {
                            const school = element.querySelector('input[placeholder="Okul"]').value;
                            const department = element.querySelector('input[placeholder="Bölüm"]').value;
                            itemName = `${school} - ${department}`;
                        } else if (fieldType === 'skill') {
                            itemName = element.querySelector('input[placeholder="Yetenek"]').value;
                        } else if (fieldType === 'language') {
                            itemName = element.querySelector('input[placeholder="Dil"]').value;
                        }
                        
                        confirmDelete(fieldType, data.data.id, itemName);
                    });
                    
                    // Alanı doğru container'a taşı
                    let targetContainer = '';
                    switch(fieldType) {
                        case 'experience':
                            targetContainer = document.querySelector('.new-field');
                            break;
                        case 'education':
                            targetContainer = document.getElementById('educationsContainer');
                            break;
                        case 'skill':
                            targetContainer = document.getElementById('skillsContainer');
                            break;
                        case 'language':
                            targetContainer = document.getElementById('languagesContainer');
                            break;
                    }
                    
                    if (targetContainer) {
                        // Alanı yeni container'a taşı
                        targetContainer.insertBefore(newField, targetContainer.firstChild);
                    }
                    
                } else {
                    // Hata durumu
                    if (data.errors) {
                        // Validation hatalarını işle
                        handleValidationErrors(data.errors, newField);
                    } else {
                        // Genel hata mesajı
                        showNotification(data.message || 'Kaydetme sırasında hata oluştu!', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Kaydetme sırasında hata oluştu!', 'error');
            })
            .finally(() => {
                this.innerHTML = '<span class="material-symbols-outlined text-sm">save</span> Kaydet';
                this.disabled = false;
            });
        });
        
        // İptal butonuna tıklama
        newField.querySelector('.cancel-btn').addEventListener('click', function() {
            newField.remove();
        });
        
        // Sil butonuna tıklama (yeni eklenen alanlar için - sadece kaydedilmeden önce)
        newField.querySelector('.delete-new-btn').addEventListener('click', function() {
            // Sadece henüz kaydedilmemiş alanlar için çalışsın
            if (newField.getAttribute('data-is-new') === 'true') {
                newField.remove();
            }
        });
    }

    // Eğitim ekleme
    document.getElementById('addEducation').addEventListener('click', function() {
        // Eğitimler bölümünün container'ını bul
        const educationsSection = document.getElementById('educationsContainer');
        
        if (educationsSection) {
            createNewFieldInContainer(educationsSection, 'education');
        }
        educationCounter++;
    });

    // Yetenek ekleme
    document.getElementById('addSkill').addEventListener('click', function() {
        // Yetenekler bölümünün container'ını bul - doğru seçici kullan
        const skillsSection = document.getElementById('skillsContainer');
        
        if (skillsSection) {
            createNewFieldInContainer(skillsSection, 'skill');
        }
        skillCounter++;
    });

    // Dil ekleme
    document.getElementById('addLanguage').addEventListener('click', function() {
        // Diller bölümünün container'ını bul - doğru seçici kullan
        const languagesSection = document.getElementById('languagesContainer');
        
        if (languagesSection) {
            createNewFieldInContainer(languagesSection, 'language');
        }
        languageCounter++;
    });

    // SweetAlert ile silme onayı fonksiyonu
    function confirmDelete(type, id, name) {
        Swal.fire({
            title: `${name} silmek istiyor musunuz?`,
            text: "Bu işlem geri alınamaz!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Evet, Sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteItem(type, id);
            }
        });
    }

    // Silme işlemi fonksiyonu
    function deleteItem(type, id) {
        let endpoint = '';
        let successMessage = '';
        
        switch(type) {
            case 'experience':
                endpoint = '{{ route("user.delete-experience") }}';
                successMessage = 'Deneyim başarıyla silindi!';
                break;
            case 'education':
                endpoint = '{{ route("user.delete-education") }}';
                successMessage = 'Eğitim başarıyla silindi!';
                break;
            case 'ability':
                endpoint = '{{ route("user.delete-ability") }}';
                successMessage = 'Yetenek başarıyla silindi!';
                break;
            case 'language':
                endpoint = '{{ route("user.delete-language") }}';
                successMessage = 'Dil başarıyla silindi!';
                break;
        }
        
        fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: id
            })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                Swal.fire('Silindi!', successMessage, 'success').then(() => {
                    location.reload();
                });
                    } else {
                Swal.fire('Hata!', data.message || 'Silme işlemi sırasında hata oluştu!', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
            Swal.fire('Hata!', 'Silme işlemi sırasında hata oluştu!', 'error');
        });
    }

    // Silme event listener'larını tek bir fonksiyonda birleştir
    document.addEventListener('click', function(event) {
        // Deneyim silme
        if (event.target.closest('.delete-experience')) {
            const deleteBtn = event.target.closest('.delete-experience');
            const experienceId = deleteBtn.dataset.id;
            const experienceElement = deleteBtn.closest('.p-4.border.rounded-lg');
            const position = experienceElement.querySelector('input[placeholder="Pozisyon"]').value;
            const company = experienceElement.querySelector('input[placeholder="Şirket"]').value;
            const experienceName = `${position} - ${company}`;
            
            confirmDelete('experience', experienceId, experienceName);
        }
        
        // Eğitim silme
        if (event.target.closest('.delete-education')) {
            const deleteBtn = event.target.closest('.delete-education');
            const educationId = deleteBtn.dataset.id;
            const educationElement = deleteBtn.closest('.p-4.border.rounded-lg');
            const school = educationElement.querySelector('input[placeholder="Okul"]').value;
            const department = educationElement.querySelector('input[placeholder="Bölüm"]').value;
            const educationName = `${school} - ${department}`;
            
            confirmDelete('education', educationId, educationName);
        }

    // Yetenek silme
        if (event.target.closest('.delete-ability')) {
            const deleteBtn = event.target.closest('.delete-ability');
            const abilityId = deleteBtn.dataset.id;
            const abilityElement = deleteBtn.closest('.p-4.border.rounded-lg');
            const abilityName = abilityElement.querySelector('input[placeholder="Yetenek"]').value;
            
            confirmDelete('ability', abilityId, abilityName);
        }
        
        // Dil silme
        if (event.target.closest('.delete-language')) {
            const deleteBtn = event.target.closest('.delete-language');
            const languageId = deleteBtn.dataset.id;
            const languageElement = deleteBtn.closest('.p-4.border.rounded-lg');
            const languageName = languageElement.querySelector('input[placeholder="Dil"]').value;
            
            confirmDelete('language', languageId, languageName);
        }
        
        // Yeni eklenen alanlar için sil butonu (sayfayı yenilemeden)
        if (event.target.closest('.delete-new-btn')) {
            const deleteBtn = event.target.closest('.delete-new-btn');
            const newField = deleteBtn.closest('[data-is-new]');
            
            if (newField) {
                newField.remove();
            }
        }
    });

    // Başarı mesajı fonksiyonu
    function getSuccessMessage(fieldType) {
        switch(fieldType) {
            case 'experience':
                return 'Deneyim başarıyla eklendi!';
            case 'education':
                return 'Eğitim başarıyla eklendi!';
            case 'skill':
                return 'Yetenek başarıyla eklendi!';
            case 'language':
                return 'Dil başarıyla eklendi!';
            default:
                return 'Başarıyla eklendi!';
        }
    }

    // Validation hatalarını işleme fonksiyonu
    function handleValidationErrors(errors, newField) {
        // Önceki hata mesajlarını temizle
        newField.querySelectorAll('.error-message').forEach(error => error.remove());
        newField.querySelectorAll('.border-red-500').forEach(input => {
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-300');
        });
        
        // Her hata için input alanını bul ve kırmızı yap
        Object.keys(errors).forEach(field => {
            let input = null;
            
            // Field name'e göre input'u bul
            switch(field) {
                case 'position':
                    input = newField.querySelector('input[placeholder="Pozisyon"]');
                    break;
                case 'company_name':
                    input = newField.querySelector('input[placeholder="Şirket"]');
                    break;
                case 'school_name':
                    input = newField.querySelector('input[placeholder="Okul"]');
                    break;
                case 'field_of_study':
                    input = newField.querySelector('input[placeholder="Bölüm"]');
                    break;
                case 'abilities_name':
                    input = newField.querySelector('input[placeholder="Yetenek"]');
                    break;
                case 'language_name':
                    input = newField.querySelector('input[placeholder="Dil"]');
                    break;
                case 'start_date':
                    input = newField.querySelector('input[type="month"]');
                    break;
                case 'end_date':
                    const dateInputs = newField.querySelectorAll('input[type="month"]');
                    input = dateInputs[1]; // İkinci tarih input'u
                    break;
            }
            
            if (input) {
                // Input'u kırmızı yap
                input.classList.remove('border-gray-300');
                input.classList.add('border-red-500');
                
                // Hata mesajını input'un altına ekle
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message text-red-500 text-sm mt-1';
                errorDiv.textContent = errors[field][0];
                
                // Input'un parent'ına hata mesajını ekle
                input.parentNode.appendChild(errorDiv);
            }
        });
    }

function generatePassword() {
    const password = Math.floor(100000 + Math.random() * 900000).toString();
    const passwordInput = document.querySelector('#customCertificateModal #password');
    if (passwordInput) {
        passwordInput.value = password;
    }
}

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="date"], input[type="month"], select, textarea').forEach(input => {
            input.addEventListener('focus', function() {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
                const errorMessage = this.nextElementSibling;
                if (errorMessage && errorMessage.classList.contains('error-message')) {
                    errorMessage.remove();
                }
            });
        });
    });

    // Input alanlarına focus event listener ekle
    function addInputFocusListeners(newField) {
        const inputs = newField.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                // Focus olduğunda kırmızı border'ı kaldır
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
                
                // Hata mesajını kaldır
                const errorMessage = this.parentNode.querySelector('.error-message');
                if (errorMessage) {
                    errorMessage.remove();
                }
            });
        });
    }

// update profile
document.getElementById('saveProfileBtn').addEventListener('click', function() {
    const phone = document.getElementById('phone').value;
    const email = document.getElementById('email').value;
    const contactInfo = document.getElementById('contactInfo').value;
    const tcNum = document.getElementById('tcNum').value;
    
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('gsm', phone);
    formData.append('email', email);
    formData.append('contact_info', contactInfo);
    formData.append('tc_num', tcNum);
    
    // Loading göster
    this.innerHTML = '<span class="material-symbols-outlined text-base animate-spin">refresh</span>';
    this.disabled = true;
    
    fetch('{{ route("user.update-profile") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Başarı durumunda butonun yanına "Güncellendi" yazısı ekle
            this.innerHTML = '<span class="material-symbols-outlined text-base">save</span> Güncellendi';
            this.classList.add('bg-green-500');
            this.classList.remove('bg-primary');
            
            // 2 saniye sonra eski haline döndür
            setTimeout(() => {
                this.innerHTML = '<span class="material-symbols-outlined text-base">save</span> Kaydet';
                this.classList.remove('bg-green-500');
                this.classList.add('bg-primary');
            }, 2000);
        } else {
            if (data.errors) {
                // Validation hatalarını göster
                Object.keys(data.errors).forEach(field => {
                    let inputId = '';
                    switch(field) {
                        case 'gsm':
                            inputId = 'phone';
                            break;
                        case 'email':
                            inputId = 'email';
                            break;
                        case 'contact_info':
                            inputId = 'contactInfo';
                            break;
                        case 'tc_num':
                            inputId = 'tcNum';
                            break;
                    }
                    
                    const input = document.getElementById(inputId);
                    if (input) {
                        input.classList.add('border-red-500');
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'text-red-500 text-sm mt-1';
                        errorDiv.textContent = data.errors[field][0];
                        input.parentNode.appendChild(errorDiv);
                    }
                });
            } else {
                // Hata durumunda butonun yanına "Hata" yazısı ekle
                this.innerHTML = '<span class="material-symbols-outlined text-base">error</span> Hata';
                this.classList.add('bg-red-500');
                this.classList.remove('bg-primary');
                
                // 2 saniye sonra eski haline döndür
                setTimeout(() => {
                    this.innerHTML = '<span class="material-symbols-outlined text-base">save</span> Kaydet';
                    this.classList.remove('bg-red-500');
                    this.classList.add('bg-primary');
                }, 2000);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Hata durumunda butonun yanına "Hata" yazısı ekle
        this.innerHTML = '<span class="material-symbols-outlined text-base">error</span> Hata';
        this.classList.add('bg-red-500');
        this.classList.remove('bg-primary');
        
        // 2 saniye sonra eski haline döndür
        setTimeout(() => {
            this.innerHTML = '<span class="material-symbols-outlined text-base">save</span> Kaydet';
            this.classList.remove('bg-red-500');
            this.classList.add('bg-primary');
        }, 2000);
    })
    .finally(() => {
        this.disabled = false;
    });
});

// phone input event listener
document.getElementById('phone').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9+\-\(\)\s]/g, '');
});

// phone keypress event listener
document.getElementById('phone').addEventListener('keypress', function(e) {
    const allowedKeys = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '+', '-', '(', ')', ' '];
    const specialKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'];
    
    if (specialKeys.includes(e.key)) {
        return;
    }
    
    if (!allowedKeys.includes(e.key)) {
        e.preventDefault();
    }
});

// TC numarası input event listener - sadece sayı girişi
const tcNumInput = document.getElementById('tcNum');
if (tcNumInput) {
    tcNumInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    tcNumInput.addEventListener('keypress', function(e) {
        const allowedKeys = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        const specialKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'];
        
        if (specialKeys.includes(e.key)) {
            return;
        }
        
        if (!allowedKeys.includes(e.key)) {
            e.preventDefault();
        }
    });
}

    // Custom Certificate modal and logic for standard user dashboard
    {
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

            fetch('{{ route("user.custom-certificate.store") }}', {
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
                    fetch('{{ route("user.custom-certificate.delete") }}', {
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

        fetch('{{ route("user.custom-certificate.parse") }}', {
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
            if (registerNoInput) registerNoInput.placeholder = "Taramadan sonra otomatik dolar veya kendiniz yazabilirsiniz";
            if (certNameInput) certNameInput.placeholder = "Örn: İleri Düzey Web Geliştirme";
            if (certInstitutionInput) certInstitutionInput.placeholder = "Örn: Udemy, Coursera, BilgeAdam";

            // Enable next step button when scanning finishes
            if (nextBtn) {
                nextBtn.disabled = false;
                nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }
}
</script>