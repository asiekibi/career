@include('admin.partials.header')

<!-- main content -->
<main class="flex-1 p-8">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $user->name }} {{ $user->surname }} için Sertifika Ata</h2>
        <a class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors" href="{{ route('admin.cvs') }}">
            <span class="material-symbols-outlined">
                arrow_back
            </span>
            Geri Dön
        </a>
    </div>
    
    <!-- success message -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    <!-- error message -->
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- new certificate information -->
    <div class="mt-8 bg-white dark:bg-background-dark/50 p-6 rounded-lg shadow-sm">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Yeni Sertifika Bilgileri</h3>
        <form action="{{ route('admin.students.assign-certificate.store', $user->id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="certificate_id">Sertifika Seç</label>
                    <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" id="certificate_id" name="certificate_id" required>
                        <option value="">Sertifika seçiniz...</option>
                        @foreach($certificates as $certificate)
                            <option value="{{ $certificate->id }}">
                                {{ $certificate->certificate_name }} - 
                                @if($certificate->type == 'kurs')
                                    Klişesiz Sertifika
                                @else
                                    Klişeli Sertifika
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="certificate_code">Sertifika Kodu</label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('certificate_code') border-red-500 @enderror" 
                           id="certificate_code" 
                           name="certificate_code" 
                           placeholder="örn: WG-12345" 
                           type="text"/>
                    @error('certificate_code')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="score">Başarı Puanı</label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" 
                           id="score" 
                           name="score" 
                           placeholder="örn: 95" 
                           type="number" 
                           min="0"/>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="issuer">Veren Kurum</label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" id="issuer" name="issuer" placeholder="örn: Teknoloji Akademisi" type="text"/>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="issue_date">Veriliş Tarihi</label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" 
                           id="issue_date" 
                           name="issue_date" 
                           placeholder="gg.aa.yyyy"
                           type="text"/>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="validity_period">Geçerlilik Süresi (Yıl)</label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" id="validity_period" name="validity_period" placeholder="örn: 2" type="number" min="1" max="10"/>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="register_no">
                        <span class="inline-block mr-2">Register No:</span>
                    </label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" 
                           id="register_no" 
                           name="register_no"
                           placeholder="Numara girin" 
                           type="text"/>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="password">
                        <span class="inline-block mr-2">Şifre:</span>
                    </label>
                    <div class="flex gap-2">
                        <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" 
                               id="password" 
                               name="password"
                               placeholder="6 haneli şifre" 
                               type="text"
                               maxlength="6"
                               readonly/>
                        <button type="button" 
                                onclick="generatePassword()"
                                class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:outline-none focus:ring-primary/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary dark:hover:bg-primary/90 dark:focus:ring-primary/80 whitespace-nowrap">
                            Şifre Üret
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="content1">İçerik 1</label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" 
                        id="content1" 
                        name="content1"
                        placeholder="İçerik 1 yazın"
                        type="text"/>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="content2">İçerik 2</label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" 
                        id="content2" 
                        name="content2"
                        placeholder="İçerik 2 yazın"
                        type="text"/>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:outline-none focus:ring-primary/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary dark:hover:bg-primary/90 dark:focus:ring-primary/80" type="submit">Kaydet</button>
            </div>
        </form>
    </div>
    
    <!-- assigned certificates -->
    <div class="mt-12">
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Atanmış Sertifikalar</h3>
        
        <!-- Desktop Table View -->
        <div class="hidden lg:block bg-white dark:bg-background-dark/50 rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3" scope="col">Sertifika Adı</th>
                            <th class="px-4 py-3" scope="col">Sertifika Kodu</th>
                            <th class="px-4 py-3" scope="col">Register No</th>
                            <th class="px-4 py-3" scope="col">Şifre</th>
                            <th class="px-4 py-3" scope="col">İçerik 1</th>
                            <th class="px-4 py-3" scope="col">İçerik 2</th>
                            <th class="px-4 py-3" scope="col">Veren Kurum</th>
                            <th class="px-4 py-3" scope="col">Veriliş Tarihi</th>
                            <th class="px-4 py-3" scope="col">Geçerlilik Sonu</th>
                            <th class="px-4 py-3" scope="col">Puan</th>
                            <th class="px-4 py-3" scope="col"><span class="sr-only">İşlemler</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($userCertificates as $userCertificate)
                            @php
                                $certificate = $userCertificate->certificate;
                                $courses = $certificate ? $certificate->certificateEducations : collect();
                                $certificateLessons = $userCertificate->certificateLessons ?? collect();
                            @endphp
                            <tr class="bg-white border-b dark:bg-background-dark/80 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-background-dark/90">
                                <th class="px-4 py-4 font-medium text-gray-900 dark:text-white break-words max-w-xs" scope="row">
                                    {{ $userCertificate->certificate->certificate_name ?? 'Bilinmeyen Sertifika' }}
                                </th>
                                <td class="px-4 py-4 break-words">{{ $userCertificate->certificate_code ?? 'Belirtilmemiş' }}</td>
                                <td class="px-4 py-4">{{ $userCertificate->register_no ?? 'Belirtilmemiş' }}</td>
                                <td class="px-4 py-4">{{ $userCertificate->password ?? 'Belirtilmemiş' }}</td>
                                <td class="px-4 py-4 break-words max-w-xs">{{ $userCertificate->content1 ?? 'Belirtilmemiş' }}</td>
                                <td class="px-4 py-4 break-words max-w-xs">{{ $userCertificate->content2 ?? 'Belirtilmemiş' }}</td>
                                <td class="px-4 py-4 break-words max-w-xs">{{ $userCertificate->issuing_institution ?? 'Belirtilmemiş' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    {{ $userCertificate->acquisition_date ? \Carbon\Carbon::parse($userCertificate->acquisition_date)->format('d.m.Y') : 'Belirtilmemiş' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($userCertificate->acquisition_date && $userCertificate->validity_period)
                                        @php
                                            $validityPeriod = is_numeric($userCertificate->validity_period) ? (int)$userCertificate->validity_period : 0;
                                        @endphp
                                        {{ \Carbon\Carbon::parse($userCertificate->acquisition_date)->addYears($validityPeriod)->format('d.m.Y') }}
                                    @else
                                        Belirtilmemiş
                                    @endif
                                </td>
                                <td class="px-4 py-4">{{ $userCertificate->achievement_score ?? 'Belirtilmemiş' }}</td>
                                <td class="px-4 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.students.certificate.download', $userCertificate->id) }}" 
                                           target="_blank"
                                           class="text-primary dark:text-primary hover:text-primary/80 dark:hover:text-primary/80"
                                           title="Sertifikayı İndir">
                                            <span class="material-symbols-outlined">download</span>
                                        </a>
                                        <button type="button" 
                                                class="text-red-600 dark:text-red-500 hover:text-red-800 dark:hover:text-red-400"
                                                onclick="confirmDeleteCertificate({{ $userCertificate->id }}, '{{ $user->name }}', '{{ $user->surname }}')"
                                                title="Sertifikayı Sil">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </div>
                                    
                                    <!-- Gizli form -->
                                    <form id="delete-certificate-form-{{ $userCertificate->id }}" 
                                          action="{{ route('admin.students.remove-certificate') }}" 
                                          method="POST" 
                                          style="display: none;">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $userCertificate->id }}">
                                    </form>
                                </td>
                            </tr>
                            @if($courses->count() > 0)
                                @php
                                    $totalScore = (int)($userCertificate->achievement_score ?? 0);
                                    $courseCount = $courses->count();
                                    $scorePerCourse = $courseCount > 0 ? (int)($totalScore / $courseCount) : 0;
                                    $remainder = $courseCount > 0 ? $totalScore % $courseCount : 0;
                                    
                                    // certificate_lessons tablosundan puanları al
                                    $lessonScores = [];
                                    foreach ($certificateLessons as $lesson) {
                                        $lessonScores[$lesson->certificate_education_id] = $lesson->score;
                                    }
                                @endphp
                                <tr class="bg-gray-50 dark:bg-gray-800/50">
                                    <td colspan="11" class="px-4 py-4">
                                        <div class="ml-4">
                                            <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Dersler ve Puanları:</h5>
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
                        @empty
                            <tr>
                                <td colspan="11" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Henüz atanmış sertifika bulunmuyor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
            @forelse($userCertificates as $userCertificate)
                @php
                    $certificate = $userCertificate->certificate;
                    $courses = $certificate ? $certificate->certificateEducations : collect();
                    $certificateLessons = $userCertificate->certificateLessons ?? collect();
                @endphp
                <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                {{ $userCertificate->certificate->certificate_name ?? 'Bilinmeyen Sertifika' }}
                            </h4>
                        </div>
                        <div class="flex items-center gap-2 ml-2">
                            <a href="{{ route('admin.students.certificate.download', $userCertificate->id) }}" 
                               target="_blank"
                               class="text-primary dark:text-primary hover:text-primary/80 dark:hover:text-primary/80 p-2"
                               title="Sertifikayı İndir">
                                <span class="material-symbols-outlined text-xl">download</span>
                            </a>
                            <button type="button" 
                                    class="text-red-600 dark:text-red-500 hover:text-red-800 dark:hover:text-red-400 p-2"
                                    onclick="confirmDeleteCertificate({{ $userCertificate->id }}, '{{ $user->name }}', '{{ $user->surname }}')"
                                    title="Sertifikayı Sil">
                                <span class="material-symbols-outlined text-xl">delete</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Sertifika Kodu:</span>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $userCertificate->certificate_code ?? 'Belirtilmemiş' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Register No:</span>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $userCertificate->register_no ?? 'Belirtilmemiş' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Şifre:</span>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $userCertificate->password ?? 'Belirtilmemiş' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Puan:</span>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $userCertificate->achievement_score ?? 'Belirtilmemiş' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Veriliş Tarihi:</span>
                            <p class="text-gray-900 dark:text-white mt-1">
                                {{ $userCertificate->acquisition_date ? \Carbon\Carbon::parse($userCertificate->acquisition_date)->format('d.m.Y') : 'Belirtilmemiş' }}
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Geçerlilik Sonu:</span>
                            <p class="text-gray-900 dark:text-white mt-1">
                                @if($userCertificate->acquisition_date && $userCertificate->validity_period)
                                    @php
                                        $validityPeriod = is_numeric($userCertificate->validity_period) ? (int)$userCertificate->validity_period : 0;
                                    @endphp
                                    {{ \Carbon\Carbon::parse($userCertificate->acquisition_date)->addYears($validityPeriod)->format('d.m.Y') }}
                                @else
                                    Belirtilmemiş
                                @endif
                            </p>
                        </div>
                        @if($userCertificate->content1)
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 font-medium">İçerik 1:</span>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $userCertificate->content1 }}</p>
                        </div>
                        @endif
                        @if($userCertificate->content2)
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 font-medium">İçerik 2:</span>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $userCertificate->content2 }}</p>
                        </div>
                        @endif
                        @if($userCertificate->issuing_institution)
                        <div class="sm:col-span-2">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Veren Kurum:</span>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $userCertificate->issuing_institution }}</p>
                        </div>
                        @endif
                    </div>

                    @if($courses->count() > 0)
                        @php
                            $totalScore = (int)($userCertificate->achievement_score ?? 0);
                            $courseCount = $courses->count();
                            $scorePerCourse = $courseCount > 0 ? (int)($totalScore / $courseCount) : 0;
                            $remainder = $courseCount > 0 ? $totalScore % $courseCount : 0;
                            
                            // certificate_lessons tablosundan puanları al
                            $lessonScores = [];
                            foreach ($certificateLessons as $lesson) {
                                $lessonScores[$lesson->certificate_education_id] = $lesson->score;
                            }
                        @endphp
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Dersler ve Puanları:</h5>
                            <div class="space-y-2">
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
                                    <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 p-2 rounded">
                                        <span>{{ $course->course_name }}</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $courseScore }} Puan</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Gizli form -->
                    <form id="delete-certificate-form-mobile-{{ $userCertificate->id }}" 
                          action="{{ route('admin.students.remove-certificate') }}" 
                          method="POST" 
                          style="display: none;">
                        @csrf
                        <input type="hidden" name="id" value="{{ $userCertificate->id }}">
                    </form>
                </div>
            @empty
                <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-8 text-center text-gray-500 dark:text-gray-400">
                    Henüz atanmış sertifika bulunmuyor.
                </div>
            @endforelse
        </div>
    </div>
