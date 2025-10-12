@include('admin.partials.header')

<!-- main content -->
<main class="flex-1 p-4 lg:p-8">
    <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white hidden lg:block">Öğrenciler</h2>
    <div class="mt-4 lg:mt-8">
        <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-3 lg:px-6 py-3" scope="col">
                                Öğrenci
                            </th>
                            <th class="px-3 lg:px-6 py-3 hidden sm:table-cell" scope="col">
                                Rozetler
                            </th>
                            <th class="px-3 lg:px-6 py-3 hidden md:table-cell" scope="col">
                                Toplam Puan
                            </th>
                            <th class="px-3 lg:px-6 py-3 hidden lg:table-cell" scope="col">
                                Çalışma Durumu
                            </th>
                            <th class="px-3 lg:px-6 py-3 hidden xl:table-cell" scope="col">
                                Güncelleme Tarihi
                            </th>
                            <th class="px-3 lg:px-6 py-3" scope="col">
                                <span class="sr-only">İşlemler</span>
                            </th>
                    <thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr class="bg-white border-b dark:bg-background-dark/80 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-background-dark/90">
                                <th class="flex items-center px-3 lg:px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white" scope="row">
                                    <img alt="{{ $student->name }} {{ $student->surname }} image" class="w-8 h-8 lg:w-10 lg:h-10 rounded-full" src="{{ $student->profile_photo_url ?? 'https://via.placeholder.com/40' }}"/>
                                    <div class="pl-3 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <div class="text-sm lg:text-base font-semibold">{{ $student->name }} {{ $student->surname }}</div>
                                            <!-- Mobil çalışma durumu - Adın yanında (her zaman görünür) -->
                                            @if($student->is_active)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-green-900 dark:text-green-300 lg:hidden">Çalışıyor</span>
                                            @else
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-red-900 dark:text-red-300 lg:hidden">Çalışmıyor</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 sm:hidden">{{ number_format($student->point) }} puan</div>
                                        <!-- Mobil rozetler -->
                                        <div class="flex items-center gap-2 mt-1 sm:hidden">
                                            @if($student->userBadges && $student->userBadges->count() > 0)
                                                @foreach($student->userBadges->take(2) as $userBadge)
                                                    @if($userBadge->badge)
                                                        <span class="material-symbols-outlined text-yellow-500 text-sm">star</span>
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="text-gray-400 text-xs">Rozet yok</span>
                                            @endif
                                        </div>
                                    </div>
                                </th>
                                <td class="px-3 lg:px-6 py-4 hidden sm:table-cell">
                                    <div class="flex items-center gap-2">
                                        @if($student->userBadges && $student->userBadges->count() > 0)
                                            @foreach($student->userBadges->take(3) as $userBadge)
                                                @if($userBadge->badge)
                                                    <span class="material-symbols-outlined text-yellow-500">star</span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="text-gray-400 text-xs">Rozet yok</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 lg:px-6 py-4 hidden md:table-cell">
                                    {{ number_format($student->point) }}
                                </td>
                                <td class="px-3 lg:px-6 py-4 hidden lg:table-cell">
                                    @if($student->is_active)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Çalışıyor</span>
                                    @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Çalışmıyor</span>
                                    @endif
                                </td>
                                <td class="px-3 lg:px-6 py-4 hidden xl:table-cell">
                                    {{ $student->updated_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-3 lg:px-6 py-4 text-right">
                                    <div class="relative">
                                        <button id="dropdown-button-{{ $student->id }}" class="text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary" onclick="toggleDropdown({{ $student->id }})">
                                            <span class="material-symbols-outlined">more_vert</span>
                                        </button>
                                        <div id="dropdown-menu-{{ $student->id }}" class="absolute right-0 mt-2 w-48 bg-white dark:bg-background-dark/90 rounded-md shadow-lg z-50 hidden border border-gray-200 dark:border-gray-700">
                                            <div class="py-1">
                                                <a href="{{ route('admin.students.edit', $student->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    <span class="material-symbols-outlined text-sm mr-2">edit</span>
                                                    Düzenle
                                                </a>
                                                <a href="#" class="flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                                    <span class="material-symbols-outlined text-sm mr-2">delete</span>
                                                    Sil
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

@include('admin.partials.footer')