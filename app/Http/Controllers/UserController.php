<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\Badge;
use App\Models\UserBadge;
use App\Models\Education;
use App\Models\Ability;
use App\Models\Language;
use App\Models\Experience;
use App\Mail\NewUserPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display user dashboard page. - user pages
     */
    public function userDashboard(): View
    {
        $user = Auth::user();
        $userCvs = $user->cvs()->latest()->get();
        $userBadges = $user->userBadges()->with('badge')->get();
        
        // Kullanıcının deneyimler, eğitimler ve yeteneklerini CV üzerinden getir
        $experiences = $user->experiences()->latest('start_date')->get();
        $educations = $user->educations()->latest('start_date')->get();
        $abilities = $user->abilities()->get();
        $languages = $user->languages()->get();
        
        return view('user.main', compact('user', 'userCvs', 'userBadges', 'experiences', 'educations', 'abilities', 'languages'));
    }

    /**
     * Display student list page. - admin pages
     */
    public function studentList(): View
    {
        $students = User::where('role', 'user')
            ->whereNull('deleted_at') // Soft deleted kayıtları hariç tut
            ->orderBy('name', 'asc')
            ->get();
        
        return view('admin.student-list', compact('students'));
    }

   
    /**
     * Remove the specified resource from storage.
    */
    public function destroy(Request $request)
    {
        // ID'yi payload'dan al
        $studentId = $request->input('student_id');
        
        if (!$studentId) {
            return response()->json([
                'success' => false,
                'message' => 'Öğrenci ID\'si bulunamadı.'
            ], 400);
        }

        try {
            // Öğrenciyi bul ve sil
            $student = User::findOrFail($studentId);
            $student->delete();

            return response()->json([
                'success' => true,
                'message' => 'Öğrenci başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Silme işlemi başarısız: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.add-student');
    }

    /**
     * Show the form for editing a new resource.
     */
    public function edit($id): View
    {
        $user = User::with('location')->findOrFail($id);
        return view('admin.add-student', compact('user'));
    }
  
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'gsm' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'country_id' => 'nullable|integer|exists:countries,id',
            'city_id' => 'nullable|string',
            'district_id' => 'nullable|string',
            'contact_info' => 'required|boolean',
        ], [
            'full_name.required' => 'Ad Soyad gereklidir.',
            'email.required' => 'Email gereklidir.',
            'email.email' => 'Geçerli bir email adresi giriniz.',
            'email.unique' => 'Bu email adresi zaten kullanılıyor.',
            'gsm.required' => 'GSM gereklidir.',
            'birth_date.required' => 'Doğum tarihi gereklidir.',
            'country_id.exists' => 'Seçilen ülke geçersiz.',
            'contact_info.required' => 'İletişim izni seçimi gereklidir.',
        ]);

        // Ad soyadı ayır
        $nameParts = explode(' ', $request->full_name, 2);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

        $updateData = [
            'name' => $firstName,
            'surname' => $lastName,
            'email' => $request->email,
            'gsm' => $request->gsm,
            'birth_date' => $request->birth_date,
            'contact_info' => $request->contact_info,
        ];
        
        // country_id'yi ekle
        if ($request->country_id) {
            $updateData['country_id'] = $request->country_id;
        }
        
        // Sadece district_id varsa location_id ve district_id'yi güncelle
        if ($request->district_id) {
            $updateData['location_id'] = $request->district_id;
            $updateData['district_id'] = $request->district_id;
        }
        
        
        $result = $user->update($updateData);
        

        return redirect()->route('admin.dashboard')->with('success', 'Öğrenci başarıyla güncellendi!');
    }
       /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'gsm' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'country_id' => 'required|integer|exists:countries,id',
            'city_id' => 'required|string',
            'district_id' => 'required|string',
            'contact_info' => 'required|boolean',
        ], [
            'full_name.required' => 'Ad Soyad gereklidir.',
            'email.required' => 'Email gereklidir.',
            'email.email' => 'Geçerli bir email adresi giriniz.',
            'email.unique' => 'Bu kullanıcı zaten kayıtlı. Lütfen farklı bir email adresi kullanın.',
            'gsm.required' => 'GSM gereklidir.',
            'birth_date.required' => 'Doğum tarihi gereklidir.',
            'country_id.required' => 'Ülke seçimi gereklidir.',
            'country_id.exists' => 'Seçilen ülke geçersiz.',
            'city_id.required' => 'İl seçimi gereklidir.',
            'district_id.required' => 'İlçe seçimi gereklidir.',
            'contact_info.required' => 'İletişim izni seçimi gereklidir.',
        ]);
    
        // Ad ve soyadı ayır
        $nameParts = explode(' ', $request->full_name, 2);
        $name = $nameParts[0];
        $surname = isset($nameParts[1]) ? $nameParts[1] : '';
    
        // Güvenli rastgele şifre oluştur
        $temporaryPassword = \Illuminate\Support\Str::random(12);
    
        // Rastgele register numarası oluştur (veritabanında olmayan)
        $registerNumber = $this->generateUniqueRegisterNumber();
    
        // Kullanıcı oluştur
        $user = User::create([
            'name' => $name,
            'surname' => $surname,
            'email' => $request->email,
            'password' => Hash::make($temporaryPassword), // Rastgele şifre kullan
            'gender' => 'other',
            'birth_date' => $request->birth_date,
            'gsm' => $request->gsm,
            'register_number' => $registerNumber,
            'point' => '0',
            'country_id' => $request->country_id,
            'location_id' => $request->district_id,
            'district_id' => $request->district_id,
            'contact_info' => $request->contact_info,
            'profile_photo_url' => '',
            'role' => 'user',
            'is_active' => true,
        ]);
    
        // Email gönder
        try {
            Mail::to($user->email)->send(new NewUserPasswordMail($user, $temporaryPassword));
            
            return redirect()->route('admin.dashboard')->with('success', 'Öğrenci başarıyla eklendi! Şifre email ile gönderildi.');
        } catch (\Exception $e) {
            // Hata detaylarını logla
            \Log::error('Yeni kullanıcı email gönderim hatası', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.dashboard')
                ->with('warning', 'Öğrenci başarıyla eklendi ancak şifre email ile gönderilemedi. Hata: ' . $e->getMessage() . ' Lütfen manuel olarak şifreyi paylaşın: ' . $temporaryPassword);
        }
    }

    /**
     * Update user profile photo (sadece resim yükleme için)
     */
    public function updateProfilePhoto(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ], [
            'profile_photo.required' => 'Profil resmi gereklidir.',
            'profile_photo.image' => 'Dosya bir resim olmalıdır.',
            'profile_photo.mimes' => 'Resim formatı jpeg, png, jpg veya gif olmalıdır.',
            'profile_photo.max' => 'Resim boyutu 5MB\'dan küçük olmalıdır.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Eski resmi sil (eğer varsa ve public klasöründe ise)
            if ($user->profile_photo_url && str_contains($user->profile_photo_url, 'profile_photos/')) {
                $oldPath = public_path('profile_photos/' . basename($user->profile_photo_url));
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Yeni resmi kaydet - public/profile_photos altına
            $file = $request->file('profile_photo');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->move(public_path('profile_photos'), $filename);
            
            // URL'i oluştur
            $profilePhotoUrl = '/profile_photos/' . $filename;

            // Kullanıcıyı güncelle
            $user->update([
                'profile_photo_url' => $profilePhotoUrl
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profil resmi başarıyla güncellendi',
                'data' => [
                    'profile_photo_url' => $profilePhotoUrl
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resim yüklenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * assign badge to user
     */
    public function storeBadge(Request $request, $id)
    {
        $request->validate([
            'badge_id' => 'required|exists:badges,id',
        ]);

        $user = User::findOrFail($id);
        
        // Aynı rozet zaten atanmış mı kontrol et
        $existingBadge = $user->userBadges()->where('badge_id', $request->badge_id)->first();
        
        if ($existingBadge) {
            return redirect()->back()->with('error', 'Bu rozet zaten atanmış!');
        }
        
        // Rozeti ata
        $user->userBadges()->create([
            'badge_id' => $request->badge_id,
            'assigned_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Rozet başarıyla atandı!');
    }

    /**
     * remove badge from user
     */
    public function removeBadge($userBadgeId)
    {
        try {
            $userBadge = UserBadge::findOrFail($userBadgeId);
            $userBadge->delete();
            
            return redirect()->back()->with('success', 'Rozet başarıyla kaldırıldı.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Rozet kaldırılırken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * store experience
     */
    public function storeExperience(Request $request)
    {
        try {
            $request->validate([
                'position' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date',
                'description' => 'nullable|string',
            ], [
                'position.required' => 'Pozisyon alanı zorunludur.',
                'company_name.required' => 'Şirket adı alanı zorunludur.',
                'start_date.required' => 'Başlangıç tarihi alanı zorunludur.',
                'start_date.date' => 'Geçerli bir başlangıç tarihi giriniz.',
            ]);

            $user = Auth::user();
            $userCv = $user->cvs()->first();
            
            if (!$userCv) {
                $userCv = $user->cvs()->create(['resume' => '', 'hobbies' => '']);
            }
            
            $experience = $userCv->experiences()->create([
                'position' => $request->position,
                'company_name' => $request->company_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Deneyim başarıyla eklendi!',
                'data' => [
                    'id' => $experience->id
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation hatası',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Deneyim eklenirken hata oluştu.'
            ], 500);
        }
    }

    /**
     * store education
     */
    public function storeEducation(Request $request)
    {
        $user = Auth::user();
        $userCv = $user->cvs()->first();
        
        if (!$userCv) {
            $userCv = $user->cvs()->create(['resume' => '', 'hobbies' => '']);
        }
        
        $request->validate([
            'school_name' => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'degree' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $education = $userCv->educations()->create([
            'school_name' => $request->school_name,
            'field_of_study' => $request->field_of_study,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'degree' => $request->degree,
            'description' => $request->description
        ]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Eğitim başarıyla eklendi!',
            'data' => [
                'id' => $education->id,
                'education' => $education
            ]
        ]);
    }

    /**
     * store ability
     */
    public function storeAbility(Request $request)
    {
        $user = Auth::user();
        $userCv = $user->cvs()->first();
        
        if (!$userCv) {
            $userCv = $user->cvs()->create(['resume' => '', 'hobbies' => '']);
        }
        
        $request->validate([
            'abilities_name' => 'required|string|max:255',
            'level' => 'required|string|in:beginner,intermediate,advanced,expert' // Database enum değerleri
        ]);
        
        $ability = $userCv->abilities()->create([
            'abilities_name' => $request->abilities_name,
            'level' => $request->level
        ]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Yetenek başarıyla eklendi!',
            'data' => [
                'id' => $ability->id,
                'ability' => $ability
            ]
        ]);
    }

    /**
     * store language
     */
    public function storeLanguage(Request $request)
    {
        $user = Auth::user();
        $userCv = $user->cvs()->first();
        
        if (!$userCv) {
            $userCv = $user->cvs()->create(['resume' => '', 'hobbies' => '']);
        }
        
        $request->validate([
            'language_name' => 'required|string|max:255',
            'level' => 'required|string|in:basic,conversational,fluent,native' // Database enum değerleri
        ]);
        
        $language = $userCv->languages()->create([
            'language_name' => $request->language_name,
            'level' => $request->level,
            'description' => '' // Boş string olarak ekle
        ]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Dil başarıyla eklendi!',
            'data' => [
                'id' => $language->id,
                'language' => $language
            ]
        ]);
    }

    /**
     * carier sequence
     */
    public function carierSequence(): View
    {
        $allStudents = User::where('role', 'user')
            ->with(['userBadges.badge', 'userCertificates.certificate', 'location'])
            ->get()
            ->sortByDesc('point');
        
        return view('user.carier-sequence', compact('allStudents'));
    }

    /**
     * show cv
     */
    public function showCv($id): View
    {
        $student = User::where('role', 'user')
            ->with([
                'userBadges.badge', 
                'userCertificates.certificate', 
                'location', 
                'cvs.experiences', 
                'cvs.educations', 
                'cvs.abilities', 
                'cvs.languages'
            ])
            ->findOrFail($id);
        
        return view('user.user-cv', compact('student'));
    }
    
    /**
     * delete experience
     */
    public function deleteExperience(Request $request)
    {
        try {
            $id = $request->input('id');
            $experience = Experience::with('cv')->findOrFail($id);
            
            // user check
            if ($experience->cv->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu deneyimi silme yetkiniz yok.'
                ], 403);
            }
            
            $experience->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Deneyim başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Deneyim silinirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * delete education
     */
    public function deleteEducation(Request $request)
    {
        try {
            $id = $request->input('id');
            $education = Education::with('cv')->findOrFail($id);
            
            // user check
            if ($education->cv->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu eğitimi silme yetkiniz yok.'
                ], 403);
            }
            
            $education->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Eğitim başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Eğitim silinirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * delete ability
     */
    public function deleteAbility(Request $request)
    {
        try {
            $id = $request->input('id');
            $ability = Ability::with('cv')->findOrFail($id);
            
            // user check
            if ($ability->cv->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu yeteneği silme yetkiniz yok.'
                ], 403);
            }
            
            $ability->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Yetenek başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Yetenek silinirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * delete language
     */
    public function deleteLanguage(Request $request)
    {
        try {
            $id = $request->input('id');
            $language = Language::with('cv')->findOrFail($id);
            
            // user check
            if ($language->cv->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu dili silme yetkiniz yok.'
                ], 403);
            }
            
            $language->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Dil başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dil silinirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * update profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'gsm' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'contact_info' => 'nullable|boolean', // Yeni eklenen alan
        ]);

        $user = auth()->user();
        $user->update([
            'gsm' => $request->gsm,
            'email' => $request->email,
            'contact_info' => $request->contact_info ?? $user->contact_info, // Yeni eklenen alan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil başarıyla güncellendi!'
        ]);
    }

    /**
     * Partner companies listesi - admin pages
     */
    public function partnerCompanies(): View
    {
        $partnerCompanies = \App\Models\PartnerCompany::orderBy('created_at', 'desc')->get();
        return view('admin.partner-companies', compact('partnerCompanies'));
    }

    /**
     * Partner company permission güncelleme
     */
    public function updatePartnerPermission(Request $request, $id)
    {
        try {
            $company = \App\Models\PartnerCompany::findOrFail($id);
            $company->has_permission = $request->has_permission;
            $company->save();

            return response()->json([
                'success' => true,
                'message' => 'İzin durumu güncellendi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Veritabanında olmayan benzersiz register numarası oluştur
     */
    private function generateUniqueRegisterNumber(): string
    {
        do {
            // 8 haneli rastgele numara oluştur
            $registerNumber = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            
            // Veritabanında bu numara var mı kontrol et
            $exists = User::where('register_number', $registerNumber)->exists();
        } while ($exists); // Varsa yeni numara oluştur
        
        return $registerNumber;
    }
}