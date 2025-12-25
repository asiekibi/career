<!-- resources/views/admin/add-student.blade.php -->
@include('admin.partials.header')

<!-- main content -->
<main class="flex-1 p-8">
    <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
        {{ isset($user) ? 'Öğrenci Düzenle' : 'Öğrenci Ekle' }}
    </h2>
    
    <!-- success message -->
    @if (session('success'))
        <div class="mt-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="text-sm text-green-700 dark:text-green-300">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- error message -->
    @if (session('error'))
        <div class="mt-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="text-sm text-red-700 dark:text-red-300">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- warning message -->
    @if (session('warning'))
        <div class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <div class="text-sm text-yellow-700 dark:text-yellow-300">
                {{ session('warning') }}
            </div>
        </div>
    @endif
    
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
                           placeholder="gg.aa.yyyy"
                           required="" 
                           type="text"/>
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

                <!-- register number - sadece düzenlemede görünür -->
                @if(isset($user))
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="register_number">Kayıt Numarası</label>
                        <input class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 cursor-not-allowed" 
                            id="register_number" 
                            name="register_number" 
                            value="{{ old('register_number', $user->register_number ?? '') }}" 
                            readonly
                            type="text"/>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kayıt numarası otomatik oluşturulur ve değiştirilemez.</p>
                    </div>
                @endif

                <!-- TC Kimlik No -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="tc_num">TC Kimlik No</label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('tc_num') border-red-500 @enderror" 
                           id="tc_num" 
                           name="tc_num" 
                           placeholder="11 haneli TC kimlik numarası" 
                           value="{{ old('tc_num', isset($user) ? $user->tc_num : '') }}" 
                           type="text"
                           maxlength="11"
                           pattern="[0-9]{11}"/>
                    @error('tc_num')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- country, city, district - 3 columns in one row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <!-- country select -->
                <div>
                    <label for="country-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ülke Seçin
                    </label>
                    <select id="country-select" name="country_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-800 dark:text-white @error('country_id') border-red-500 @enderror" required>
                        <option value="">Ülke Seçin</option>
                    </select>
                    @error('country_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- city select -->
                <div>
                    <label for="city-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        İl Seçin
                    </label>
                    <select id="city-select" name="city_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-800 dark:text-white @error('city_id') border-red-500 @enderror">
                        <option value="">İl Seçin</option>
                    </select>
                    @error('city_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- district select -->
                <div>
                    <label for="district-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        İlçe Seçin
                    </label>
                    <select id="district-select" name="district_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-800 dark:text-white @error('district_id') border-red-500 @enderror">
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

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Flatpickr Turkish Locale -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/tr.js"></script>


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
        const tcNumInput = document.getElementById('tc_num');
        
        gsmInput.addEventListener('input', function(e) {
            // Sadece sayı, +, -, (, ), boşluk karakterlerine izin ver
            this.value = this.value.replace(/[^0-9+\s\-\(\)]/g, '');
        });
        
        // TC numarası için sadece sayı girişi
        if (tcNumInput) {
            tcNumInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
            
            tcNumInput.addEventListener('keypress', function(e) {
                const allowedKeys = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                const specialKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'];
                
                if (specialKeys.includes(e.key)) {
                    return;
                }
                
                if (!allowedKeys.includes(e.key)) {
                    e.preventDefault();
                }
            });
        }
    
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
    
    const countrySelect = document.getElementById('country-select');
    const citySelect = document.getElementById('city-select');
    const districtSelect = document.getElementById('district-select');
    
    // load countries
    loadCountries();
    
    // when country changes, load cities
    countrySelect.addEventListener('change', function() {
        const countryId = this.value;
        citySelect.innerHTML = '<option value="">İl Seçin</option>';
        districtSelect.innerHTML = '<option value="">İlçe Seçin</option>';
        if (countryId) {
            loadCitiesByCountry(countryId);
        }
    });
    
    // when city changes, load districts
    citySelect.addEventListener('change', function() {
        const cityId = this.value;
        if (cityId) {
            @if(isset($user) && $user->location_id)
            const selectedDistrictId = '{{ $user->location_id }}';
            loadDistricts(cityId, selectedDistrictId);
            @else
            loadDistricts(cityId);
            @endif
        } else {
            districtSelect.innerHTML = '<option value="">İlçe Seçin</option>';
        }
    });
    
    // load countries
    function loadCountries() {
        fetch('/admin/countries')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    countrySelect.innerHTML = '<option value="">Ülke Seçin</option>';
                    let turkeyId = null;
                    
                    data.data.forEach(country => {
                        const option = document.createElement('option');
                        option.value = country.id;
                        option.textContent = country.name;
                        countrySelect.appendChild(option);
                        
                        // Türkiye'yi bul
                        if (country.name.toLowerCase() === 'türkiye' || country.name.toLowerCase() === 'turkey' || country.name.toLowerCase() === 'turkiye') {
                            turkeyId = country.id;
                        }
                    });
                    
                    // Edit modunda seçili değerleri yükle
                    @if(isset($user) && $user->location)
                        const userLocation = @json($user->location);
                        const userCountryId = userLocation ? userLocation.country_id : null;
                        const userCityId = userLocation ? userLocation.city_id : null;
                        
                        if (userCountryId) {
                            countrySelect.value = userCountryId;
                            loadCitiesByCountry(userCountryId, userCityId);
                        } else if (turkeyId) {
                            // Eğer kullanıcının ülkesi yoksa Türkiye'yi seç
                            countrySelect.value = turkeyId;
                            loadCitiesByCountry(turkeyId);
                        }
                    @else
                        // Yeni öğrenci ekleme modunda Türkiye'yi varsayılan olarak seç
                        if (turkeyId) {
                            countrySelect.value = turkeyId;
                            loadCitiesByCountry(turkeyId);
                        }
                    @endif
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    // load cities by country
    function loadCitiesByCountry(countryId, selectedCityId = null) {
        fetch(`/admin/cities-by-country?country_id=${countryId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    citySelect.innerHTML = '<option value="">İl Seçin</option>';
                    data.data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.city_id;
                        option.textContent = city.location;
                        if (selectedCityId && city.city_id == selectedCityId) {
                            option.selected = true;
                            @if(isset($user) && $user->location_id)
                            const selectedDistrictId = '{{ $user->location_id }}';
                            loadDistricts(selectedCityId, selectedDistrictId);
                            @else
                            loadDistricts(selectedCityId);
                            @endif
                        }
                        citySelect.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    // load districts
    function loadDistricts(cityId, selectedDistrictId = null) {
        fetch(`/admin/districts?city_id=${cityId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    districtSelect.innerHTML = '<option value="">İlçe Seçin</option>';
                    data.data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.location;
                        if (selectedDistrictId && district.id == selectedDistrictId) {
                            option.selected = true;
                        }
                        districtSelect.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Flatpickr date picker initialization - Türkçe format (gün.ay.yıl)
    const birthDateInput = document.getElementById('birth_date');
    let birthDatePicker = null;
    if (birthDateInput) {
        birthDatePicker = flatpickr(birthDateInput, {
            locale: "tr",
            dateFormat: "Y-m-d", // Form için YYYY-MM-DD formatı
            altInput: false, // Tek input kullan, altInput kullanma
            allowInput: true,
            placeholder: "gg.aa.yyyy",
            parseDate: (datestr, format) => {
                // Türkçe formatı parse et (gün.ay.yıl)
                const parts = datestr.split('.');
                if (parts.length === 3) {
                    const day = parseInt(parts[0], 10);
                    const month = parseInt(parts[1], 10) - 1; // JavaScript months are 0-indexed
                    const year = parseInt(parts[2], 10);
                    return new Date(year, month, day);
                }
                return null;
            },
            onChange: function(selectedDates, dateStr, instance) {
                // Seçilen tarihi gün.ay.yıl formatında göster
                if (selectedDates.length > 0) {
                    const date = selectedDates[0];
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    instance.input.value = `${day}.${month}.${year}`;
                }
            }
        });
    }

    // Form submit edilmeden önce tarih formatını düzelt
    const studentForm = document.querySelector('form[action*="students"]');
    if (studentForm && birthDatePicker) {
        studentForm.addEventListener('submit', function(e) {
            // Flatpickr'dan seçilen tarihi al
            if (birthDatePicker.selectedDates.length > 0) {
                // Seçilen tarihi YYYY-MM-DD formatına çevir
                const date = birthDatePicker.selectedDates[0];
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                birthDateInput.value = `${year}-${month}-${day}`;
            } else if (birthDateInput.value && birthDateInput.value.includes('.')) {
                // Eğer manuel olarak gün.ay.yıl formatında girildiyse
                const parts = birthDateInput.value.split('.');
                if (parts.length === 3) {
                    const day = parts[0].padStart(2, '0');
                    const month = parts[1].padStart(2, '0');
                    const year = parts[2];
                    birthDateInput.value = `${year}-${month}-${day}`;
                }
            }
        });
    }

});
</script>