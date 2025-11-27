@include('portal-company.partials.header')

<!-- main content -->
<main class="flex-1 p-4 lg:p-8">
    <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm">
        <!-- title and description -->
        <div class="p-4 lg:p-6 border-b border-gray-200 dark:border-gray-800">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white">Partner Firma Ol</h2>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">Firmamızla işbirliği yaparak öğrencilerimize daha iyi fırsatlar sunun.</p>
                </div>
                <a href="{{ route('portal-login') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-600 transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    Geri Dön
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4 lg:p-6">
            <!-- Banner Area -->
            <div id="banner-area" class="mb-6"></div>
            
            <!-- Contact Form -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8">
                <form id="partner-form" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_person" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ad Soyad</label>
                            <input type="text" id="contact_person" name="contact_person" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="Ad Soyad" required>
                        </div>
                        
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Doğum Tarihi</label>
                            <input type="date" id="birth_date" name="birth_date" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telefon</label>
                            <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="+90 555 123 45 67" required>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-posta</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="ornek@firma.com" required>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-300 dark:border-gray-600 pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Firma Bilgileri</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Firma Adı</label>
                            <input type="text" id="company_name" name="company_name" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="Firma adınızı girin" required>
                        </div>
                        
                        <div>
                            <label for="tax_office" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vergi Dairesi</label>
                            <input type="text" id="tax_office" name="tax_office" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="Vergi dairesi adı" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tax_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vergi Numarası</label>
                            <input type="text" id="tax_number" name="tax_number" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="Vergi numaranızı girin" required>
                        </div>
                        
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mesaj</label>
                        <textarea id="message" name="message" rows="5" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="İşbirliği hakkında detayları buraya yazabilirsiniz..."></textarea>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" id="submit-btn" class="bg-primary text-white py-2 px-4 rounded-lg font-medium text-sm hover:bg-primary/90 transition-colors">
                            Başvuru Gönder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@include('portal-company.partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('partner-form');
    const submitBtn = document.getElementById('submit-btn');
    const bannerArea = document.getElementById('banner-area');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Gönderiliyor...';
        
        const formData = new FormData(form);
        
        fetch('{{ route("portal.partner-company.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessBanner(data.message);
                form.reset();
            } else {
                showErrorBanner('Hata: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorBanner('Bir hata oluştu. Lütfen tekrar deneyin.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Başvuru Gönder';
        });
    });
    
    function showSuccessBanner(message) {
        bannerArea.innerHTML = '';
        
        const banner = document.createElement('div');
        banner.className = 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6';
        banner.innerHTML = `
            <div class="text-sm text-green-700 dark:text-green-300">
                ${message}
            </div>
        `;
        
        bannerArea.appendChild(banner);
        
        setTimeout(() => {
            if (banner.parentElement) {
                banner.remove();
            }
        }, 4000);
    }
    
    function showErrorBanner(message) {
        bannerArea.innerHTML = '';
        
        const banner = document.createElement('div');
        banner.className = 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6';
        banner.innerHTML = `
            <div class="text-sm text-red-700 dark:text-red-300">
                ${message}
            </div>
        `;
        
        bannerArea.appendChild(banner);
        
        setTimeout(() => {
            if (banner.parentElement) {
                banner.remove();
            }
        }, 4000);
    }
});
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
