@include('user.partials.header')

<main class="flex-1 p-4 lg:p-8">
    <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm">
        <div class="p-4 lg:p-6 border-b border-gray-200 dark:border-gray-800">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white">Eğitmen Kimlik Kartı Talep</h2>
                    <p class="mt-1 text-sm lg:text-base text-gray-600 dark:text-gray-400">Eğitmen kimlik kartı için başvuru formunu doldurun.</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <span class="font-semibold">Mevcut Talep Sayısı:</span> {{ $currentRequestCount }}
                    </p>
                    <p class="text-sm text-blue-800 dark:text-blue-200 mt-1">
                        <span class="font-semibold">Kalan Başvuru Hakkı:</span> {{ $remainingRequests ?? 0 }}
                    </p>
                    @if($nextRequestCount <= 3)
                        <p class="text-xs text-blue-600 dark:text-blue-300 mt-1">
                            Bu talep {{ $nextRequestCount }}. talebiniz olacak
                        </p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="p-4 lg:p-6">
            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400 mr-3">check_circle</span>
                        <span class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-start">
                        <span class="material-symbols-outlined text-red-600 dark:text-red-400 mr-3">error</span>
                        <div class="flex-1">
                            <h3 class="text-red-800 dark:text-red-200 font-medium mb-2">Lütfen aşağıdaki hataları düzeltin:</h3>
                            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @php
                $remainingRequests = $remainingRequests ?? 0;
                $canSubmit = $remainingRequests > 0;
            @endphp

            @if(!$canSubmit)
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-start">
                        <span class="material-symbols-outlined text-red-600 dark:text-red-400 mr-3">warning</span>
                        <div class="flex-1">
                            <h3 class="text-red-800 dark:text-red-200 font-medium mb-2">Başvuru Hakkınız Kalmadı</h3>
                            <p class="text-sm text-red-700 dark:text-red-300">
                                Maksimum başvuru hakkınızı (3 başvuru) kullandınız. Yeni başvuru yapmak için lütfen admin ile iletişime geçin.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('user.instructor-card-request.store') }}" method="POST" class="space-y-6" @if(!$canSubmit) onsubmit="return false;" @endif>
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Eğitmen Adı -->
                    <div>
                        <label for="instructor_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Eğitmen Adı Soyadı <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="instructor_name" 
                            name="instructor_name" 
                            value="{{ old('instructor_name', $user->name . ' ' . $user->surname) }}"
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm @error('instructor_name') border-red-500 @enderror"
                            placeholder="Eğitmen adı soyadı"
                        />
                        @error('instructor_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm @error('email') border-red-500 @enderror"
                            placeholder="ornek@email.com"
                        />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telefon -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Telefon Numarası <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="{{ old('phone', $user->gsm) }}"
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-800 dark:border-gray-600 dark:text-white sm:text-sm @error('phone') border-red-500 @enderror"
                            placeholder="05XX XXX XX XX"
                        />
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sertifikalar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Sertifikalar
                    </label>
                    @if($userCertificates->count() > 0)
                        <div class="space-y-3 p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                            @foreach($userCertificates as $userCertificate)
                                <div class="flex items-start">
                                    <input 
                                        type="checkbox" 
                                        id="certificate_{{ $userCertificate->id }}" 
                                        name="certificates[]" 
                                        value="{{ $userCertificate->id }}"
                                        {{ old('certificates') && in_array($userCertificate->id, old('certificates')) ? 'checked' : '' }}
                                        class="mt-1 h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                                    />
                                    <label for="certificate_{{ $userCertificate->id }}" class="ml-3 flex-1 cursor-pointer">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $userCertificate->certificate->certificate_name ?? 'Sertifika adı bulunamadı' }}
                                                </span>
                                                @if($userCertificate->certificate_code)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        Kod: {{ $userCertificate->certificate_code }}
                                                    </p>
                                                @endif
                                            </div>
                                            @if($userCertificate->achievement_score)
                                                <span class="text-xs font-medium text-primary">
                                                    {{ $userCertificate->achievement_score }} Puan
                                                </span>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Kimlik kartınıza eklemek istediğiniz sertifikaları seçin.
                        </p>
                    @else
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Henüz sertifikanız bulunmuyor. Sertifika eklemek için admin ile iletişime geçin.
                            </p>
                        </div>
                    @endif
                    @error('certificates')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    @error('certificates.*')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    @if($canSubmit)
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-medium flex items-center gap-2"
                        >
                            <span class="material-symbols-outlined text-sm">send</span>
                            Talep Gönder
                        </button>
                    @else
                        <button 
                            type="button" 
                            disabled
                            class="px-6 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium flex items-center gap-2"
                        >
                            <span class="material-symbols-outlined text-sm">block</span>
                            Başvuru Hakkı Yok
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</main>

@include('user.partials.footer')

