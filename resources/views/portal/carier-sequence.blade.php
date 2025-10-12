@include('portal.partials.header')

<!-- main content -->
<main class="flex-1 p-8">
    <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm">

        <!-- title and description -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Kariyer Sıralaması</h2>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">Öğrencilerin puanlarına göre liderlik tablosu.</p>
                </div>
                <!-- Search input -->
                <div class="relative">
                    <input type="text" 
                           id="portal-search" 
                           placeholder="Öğrenci ara..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">search</span>
                </div>
            </div>
        </div>

        <!-- table -->
        <div class="p-6">
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
                            <tr class="bg-white border-b dark:bg-background-dark dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
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
                                    @if($student->contact_info)
                                        {{ $student->gsm ?? 'Belirtilmemiş' }}
                                    @else
                                        *
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($student->contact_info)
                                        {{ $student->email }}
                                    @else
                                        *
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
    </div>
</main>

@include('portal.partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('portal-search');
    const tableRows = document.querySelectorAll('tbody tr');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        tableRows.forEach(row => {
            const studentName = row.querySelector('th').textContent.toLowerCase();
            
            if (studentName.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>