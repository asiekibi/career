@include('user.partials.header')

<main class="flex-1 p-8">
    <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-800">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Cv</h2>
            </div>
        </div>
        <div class="p-6">
            <div class="flex items-start gap-6">
                @if($student->profile_photo_url)
                    <img alt="Profil Resmi" class="w-32 h-32 rounded-full object-cover" src="{{ asset($student->profile_photo_url) }}"/>
                @else
                    <div class="w-32 h-32 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-gray-500 text-6xl">person</span>
                    </div>
                @endif
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $student->name }} {{ $student->surname }}</h3>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                            <span class="material-symbols-outlined mr-2">cake</span>
                            <span>{{ $student->birth_date ? $student->birth_date->format('d.m.Y') : 'Belirtilmemiş' }}</span>
                        </div>
                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                            <span class="material-symbols-outlined mr-2">phone</span>
                            <span>
                                @if($student->contact_info)
                                    {{ $student->gsm ?? 'Belirtilmemiş' }}
                                @else
                                    *
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                            <span class="material-symbols-outlined mr-2">email</span>
                            <span>
                                @if($student->contact_info)
                                    {{ $student->email }}
                                @else
                                    *
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                            <span class="material-symbols-outlined mr-2">star</span>
                            <span>
                                {{ ($student->userBadges->sum('badge.point') + $student->userCertificates->sum('achievement_score')) }} Puan
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($student->cvs->count() > 0)
                @php $cv = $student->cvs->first(); @endphp
                
                @if($cv->experiences->count() > 0)
                    <div class="mt-10">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Deneyimler</h4>
                        <div class="space-y-6">
                            @foreach($cv->experiences as $experience)
                                <div class="pl-4 border-l-2 border-primary">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $experience->start_date ? \Carbon\Carbon::parse($experience->start_date)->format('m.Y') : 'Belirtilmemiş' }} - 
                                        {{ $experience->end_date ? \Carbon\Carbon::parse($experience->end_date)->format('m.Y') : 'Halen' }}
                                    </p>
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200">{{ $experience->company_name }} - {{ $experience->position }}</h5>
                                    <p class="mt-1 text-gray-600 dark:text-gray-300">{{ $experience->description ?? 'Açıklama yok' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($cv->educations->count() > 0)
                    <div class="mt-10">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Eğitimler</h4>
                        <div class="space-y-6">
                            @foreach($cv->educations as $education)
                                <div class="pl-4 border-l-2 border-primary">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $education->start_date ? \Carbon\Carbon::parse($education->start_date)->format('m.Y') : 'Belirtilmemiş' }} - 
                                        {{ $education->end_date ? \Carbon\Carbon::parse($education->end_date)->format('m.Y') : 'Halen' }}
                                    </p>
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200">{{ $education->school_name }} - {{ $education->field_of_study }}</h5>
                                    @if($education->degree)
                                        <p class="mt-1 text-gray-600 dark:text-gray-300">Derece: {{ $education->degree }}</p>
                                    @endif
                                    @if($education->description)
                                        <p class="mt-1 text-gray-600 dark:text-gray-300">{{ $education->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($cv->abilities->count() > 0 || $cv->languages->count() > 0)
                    <div class="mt-10">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Yetenekler -->
                            @if($cv->abilities->count() > 0)
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Yetenekler</h4>
                                    <div class="space-y-4">
                                        @foreach($cv->abilities as $ability)
                                            <div>
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-base font-medium text-gray-700 dark:text-gray-300">{{ $ability->abilities_name }}</span>
                                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
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
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                                    @php
                                                        $width = match($ability->level) {
                                                            'beginner' => '25%',
                                                            'intermediate' => '50%',
                                                            'advanced' => '75%',
                                                            'expert' => '100%',
                                                            default => '50%'
                                                        };
                                                    @endphp
                                                    <div class="bg-primary h-2.5 rounded-full" style="width: {{ $width }}"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Diller -->
                            @if($cv->languages->count() > 0)
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Diller</h4>
                                    <div class="space-y-4">
                                        @foreach($cv->languages as $language)
                                            <div>
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-base font-medium text-gray-700 dark:text-gray-300">{{ $language->language_name }}</span>
                                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
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
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                                    @php
                                                        $width = match($language->level) {
                                                            'basic' => '25%',
                                                            'conversational' => '50%',
                                                            'fluent' => '75%',
                                                            'native' => '100%',
                                                            default => '50%'
                                                        };
                                                    @endphp
                                                    <div class="bg-primary h-2.5 rounded-full" style="width: {{ $width }}"></div>
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
                <div class="mt-10">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Kazanılan Rozetler</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        @foreach($student->userBadges as $userBadge)
                            <div class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800/50 text-center">
                                @if($userBadge->badge && $userBadge->badge->badge_icon_url)
                                    <img alt="{{ $userBadge->badge->badge_name }}" 
                                         class="w-12 h-12 rounded-full object-cover" 
                                         src="{{ asset($userBadge->badge->badge_icon_url) }}" 
                                         title="{{ $userBadge->badge->badge_name }}"/>
                                @else
                                    <span class="material-symbols-outlined text-5xl text-yellow-500">military_tech</span>
                                @endif
                                <h5 class="mt-2 font-semibold text-gray-800 dark:text-gray-200 text-sm">{{ $userBadge->badge->badge_name }}</h5>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($student->userCertificates->count() > 0)
                <div class="mt-10">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Sertifikalar</h4>
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
                                @foreach($student->userCertificates as $userCertificate)
                                    @php
                                        $certificate = $userCertificate->certificate;
                                        $courses = $certificate ? $certificate->certificateEducations : collect();
                                        $certificateLessons = $userCertificate->certificateLessons ?? collect();
                                        
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
</main>
@include('user.partials.footer')