</main>

@include('admin.partials.footer')

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Flatpickr Turkish Locale -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/tr.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toastr configuration
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // Success message
    @if(session('success'))
        toastr.success('{{ session('success') }}', 'Başarılı!');
    @endif

    // Error message
    @if(session('error'))
        toastr.error('{{ session('error') }}', 'Hata!');
    @endif

    // Flatpickr date picker initialization - Türkçe format (gün.ay.yıl)
    const issueDateInput = document.getElementById('issue_date');
    let issueDatePicker = null;
    if (issueDateInput) {
        issueDatePicker = flatpickr(issueDateInput, {
            locale: "tr",
            dateFormat: "Y-m-d", // Form için YYYY-MM-DD formatı
            altInput: false, // Tek input kullan, altInput kullanma
            allowInput: true,
            placeholder: "gg.aa.yyyy",
            parseDate: (datestr, format) => {
                // Türkçe formatı parse et (gün.ay.yıl)
                const parts = datestr.split('.');
                if (parts.length === 3) {
                    const day = parseInt(parts[0], 10);
                    const month = parseInt(parts[1], 10) - 1; // JavaScript months are 0-indexed
                    const year = parseInt(parts[2], 10);
                    return new Date(year, month, day);
                }
                return null;
            },
            onChange: function(selectedDates, dateStr, instance) {
                // Seçilen tarihi gün.ay.yıl formatında göster
                if (selectedDates.length > 0) {
                    const date = selectedDates[0];
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    instance.input.value = `${day}.${month}.${year}`;
                }
            }
        });
    }

    // Form submit event listener
    const form = document.querySelector('form[action*="assign-certificate"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const certificateSelect = document.getElementById('certificate_id');
            
            if (!certificateSelect.value) {
                e.preventDefault();
                toastr.error('Lütfen bir sertifika seçiniz!', 'Ders Seçilmedi');
                return false;
            }

            // Form submit edilmeden önce tarih formatını düzelt
            if (issueDatePicker && issueDatePicker.selectedDates.length > 0) {
                // Seçilen tarihi YYYY-MM-DD formatına çevir
                const date = issueDatePicker.selectedDates[0];
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                issueDateInput.value = `${year}-${month}-${day}`;
            } else if (issueDateInput.value && issueDateInput.value.includes('.')) {
                // Eğer manuel olarak gün.ay.yıl formatında girildiyse
                const parts = issueDateInput.value.split('.');
                if (parts.length === 3) {
                    const day = parts[0].padStart(2, '0');
                    const month = parts[1].padStart(2, '0');
                    const year = parts[2];
                    issueDateInput.value = `${year}-${month}-${day}`;
                }
            }
        });
    }
});

// Generate 6-digit random password
function generatePassword() {
    const password = Math.floor(100000 + Math.random() * 900000).toString();
    document.getElementById('password').value = password;
}

// SweetAlert2 with certificate delete confirmation
function confirmDeleteCertificate(certificateId, userName, userSurname) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: `${userName} ${userSurname} kullanıcısının sertifikasını silmek istediğinizden emin misiniz?`,
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
                text: 'Sertifika siliniyor, lütfen bekleyin.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Form submit - hem desktop hem mobile için
            const desktopForm = document.getElementById('delete-certificate-form-' + certificateId);
            const mobileForm = document.getElementById('delete-certificate-form-mobile-' + certificateId);
            
            if (desktopForm) {
                desktopForm.submit();
            } else if (mobileForm) {
                mobileForm.submit();
            }
        }
    });
}
</script>