@include('portal-company.partials.header')

<!-- main content -->
<main class="flex-1 p-4 lg:p-8">
    <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm">
        <!-- title and description -->
        <div class="p-4 lg:p-6 border-b border-gray-200 dark:border-gray-800">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white">Kariyer Sıralaması</h2>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">Öğrencilerin puanlarına göre liderlik tablosu.</p>
                </div>
                <!-- Search input -->
                <div class="relative w-full sm:w-auto">
                    <input type="text" 
                           id="portal-search" 
                           placeholder="Öğrenci ara..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">search</span>
                </div>
            </div>
        </div>

        <!-- Desktop Table (Hidden on Mobile) -->
        <div class="hidden lg:block p-4 lg:p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3" scope="col">Sıra</th>
                            <th class="px-6 py-3" scope="col">Öğrenci</th>
                            <th class="px-6 py-3" scope="col">GSM</th>
                            <th class="px-6 py-3" scope="col">Email</th>
                            <th class="px-6 py-3" scope="col">Puan</th>
                            <th class="px-6 py-3" scope="col">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                            <tr class="bg-white border-b dark:bg-background-dark dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50" data-student-name="{{ $student->name }} {{ $student->surname }}">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                                <th class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white" scope="row">
                                    @if($student->profile_photo_url)
                                        <img alt="{{ $student->name }} {{ $student->surname }}" class="w-10 h-10 rounded-full" src="{{ asset($student->profile_photo_url) }}"/>
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-gray-500">person</span>
                                        </div>
                                    @endif
                                    <div class="pl-3">
                                        <div class="text-base font-semibold">{{ $student->name }} {{ $student->surname }}</div>
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    @if(session('is_company_auth'))
                                        {{ $student->gsm ?? 'Belirtilmemiş' }}
                                    @else
                                        @if($student->contact_info)
                                            {{ $student->gsm ?? 'Belirtilmemiş' }}
                                        @else
                                            *
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if(session('is_company_auth'))
                                        {{ $student->email }}
                                    @else
                                        @if($student->contact_info)
                                            {{ $student->email }}
                                        @else
                                            *
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                    {{ $student->point ?? 0 }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('portal.student.cv', $student->id) }}" class="bg-primary/10 text-primary px-4 py-2 rounded-lg font-semibold text-sm hover:bg-primary/20 transition-colors">
                                        Cv Göster
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden p-4 space-y-4">
            @foreach($students as $index => $student)
                <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700" data-student-name="{{ $student->name }} {{ $student->surname }}">
                    <!-- Student Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            @if($student->profile_photo_url)
                                <img alt="{{ $student->name }} {{ $student->surname }}" class="w-12 h-12 rounded-full" src="{{ asset($student->profile_photo_url) }}"/>
                            @else
                                <div class="w-12 h-12 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-500">person</span>
                                </div>
                            @endif
                            <div class="ml-3">
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $student->name }} {{ $student->surname }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Puan: <span class="font-bold text-primary">{{ $student->point ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- CV Göster Butonu -->
                        <a href="{{ route('portal.student.cv', $student->id) }}" class="bg-primary/10 text-primary px-3 py-2 rounded-lg font-semibold text-sm hover:bg-primary/20 transition-colors">
                            CV Göster
                        </a>
                    </div>
                    
                    <!-- Student Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Telefon:</span>
                            <span class="ml-2 text-gray-900 dark:text-white">
                                @if(session('is_company_auth'))
                                    {{ $student->gsm ?? 'Belirtilmemiş' }}
                                @else
                                    @if($student->contact_info)
                                        {{ $student->gsm ?? 'Belirtilmemiş' }}
                                    @else
                                        *
                                    @endif
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Email:</span>
                            <span class="ml-2 text-gray-900 dark:text-white">
                                @if(session('is_company_auth'))
                                    {{ $student->email }}
                                @else
                                    @if($student->contact_info)
                                        {{ $student->email }}
                                    @else
                                        *
                                    @endif
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</main>

@include('portal-company.partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('portal-search');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        // Tüm öğrenci elementlerini bul (hem desktop hem mobile)
        const allStudentElements = document.querySelectorAll('[data-student-name]');
        
        allStudentElements.forEach(element => {
            const studentName = element.getAttribute('data-student-name').toLowerCase();
            if (studentName.includes(searchTerm)) {
                element.style.display = '';
            } else {
                element.style.display = 'none';
            }
        });
    });
});
</script>