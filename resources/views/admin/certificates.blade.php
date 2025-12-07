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
                    <option value="ders">Ders</option>
                    <option value="kurs">Kurs</option>
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
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $certificate->certificate_name }}</h3>
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
</script>