@include('admin.partials.header')

<!-- main content -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<main class="flex-1 p-8">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Öğrenciler</h2>
        <div class="flex items-center gap-4">
            <!-- Search input -->
            <div class="relative">
                <input type="text" 
                       id="student-search" 
                       placeholder="Öğrenci ara..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white">
                <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">search</span>
            </div>
            <!-- student add button -->
            <a href="{{ route('admin.students.create') }}" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined">
                    add
                </span>
                Öğrenci Ekle
            </a>
        </div>
    </div>
    <div class="mt-8">
        <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <!-- student list table -->
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3" scope="col">Öğrenci</th>
                            <th class="px-6 py-3" scope="col">GSM</th>
                            <th class="px-6 py-3" scope="col">Email</th>
                            <th class="px-6 py-3" scope="col">İletişim İzni</th>
                            <th class="px-6 py-3" scope="col">Puan</th>
                            <th class="px-6 py-3" scope="col"><span class="sr-only">İşlemler</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr class="bg-white border-b dark:bg-background-dark/80 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-background-dark/90">
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
                                <td class="px-6 py-4 text-right">
                                    <div class="relative">
                                        <button id="action-btn-{{ $student->id }}" class="text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary">
                                            <span class="material-symbols-outlined">
                                                more_vert
                                            </span>
                                        </button>
                                        
                                        <!-- Dropdown Menu -->
                                        <div id="dropdown-{{ $student->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 border border-gray-200 dark:border-gray-700">
                                            <div class="py-1">
                                                <a href="{{ route('admin.students.edit', $student->id) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    <span class="material-symbols-outlined text-sm">edit</span>
                                                    Düzenle
                                                </a>
                                                <button onclick="confirmDelete({{ $student->id }})" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                                    <span class="material-symbols-outlined text-sm">delete</span>
                                                    Sil
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Form (Hidden) -->
                                    <form id="delete-form-{{ $student->id }}" action="{{ route('admin.students.destroy', $student->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                                    </form>
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
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('student-search');
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
    
    // Event delegation for dropdown toggle
    document.addEventListener('click', function(event) {
        if (event.target.closest('[id^="action-btn-"]')) {
            const button = event.target.closest('[id^="action-btn-"]');
            const studentId = button.id.replace('action-btn-', '');
            
            // Close all dropdowns
            document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
            
            // Toggle current dropdown
            const dropdown = document.getElementById(`dropdown-${studentId}`);
            if (dropdown) {
                dropdown.classList.remove('hidden');
            }
        } else if (!event.target.closest('[id^="dropdown-"]')) {
            // Close all dropdowns if clicking outside
            document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
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