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

                @if($user->userCertificates->count() > 0)
                    <div class="mt-8 lg:mt-10">
                        <h3 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white mb-4">Sertifikalar</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-6 py-3" scope="col">Sertifika Adı</th>
                                        <th class="px-6 py-3" scope="col">Kodu</th>
                                        <th class="px-6 py-3" scope="col">Derecesi</th>
                                        <th class="px-6 py-3" scope="col">Kurum</th>
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
                                                {{ $userCertificate->certificate->certificate_name ?? 'Sertifika adı bulunamadı' }}
                                            </th>
                                            <td class="px-6 py-4">{{ $userCertificate->certificate_code ?? 'Belirtilmemiş' }}</td>
                                            <td class="px-6 py-4">{{ $userCertificate->achievement_score ?? 'Belirtilmemiş' }}</td>
                                            <td class="px-6 py-4">{{ $userCertificate->issuing_institution ?? 'Belirtilmemiş' }}</td>
                                            <td class="px-6 py-4">
                                                <a href="{{ route('certificate.download', $userCertificate->id) }}" 
                                                   target="_blank"
                                                   class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 rounded-lg transition-colors"
                                                   title="Sertifikayı İndir">
                                                    <span class="material-symbols-outlined text-lg">download</span>
                                                    <span>İndir</span>
                                                </a>
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
                    </div>
                @endif
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
    const contactInfo = document.getElementById('contactInfo').value; // Yeni eklenen alan
    
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('gsm', phone);
    formData.append('email', email);
    formData.append('contact_info', contactInfo); // Yeni eklenen alan
    
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
</script>