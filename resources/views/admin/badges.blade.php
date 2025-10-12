@include('admin.partials.header')
<style>
    .border-dashed {
        transition: all 0.3s ease;
    }
    
    .border-dashed:hover {
        border-color: #1173d4;
        background-color: rgba(17, 115, 212, 0.05);
    }
    
    #preview-img {
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    #preview-img:hover {
        border-color: #1173d4;
    }
    
    #remove-preview {
        transition: all 0.3s ease;
    }
    
    #remove-preview:hover {
        background-color: rgba(239, 68, 68, 0.1);
        padding: 2px 8px;
        border-radius: 4px;
    }
</style>
<main class="flex-1 p-8">
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

    <!-- new badge information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Yeni Rozet Ekle</h2>
            <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-6">
                <form action="{{ route('admin.badges.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="badge-icon">Rozet İkonu</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <!-- preview area -->
                                    <div id="image-preview" class="hidden mb-4">
                                        <img id="preview-img" class="w-20 h-20 rounded-full mx-auto object-cover border-2 border-gray-300" src="" alt="Önizleme">
                                        <button type="button" id="remove-preview" class="mt-2 text-sm text-red-500 hover:text-red-700">
                                            Kaldır
                                        </button>
                                    </div>
                                    
                                    <!-- default upload area -->
                                    <div id="upload-area">
                                        <span class="material-symbols-outlined text-4xl text-gray-400">cloud_upload</span>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                            <label class="relative cursor-pointer bg-white dark:bg-background-dark/50 rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary" for="badge-icon-upload">
                                                <span>Dosya yükle</span>
                                                <input class="sr-only" id="badge-icon-upload" name="badge_icon" type="file" accept="image/*"/>
                                            </label>
                                            <p class="pl-1">veya sürükleyip bırakın</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                    </div>
                                </div>
                            </div>
                            @error('badge_icon')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="badge-name">Rozet Adı</label>
                            <input class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary sm:text-sm dark:bg-background-dark/70 dark:text-white @error('badge_name') border-red-500 @enderror" 
                                   id="badge-name" 
                                   name="badge_name" 
                                   placeholder="Örn: Süper Öğrenci" 
                                   type="text"
                                   value="{{ old('badge_name') }}"
                                   required/>
                            @error('badge_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="badge-points">Rozet Puanı</label>
                            <input class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary sm:text-sm dark:bg-background-dark/70 dark:text-white @error('point') border-red-500 @enderror" 
                                   id="badge-points" 
                                   name="point" 
                                   placeholder="Örn: 100" 
                                   type="number"
                                   value="{{ old('point') }}"
                                   required/>
                            @error('point')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <button class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" type="submit">
                                Rozeti Ekle
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Eklenmiş Rozetler</h2>
            <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3" scope="col">Rozet</th>
                                <th class="px-6 py-3" scope="col">Puan</th>
                                <th class="px-6 py-3" scope="col">Oluşturulma Tarihi</th>
                                <th class="px-6 py-3" scope="col">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($badges as $badge)
                                <tr class="bg-white border-b dark:bg-background-dark/80 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-background-dark/90">
                                    <th class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white" scope="row">
                                        @if($badge->badge_icon_url)
                                            <img alt="Badge Icon" class="w-10 h-10 rounded-full" src="{{ asset($badge->badge_icon_url) }}"/>
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                <span class="material-symbols-outlined text-gray-500">emoji_events</span>
                                            </div>
                                        @endif
                                        <div class="pl-3">
                                            <div class="text-base font-semibold">{{ $badge->badge_name }}</div>
                                        </div>
                                    </th>
                                    <td class="px-6 py-4">{{ $badge->point }}</td>
                                    <td class="px-6 py-4">{{ $badge->created_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <button type="button" 
                                                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                                onclick="document.getElementById('delete-form-{{ $badge->id }}').submit();">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                        
                                        <!-- Gizli form -->
                                        <form id="delete-form-{{ $badge->id }}" 
                                              action="{{ route('admin.badges.destroy') }}" 
                                              method="POST" 
                                              style="display: none;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $badge->id }}">
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Henüz rozet eklenmemiş.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    // file select
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('badge-icon-upload');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        const uploadArea = document.getElementById('upload-area');
        const removePreviewBtn = document.getElementById('remove-preview');
        const dropZone = document.querySelector('.border-dashed');
        
        // file selected, show preview
        fileInput.addEventListener('change', function(e) {
            handleFileSelect(e.target.files[0]);
        });
        
        // remove preview button
        removePreviewBtn.addEventListener('click', function() {
            removePreview();
        });
        
        // file select
        function handleFileSelect(file) {
            if (file) {
                // file type check
                if (!file.type.startsWith('image/')) {
                    alert('Lütfen bir resim dosyası seçin.');
                    return;
                }
                
                // file size check (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert('Dosya boyutu 10MB\'dan küçük olmalıdır.');
                    return;
                }
                
                // FileReader with preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    uploadArea.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
        
        // remove preview
        function removePreview() {
            imagePreview.classList.add('hidden');
            uploadArea.classList.remove('hidden');
            fileInput.value = '';
            previewImg.src = '';
        }
        
        // drag and drop functionality
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropZone.classList.add('border-primary', 'bg-primary/5');
        });
        
        // drag leave
        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropZone.classList.remove('border-primary', 'bg-primary/5');
        });
        
        // drop
        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropZone.classList.remove('border-primary', 'bg-primary/5');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFileSelect(files[0]);
            }
        });
    });
</script>
@include('admin.partials.footer')