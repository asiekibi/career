@include('admin.partials.header')

<!-- main content -->
<main class="flex-1 p-8">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">İlan Düzenle</h2>
        <a class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors" href="{{ route('admin.job-listings.index') }}">
            <span class="material-symbols-outlined">
                arrow_back
            </span>
            Geri Dön
        </a>
    </div>
    
    <!-- success message -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900/20 dark:border-green-500 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif
    
    <!-- error message -->
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded dark:bg-red-900/20 dark:border-red-500 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <!-- edit job listing form -->
    <div class="mt-8 bg-white dark:bg-background-dark/50 p-6 rounded-lg shadow-sm">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">İlan Bilgileri</h3>
        <form action="{{ route('admin.job-listings.update', $jobListing->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="job_title">İlan Başlığı <span class="text-red-500">*</span></label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('job_title') border-red-500 @enderror" 
                           id="job_title" 
                           name="job_title" 
                           placeholder="örn: Yazılım Geliştirici" 
                           type="text"
                           value="{{ old('job_title', $jobListing->job_title) }}"
                           required/>
                    @error('job_title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="job_description">İlan Açıklaması <span class="text-red-500">*</span></label>
                    <textarea class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('job_description') border-red-500 @enderror" 
                              id="job_description" 
                              name="job_description" 
                              rows="6"
                              placeholder="İlan detaylarını buraya yazın..."
                              required>{{ old('job_description', $jobListing->job_description) }}</textarea>
                    @error('job_description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="phone">Telefon Numarası <span class="text-red-500">*</span></label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('phone') border-red-500 @enderror" 
                           id="phone" 
                           name="phone" 
                           placeholder="örn: 0555 123 45 67" 
                           type="text"
                           value="{{ old('phone', $jobListing->phone) }}"
                           required/>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('admin.job-listings.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                    İptal
                </a>
                <button class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:outline-none focus:ring-primary/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary dark:hover:bg-primary/90 dark:focus:ring-primary/80" type="submit">
                    Güncelle
                </button>
            </div>
        </form>
    </div>
</main>

@include('admin.partials.footer')

