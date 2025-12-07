@include('admin.partials.header')

<!-- main content -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<main class="flex-1 p-4 lg:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white">Öğrenciler</h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
            <!-- Search input -->
            <div class="relative w-full sm:w-auto">
                <input type="text" 
                       id="student-search" 
                       placeholder="Öğrenci ara..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white">
                <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">search</span>
            </div>
            <!-- student add button -->
            <a href="{{ route('admin.students.create') }}" class="flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined">
                    add
                </span>
                <span class="hidden sm:inline">Öğrenci Ekle</span>
                <span class="sm:hidden">Ekle</span>
            </a>
        </div>
    </div>
    
    <div class="mt-6 lg:mt-8">
        <!-- Desktop Table (Hidden on Mobile) -->
        <div class="hidden lg:block bg-white dark:bg-background-dark/50 rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3" scope="col">Öğrenci</th>
                            <th class="px-6 py-3" scope="col">GSM</th>
                            <th class="px-6 py-3" scope="col">Email</th>
                            <th class="px-6 py-3" scope="col">İletişim İzni</th>
                            <th class="px-6 py-3" scope="col">Puan</th>
                            <th class="px-6 py-3 text-center" scope="col">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr class="bg-white border-b dark:bg-background-dark/80 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-background-dark/90" data-student-name="{{ $student->name }} {{ $student->surname }}">
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
                                <td class="px-6 py-4">{{ $student->gsm ?? 'Belirtilmemiş' }}</td>
                                <td class="px-6 py-4">{{ $student->email }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($student->contact_info)
                                            <div class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></div> Açık
                                        @else
                                            <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div> Kapalı
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-lg">{{ $student->point ?? 0 }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.students.edit', $student->id) }}" 
                                           class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" 
                                           title="Düzenle">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                        <button onclick="confirmDelete({{ $student->id }})" 
                                                class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" 
                                                title="Sil">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Henüz öğrenci bulunmuyor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards (Visible on Mobile) -->
        <div class="lg:hidden space-y-4">
            @forelse($students as $student)
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
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $student->email }}</div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.students.edit', $student->id) }}" 
                               class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" 
                               title="Düzenle">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <button onclick="confirmDelete({{ $student->id }})" 
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" 
                                    title="Sil">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Student Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Telefon:</span>
                            <span class="ml-2 text-gray-900 dark:text-white">{{ $student->gsm ?? 'Belirtilmemiş' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Puan:</span>
                            <span class="ml-2 font-semibold text-gray-900 dark:text-white">{{ $student->point ?? 0 }}</span>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="text-gray-500 dark:text-gray-400">İletişim İzni:</span>
                            <div class="inline-flex items-center ml-2">
                                @if($student->contact_info)
                                    <div class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></div> 
                                    <span class="text-green-600 dark:text-green-400">Açık</span>
                                @else
                                    <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div> 
                                    <span class="text-red-600 dark:text-red-400">Kapalı</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-8 text-center text-gray-500 dark:text-gray-400">
                    Henüz öğrenci bulunmuyor.
                </div>
            @endforelse
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('student-search');
        
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
        
        
        // Delete confirmation function
        window.confirmDelete = function(studentId) {
            Swal.fire({
                title: 'Emin misiniz?',
                text: "Bu öğrenciyi silmek istediğinizden emin misiniz?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Evet, Sil!',
                cancelButtonText: 'İptal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // POST request with ID in payload
                    fetch(`{{ route('admin.students.destroy') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            student_id: studentId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Silindi!', 'Öğrenci başarıyla silindi.', 'success');
                            location.reload();
                        } else {
                            Swal.fire('Hata!', data.message || 'Silme işlemi başarısız.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Hata!', 'Silme işlemi başarısız.', 'error');
                    });
                }
            });
        }
    });
    </script>
@include('admin.partials.footer')