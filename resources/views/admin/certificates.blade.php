@include('admin.partials.header')

<main class="flex-1 p-8">
    <!-- new certificate add -->
    <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-8">Eklenmiş Sertifikalar</h2>

    <!-- form -->
    <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-6 mb-8">
        <form action="{{ route('admin.certificates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="certificate-name">Sertifika Adı</label>
                <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" 
                    id="certificate-name" 
                    name="certificate_name"
                    placeholder="Örn: Pilates 101" 
                    required="" 
                    type="text"/>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="certificate-type">Sertifika Tipi</label>
                <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" 
                    id="certificate-type" 
                    name="type"
                    required>
                    <option value="">Sertifika tipi seçiniz...</option>
                    <option value="ders">Klişeli Sertifika</option>
                    <option value="kurs">Klişesiz Sertifika</option>
                </select>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="certificate-template">Sertifika Şablonu (PDF)</label>
                <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" 
                    id="certificate-template" 
                    name="template_file"
                    accept=".pdf"
                    type="file"/>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">PDF formatında sertifika şablonu yükleyin (HTML olarak kaydedilecektir)</p>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sertifika İçeriği (Dersler)</label>
                <div class="space-y-4" id="courses-container">
                    <div class="flex items-center gap-4">
                        <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" name="course[]" placeholder="Ders adı" type="text"/>
                        <button class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" type="button" onclick="removeCourse(this)" title="Dersi Sil">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                    <div class="flex items-center gap-4">
                        <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" name="course[]" placeholder="Ders adı" type="text"/>
                        <button class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" type="button" onclick="removeCourse(this)" title="Dersi Sil">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                </div>
                <button class="mt-4 flex items-center gap-2 text-sm font-medium text-primary hover:text-primary/80 transition-colors" id="add-course-btn" type="button">
                    <span class="material-symbols-outlined text-lg">
                        add_circle
                    </span>
                    <span>Yeni Ders Ekle</span>
                </button>
            </div>

            <div class="flex justify-end">
                <button class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:outline-none focus:ring-primary/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-primary/80" type="submit">Sertifikayı Kaydet</button>
            </div>
        </form>
    </div>

    <!-- added certificates -->
    <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-8">Eklenmiş Sertifikalar</h2>

    <!-- success message -->
    @if (session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
            <div class="text-sm text-green-700 dark:text-green-300">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- error message -->
    @if (session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
            <div class="text-sm text-red-700 dark:text-red-300">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- certificate list -->
    <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm">
        @forelse($certificates as $certificate)
            <div class="p-6 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                            {{ $certificate->certificate_name }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            @if($certificate->type == 'kurs')
                                Klişesiz Sertifika
                            @else
                                Klişeli Sertifika
                            @endif
                        </p>
                    </div>
                    <button type="button" 
                            onclick="editCertificate({{ $certificate->id }})"
                            class="text-primary hover:text-primary/80 dark:text-primary dark:hover:text-primary/80 flex items-center gap-1">
                        <span class="material-symbols-outlined text-lg">edit</span>
                        <span>Düzenle</span>
                    </button>
                </div>
                @if($certificate->template_path)
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium">Şablon:</span> 
                        <a href="{{ route('admin.certificates.download-template', $certificate->id) }}" class="text-primary hover:underline flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            PDF İndir
                        </a>
                    </div>
                @else
                    <div class="mt-2 text-sm text-gray-500 dark:text-gray-500 italic">
                        Şablon eklenmemiş
                    </div>
                @endif
                <ul class="mt-4 space-y-2 list-disc list-inside text-gray-600 dark:text-gray-300">
                    @foreach($certificate->certificateEducations as $education)
                        <li>{{ $education->course_name }}</li>
                    @endforeach
                </ul>
            </div>
        @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                Henüz sertifika eklenmemiş.
            </div>
        @endforelse
    </div>
</main>

<!-- Edit Certificate Modal -->
<div id="editCertificateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-background-dark/90">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Sertifikayı Düzenle</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <span class="material-symbols-outlined text-2xl">close</span>
            </button>
        </div>
        <form id="editCertificateForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="edit-certificate-name">Sertifika Adı</label>
                <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" 
                    id="edit-certificate-name" 
                    name="certificate_name"
                    placeholder="Örn: Pilates 101" 
                    required="" 
                    type="text"/>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="edit-certificate-type">Sertifika Tipi</label>
                <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" 
                    id="edit-certificate-type" 
                    name="type"
                    required>
                    <option value="">Sertifika tipi seçiniz...</option>
                    <option value="ders">Klişeli Sertifika</option>
                    <option value="kurs">Klişesiz Sertifika</option>
                </select>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="edit-certificate-template">Sertifika Şablonu (PDF)</label>
                <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" 
                    id="edit-certificate-template" 
                    name="template_file"
                    accept=".pdf"
                    type="file"/>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Yeni şablon yüklemek için PDF seçin (isteğe bağlı)</p>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sertifika İçeriği (Dersler)</label>
                <div class="space-y-4" id="edit-courses-container">
                    <!-- Courses will be added dynamically -->
                </div>
                <button type="button" class="mt-4 flex items-center gap-2 text-sm font-medium text-primary hover:text-primary/80 transition-colors" id="edit-add-course-btn">
                    <span class="material-symbols-outlined text-lg">
                        add_circle
                    </span>
                    <span>Yeni Ders Ekle</span>
                </button>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    İptal
                </button>
                <button type="submit" class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:outline-none focus:ring-primary/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary dark:hover:bg-primary/90 dark:focus:ring-primary/80">
                    Güncelle
                </button>
            </div>
        </form>
    </div>
</div>

@include('admin.partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const coursesContainer = document.getElementById('courses-container');
        const addCourseBtn = document.getElementById('add-course-btn');
        
        // add course function
        addCourseBtn.addEventListener('click', function() {
            const courseDiv = document.createElement('div');
            courseDiv.className = 'flex items-center gap-4';
            courseDiv.innerHTML = `
                <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" 
                       name="course[]" 
                       placeholder="Ders adı" 
                       type="text"/>
                <button class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" type="button" onclick="removeCourse(this)" title="Dersi Sil">
                    <span class="material-symbols-outlined text-lg">delete</span>
                </button>
            `;
            coursesContainer.appendChild(courseDiv);
        });
        
        // Form submit event listener
        const form = document.querySelector('form[action*="certificates.store"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Tüm course inputlarını al
                const courseInputs = document.querySelectorAll('input[name="course[]"]');
                
                // Boş olanları kaldır
                courseInputs.forEach(function(input) {
                    if (!input.value || input.value.trim() === '') {
                        // Boş input'u parent div'i ile birlikte kaldır
                        const courseDiv = input.closest('.flex.items-center.gap-4');
                        if (courseDiv) {
                            courseDiv.remove();
                        }
                    }
                });
                
                // Eğer hiç ders yoksa uyarı ver
                const remainingInputs = document.querySelectorAll('input[name="course[]"]');
                if (remainingInputs.length === 0) {
                    e.preventDefault();
                    alert('En az bir ders eklemelisiniz!');
                    return false;
                }
            });
        }
    });
    
    // remove course function
    function removeCourse(button) {
        const courseDiv = button.closest('.flex.items-center.gap-4');
        if (courseDiv) {
            courseDiv.remove();
        }
    }

    // Edit certificate function
    function editCertificate(id) {
        fetch(`/admin/certificates/${id}/edit`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Certificate data:', data);
                
                // Fill form with certificate data
                const nameInput = document.getElementById('edit-certificate-name');
                const typeSelect = document.getElementById('edit-certificate-type');
                const form = document.getElementById('editCertificateForm');
                
                if (nameInput) {
                    nameInput.value = data.certificate_name || '';
                }
                if (typeSelect) {
                    typeSelect.value = data.type || '';
                }
                if (form) {
                    form.action = `/admin/certificates/${id}`;
                }
                
                // Clear and populate courses
                const coursesContainer = document.getElementById('edit-courses-container');
                if (coursesContainer) {
                    coursesContainer.innerHTML = '';
                    
                    // Laravel JSON response'da ilişki camelCase olarak gelir
                    const educations = data.certificateEducations || data.certificate_educations || [];
                    console.log('Educations:', educations);
                    
                    if (educations && educations.length > 0) {
                        educations.forEach(function(education) {
                            addEditCourseInput(education.course_name || '');
                        });
                    } else {
                        addEditCourseInput('');
                    }
                }
                
                // Show modal
                const modal = document.getElementById('editCertificateModal');
                if (modal) {
                    modal.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error fetching certificate:', error);
                alert('Sertifika bilgileri yüklenirken bir hata oluştu: ' + error.message);
            });
    }

    // Add course input to edit form
    function addEditCourseInput(value = '') {
        const coursesContainer = document.getElementById('edit-courses-container');
        const courseDiv = document.createElement('div');
        courseDiv.className = 'flex items-center gap-4';
        
        // Escape HTML to prevent XSS
        const escapedValue = (value || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        
        courseDiv.innerHTML = `
            <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" 
                   name="course[]" 
                   placeholder="Ders adı" 
                   value="${escapedValue}"
                   type="text"/>
            <button class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" type="button" onclick="removeCourse(this)" title="Dersi Sil">
                <span class="material-symbols-outlined text-lg">delete</span>
            </button>
        `;
        coursesContainer.appendChild(courseDiv);
    }

    // Close edit modal
    function closeEditModal() {
        document.getElementById('editCertificateModal').classList.add('hidden');
    }

    // Add course button for edit form
    document.addEventListener('DOMContentLoaded', function() {
        const editAddCourseBtn = document.getElementById('edit-add-course-btn');
        if (editAddCourseBtn) {
            editAddCourseBtn.addEventListener('click', function() {
                addEditCourseInput('');
            });
        }

        // Edit form submit handler
        const editForm = document.getElementById('editCertificateForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                // Remove empty course inputs
                const courseInputs = document.querySelectorAll('#edit-courses-container input[name="course[]"]');
                courseInputs.forEach(function(input) {
                    if (!input.value || input.value.trim() === '') {
                        const courseDiv = input.closest('.flex.items-center.gap-4');
                        if (courseDiv) {
                            courseDiv.remove();
                        }
                    }
                });
            });
        }
    });
</script>