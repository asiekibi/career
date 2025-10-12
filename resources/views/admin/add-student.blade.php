<!-- resources/views/admin/add-student.blade.php -->
@include('admin.partials.header')

<!-- main content -->
<main class="flex-1 p-8">
    <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
        {{ isset($user) ? 'Öğrenci Düzenle' : 'Öğrenci Ekle' }}
    </h2>
    
    <!-- form -->
    <div class="mt-8 bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-6">
        <form action="{{ isset($user) ? route('admin.students.update', $user->id) : route('admin.students.store') }}" method="POST">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif
            
            <!-- student information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- full name -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="full-name">Ad Soyad</label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('full_name') border-red-500 @enderror" 
                           id="full-name" 
                           name="full_name" 
                           placeholder="Ali Yılmaz" 
                           value="{{ old('full_name', isset($user) ? $user->name . ' ' . $user->surname : '') }}" 
                           required="" 
                           type="text"/>
                    @error('full_name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- birth date -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="birth_date">Doğum Tarihi</label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('birth_date') border-red-500 @enderror" 
                           id="birth_date" 
                           name="birth_date" 
                           value="{{ old('birth_date', isset($user) && $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}" 
                           required="" 
                           type="date"/>
                    @error('birth_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- gsm -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="gsm">GSM</label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('gsm') border-red-500 @enderror" 
                           id="gsm" 
                           name="gsm" 
                           placeholder="+90 555 123 45 67" 
                           value="{{ old('gsm', isset($user) ? $user->gsm : '') }}" 
                           required="" 
                           type="tel"
                           pattern="[0-9+\s\-\(\)]+"
                           onkeypress="return isPhoneNumber(event)"/>
                    @error('gsm')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- email -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="email">Email</label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('email') border-red-500 @enderror" 
                           id="email" 
                           name="email" 
                           placeholder="ali.yilmaz@example.com" 
                           value="{{ old('email', isset($user) ? $user->email : '') }}" 
                           required="" 
                           type="email"/>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- city select -->
                <div class="mb-4">
                    <label for="city-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        İl Seçin
                    </label>
                    <select id="city-select" name="city_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-800 dark:text-white @error('city_id') border-red-500 @enderror" required>
                        <option value="">İl Seçin</option>
                    </select>
                    @error('city_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- district select -->
                <div class="mb-4">
                    <label for="district-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        İlçe Seçin
                    </label>
                    <select id="district-select" name="district_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-800 dark:text-white @error('district_id') border-red-500 @enderror" required>
                        <option value="">İlçe Seçin</option>
                    </select>
                    @error('district_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- contact info -->
            <div class="mt-6">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="contact_info">İletişim İzni</label>
                <select id="contact_info" name="contact_info" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-800 dark:text-white @error('contact_info') border-red-500 @enderror" required>
                    <option value="1" {{ old('contact_info', isset($user) ? $user->contact_info : '1') == '1' ? 'selected' : '' }}>Açık</option>
                    <option value="0" {{ old('contact_info', isset($user) ? $user->contact_info : '1') == '0' ? 'selected' : '' }}>Kapalı</option>
                </select>
                @error('contact_info')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- submit buttons -->
            <div class="mt-6 flex justify-end gap-4">
                <button class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:outline-none focus:ring-primary/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary dark:hover:bg-primary/90 dark:focus:ring-primary/80" type="submit">
                    {{ isset($user) ? 'Güncelle' : 'Kaydet' }}
                </button>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    İptal
                </a>
            </div>
        </form>
    </div>
</main>

@include('admin.partials.footer')

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
// GSM alanı için özel validasyon
function isPhoneNumber(event) {
    const char = String.fromCharCode(event.which);
    const allowedChars = /[0-9+\s\-\(\)]/;
    
    if (!allowedChars.test(char)) {
        event.preventDefault();
        return false;
    }
    return true;
}

// Input event listener ile gerçek zamanlı kontrol
document.addEventListener('DOMContentLoaded', function() {
    const gsmInput = document.getElementById('gsm');
    
    gsmInput.addEventListener('input', function(e) {
        // Sadece sayı, +, -, (, ), boşluk karakterlerine izin ver
        this.value = this.value.replace(/[^0-9+\s\-\(\)]/g, '');
    });
    
    gsmInput.addEventListener('keypress', function(e) {
        // Enter tuşu hariç diğer özel karakterleri engelle
        if (e.which === 13) return true; // Enter tuşu
        if (e.which === 8) return true;  // Backspace
        if (e.which === 9) return true;   // Tab
        if (e.which === 46) return true; // Delete
        
        const char = String.fromCharCode(e.which);
        const allowedChars = /[0-9+\s\-\(\)]/;
        
        if (!allowedChars.test(char)) {
            e.preventDefault();
            return false;
        }
    });
    
    const citySelect = document.getElementById('city-select');
    const districtSelect = document.getElementById('district-select');
    
    // upload cities
    loadCities();
    
    // when city changes, load districts
    citySelect.addEventListener('change', function() {
        const cityId = this.value;
        if (cityId) {
            loadDistricts(cityId);
        } else {
            districtSelect.innerHTML = '<option value="">İlçe Seçin</option>';
        }
    });
    
    // load cities
    function loadCities() {
        fetch('/admin/cities')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    citySelect.innerHTML = '<option value="">İl Seçin</option>';
                    data.data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.city_id;
                        option.textContent = city.location;
                        citySelect.appendChild(option);
                    });
                    
                    // Edit modunda seçili değerleri yükle
                    @if(isset($user) && $user->location)
                        // Kullanıcının location_id'sinden city_id'yi bul
                        const userLocationId = '{{ $user->location_id }}';
                        const userCityId = '{{ $user->location ? $user->location->city_id : "" }}';
                        
                        if (userCityId) {
                            citySelect.value = userCityId;
                            loadDistricts(userCityId);
                        }
                    @endif
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    // load districts
    function loadDistricts(cityId) {
        fetch(`/admin/districts?city_id=${cityId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    districtSelect.innerHTML = '<option value="">İlçe Seçin</option>';
                    data.data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.location;
                        districtSelect.appendChild(option);
                    });
                    
                    // Edit modunda seçili ilçeyi yükle
                    @if(isset($user) && $user->location_id)
                        setTimeout(() => {
                            districtSelect.value = '{{ $user->location_id }}';
                        }, 100);
                    @endif
                }
            })
            .catch(error => console.error('Error:', error));
    }

});
</script>