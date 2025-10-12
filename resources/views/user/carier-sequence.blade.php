@include('user.partials.header')

<!-- main content -->
<main class="flex-1 p-8">
    <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-800">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Kariyer Sıralaması</h2>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Öğrencilerin puanlarına göre liderlik tablosu.</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @if($allStudents->count() >= 3)
                    @foreach($allStudents->take(3) as $index => $student)
                        <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-md flex flex-col items-center text-center relative border-2 {{ $index === 0 ? 'border-primary/50' : 'border-gray-300 dark:border-gray-700' }}">
                            @if($index === 0)
                                <span class="material-symbols-outlined text-primary text-6xl absolute -top-4 -left-4 transform rotate-[-20deg]">military_tech</span>
                            @endif
                            @if($student->profile_photo_url)
                                <img alt="{{ $student->name }} {{ $student->surname }}" 
                                     class="w-24 h-24 rounded-full object-cover border-4 {{ $index === 0 ? 'border-primary' : 'border-gray-300 dark:border-gray-700' }} shadow-lg mb-4" 
                                     src="{{ $student->profile_photo_url }}"/>
                            @else
                                <div class="w-24 h-24 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center border-4 {{ $index === 0 ? 'border-primary' : 'border-gray-300 dark:border-gray-700' }} shadow-lg mb-4">
                                    <span class="material-symbols-outlined text-gray-500 text-3xl">person</span>
                                </div>
                            @endif
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">{{ $student->name }} {{ $student->surname }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-lg font-bold">
                                Puan: <span class="{{ $index === 0 ? 'text-primary' : 'text-gray-900 dark:text-white' }}">{{ $student->point ?? 0 }}</span>
                            </p>
                            <div class="flex items-center gap-2 mt-3">
                                @forelse($student->userBadges as $userBadge)
                                    @if($userBadge->badge && $userBadge->badge->badge_icon_url)
                                        <img alt="{{ $userBadge->badge->badge_name }}" 
                                             class="w-6 h-6 rounded-full" 
                                             src="{{ asset($userBadge->badge->badge_icon_url) }}" 
                                             title="{{ $userBadge->badge->badge_name }}"/>
                                    @else
                                        <span class="material-symbols-outlined text-yellow-500">star</span>
                                    @endif
                                @empty
                                    <span class="text-sm text-gray-500">Rozet yok</span>
                                @endforelse
                            </div>
                            <button onclick="window.location.href='{{ route('user.cv.show', $student->id) }}'" class="mt-4 bg-primary/10 text-primary px-4 py-2 rounded-lg font-semibold text-sm hover:bg-primary/20 transition-colors">
                                Cv Göster
                            </button>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="md:col-span-3">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Diğer Öğrenciler</h3>
                <div class="overflow-x-auto max-h-96 overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-800">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3" scope="col">Sıra No</th>
                                <th class="px-6 py-3" scope="col">Ad Soyad</th>
                                <th class="px-6 py-3" scope="col">Rozetleri</th>
                                <th class="px-6 py-3" scope="col">Puan</th>
                                <th class="px-6 py-3" scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allStudents->skip(3) as $index => $student)
                                <tr class="bg-white border-b dark:bg-background-dark dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $loop->iteration + 3 }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex items-center gap-3">
                                            @if($student->profile_photo_url)
                                                <img alt="{{ $student->name }} {{ $student->surname }}" 
                                                     class="w-10 h-10 rounded-full object-cover" 
                                                     src="{{ $student->profile_photo_url }}"/>
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-gray-500 text-sm">person</span>
                                                </div>
                                            @endif
                                            <span>{{ $student->name }} {{ $student->surname }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            @forelse($student->userBadges as $userBadge)
                                                @if($userBadge->badge && $userBadge->badge->badge_icon_url)
                                                    <img alt="{{ $userBadge->badge->badge_name }}" 
                                                         class="w-6 h-6 rounded-full" 
                                                         src="{{ asset($userBadge->badge->badge_icon_url) }}" 
                                                         title="{{ $userBadge->badge->badge_name }}"/>
                                                @else
                                                    <span class="material-symbols-outlined text-yellow-500">star</span>
                                                @endif
                                            @empty
                                                <span class="text-sm text-gray-500">Rozet yok</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $student->point ?? 0 }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <button onclick="window.location.href='{{ route('user.cv.show', $student->id) }}'" class="bg-primary/10 text-primary px-4 py-2 rounded-lg font-semibold text-sm hover:bg-primary/20 transition-colors">
                                            Cv Göster
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@include('user.partials.footer')

