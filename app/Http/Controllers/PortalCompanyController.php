<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCertificate;
use App\Models\User;
use App\Models\Certificate;
use App\Models\Country;
use App\Models\Location;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\PartnerCompany;
use App\Models\CompanyRequest;
use Illuminate\Support\Facades\Auth;

class PortalCompanyController extends Controller
{
    /**
     * Show company portal login page
     */
    public function showPortalLogin(): View
    {
        return view('portal-company.portal-login');
    }

    /**
     * Company login - Email ve şifre ile normal auth girişi
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'Email adresi gereklidir.',
            'email.email' => 'Geçerli bir email adresi giriniz.',
            'password.required' => 'Şifre gereklidir.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.'
        ]);

        // Login credentials
        $credentials = $request->only('email', 'password');

        // Company tipi kullanıcıyı kontrol et
        $company = User::where('email', $request->email)
            ->where('role', 'company')
            ->first();

        if (!$company) {
            return back()->withErrors([
                'email' => 'Bu email adresi ile kayıtlı firma bulunamadı.'
            ])->withInput($request->except('password'));
        }

        // Company onaylı mı kontrol et
        if (!$company->company_approved) {
            return back()->withErrors([
                'email' => 'Firma hesabınız henüz onaylanmamış. Lütfen onay bekleyin.'
            ])->withInput($request->except('password'));
        }

        // Aktif mi kontrol et
        if (!$company->is_active) {
            return back()->withErrors([
                'email' => 'Firma hesabınız aktif değil.'
            ])->withInput($request->except('password'));
        }

        // Normal auth ile giriş yap
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            // Session'a ek bilgileri kaydet
            session([
                'company_id' => Auth::user()->id,
                'company_name' => Auth::user()->name . ' ' . Auth::user()->surname,
                'is_company_auth' => true,
                'login_type' => 'company'
            ]);

            return redirect()->route('company-portal.main');
        }

        // Giriş başarısız
        return back()->withErrors([
            'email' => 'Giriş başarısız. Email veya şifre hatalı.'
        ])->withInput($request->except('password'));
    }

    /**
     * Search certificate - Company portal için register numarası ile giriş
     */
    public function searchCertificate(Request $request)
    {
        $request->validate([
            'register_number' => 'required|string|max:50'
        ]);

        // Register numarası ile öğrenci arama
        $student = User::where('register_number', $request->register_number)
            ->where('role', 'user')
            ->with(['cvs.experiences', 'cvs.educations', 'cvs.abilities', 'cvs.languages', 'userBadges.badge', 'userCertificates.certificate'])
            ->first();
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Girilen kayıt numarası ile öğrenci bulunamadı.'
            ]);
        }

        // Session'a kaydet
        session([
            'student_id' => $student->id,
            'is_company_auth' => false,
            'login_type' => 'student',
            'register_number' => $request->register_number
        ]);

        return response()->json([
            'success' => true,
            'student' => $student,
            'is_company_auth' => false,
            'login_type' => 'student'
        ]);
    }

    /**
     * Show main page - CV görüntüleme sayfası (Firma girişi veya öğrenci session'ı ile)
     */
    public function showMain(): View|RedirectResponse
    {
        // Firma girişi kontrolü
        if (Auth::check() && Auth::user()->role === 'company') {
            $company = Auth::user();
            
            // Firma onaylı mı kontrol et
            if (!$company->company_approved) {
                return redirect()->route('login')->withErrors(['error' => 'Firma hesabınız henüz onaylanmamış.']);
            }

            // Firma aktif mi kontrol et
            if (!$company->is_active) {
                return redirect()->route('login')->withErrors(['error' => 'Firma hesabınız aktif değil.']);
            }

            $companyName = $company->name . ' ' . $company->surname;
            $isCompanyAuth = true;
            $loginType = 'company';
            
            $users = User::where('role', 'user')
                ->whereNull('deleted_at')
                ->with(['userBadges.badge', 'cvs', 'userCertificates.certificate', 'country', 'location.country', 'location.parent'])
                ->orderBy('name', 'asc')
                ->get();
            
            // Tüm sertifikaları filtreleme için getir
            $certificates = Certificate::orderBy('certificate_name', 'asc')->get();
            
            // Tüm ülkeleri filtreleme için getir
            $countries = Country::orderBy('name', 'asc')->get();
            
            // Tüm illeri filtreleme için getir (parent_id = 0 olanlar)
            $locations = Location::where('parent_id', 0)
                ->orderBy('location', 'asc')
                ->get();
            
            return view('portal-company.main', compact('users', 'certificates', 'countries', 'locations', 'companyName', 'isCompanyAuth', 'loginType'));
        }
        
        // Öğrenci session kontrolü - portal-user/portal-login.blade.php'den gelen sorgulama için
        $studentId = session('student_id');
        if ($studentId) {
            try {
            $student = User::where('id', $studentId)
                ->with(['cvs.experiences', 'cvs.educations', 'cvs.abilities', 'cvs.languages', 'userBadges.badge', 'userCertificates.certificate'])
                ->firstOrFail();

            $companyName = session('company_name');
            $isCompanyAuth = session('is_company_auth', false);
            $loginType = session('login_type', 'student');

                // Firma girişi için CV listesi göster
                $users = User::where('role', 'user')
                    ->whereNull('deleted_at')
                    ->with(['userBadges.badge', 'cvs', 'userCertificates.certificate', 'country', 'location.country', 'location.parent'])
                    ->orderBy('name', 'asc')
                    ->get();
                
                // Tüm sertifikaları filtreleme için getir
                $certificates = Certificate::orderBy('certificate_name', 'asc')->get();
                
                // Tüm ülkeleri filtreleme için getir
                $countries = Country::orderBy('name', 'asc')->get();
                
                // Tüm illeri filtreleme için getir (parent_id = 0 olanlar)
                $locations = Location::where('parent_id', 0)
                    ->orderBy('location', 'asc')
                    ->get();

                // Firma girişi gibi göster (CV listesi)
                $loginType = 'company';
                
                return view('portal-company.main', compact('users', 'certificates', 'countries', 'locations', 'companyName', 'isCompanyAuth', 'loginType'));
            } catch (\Exception $e) {
                // Öğrenci bulunamazsa session'ı temizle ve login sayfasına yönlendir
                session()->forget(['student_id', 'searched_certificate_id', 'is_company_auth', 'company_name', 'login_type']);
                return redirect()->route('login')->withErrors(['error' => 'Öğrenci bilgileri bulunamadı. Lütfen tekrar giriş yapın.']);
            }
        }
        
        // Hiçbir giriş yoksa login sayfasına yönlendir
        return redirect()->route('login')->withErrors(['error' => 'Giriş yapmanız gerekmektedir.']);
    }

    /**
     * Show student cv
     */
    public function showStudentCv($userId): View
    {
        $student = User::where('id', $userId)
            ->with(['cvs.experiences', 'cvs.educations', 'cvs.abilities', 'cvs.languages', 'userBadges.badge', 'userCertificates.certificate'])
            ->firstOrFail();

        // Giriş tipini session'dan al
        $loginType = session('login_type', 'student');
        $companyName = session('company_name');
        $isCompanyAuth = session('is_company_auth', false);

        return view('portal-company.user-cv', compact('student', 'loginType', 'companyName', 'isCompanyAuth'));
    }

    /**
     * Show career sequence
     */
    public function careerSequence(): View
    {
        $students = User::where('role', 'user')
            ->with(['userBadges.badge', 'userCertificates'])
            ->get()
            ->sortByDesc('point')
            ->values(); // Collection'ı yeniden indeksle
        
        return view('portal-company.carier-sequence', compact('students'));
    }

    /**
     * Show partner company page
     */
    public function partnerCompany(): View
    {
        return view('portal-company.partner-company');
    }

    /**
     * Store partner application
     */
    public function storePartnerCompany(Request $request)
    {
        try {
            $request->validate([
                'contact_person' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'company_name' => 'required|string|max:255',
                'tax_office' => 'required|string|max:255',
                'tax_number' => 'required|string|max:50|unique:partner_companies,tax_number',
                'message' => 'nullable|string'
            ]);

            PartnerCompany::create([
                'contact_person' => $request->contact_person,
                'birth_date' => $request->birth_date,
                'phone' => $request->phone,
                'email' => $request->email,
                'company_name' => $request->company_name,
                'tax_office' => $request->tax_office,
                'tax_number' => $request->tax_number,
                'message' => $request->message,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Başvurunuz başarıyla gönderildi. En kısa sürede size dönüş yapacağız.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bu vergi numarası ile daha önce başvuru yapılmış.'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store company request - Public form submission
     */
    public function storeCompanyRequest(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:company_requests,email',
                'company_name' => 'required|string|max:255',
                'tax_number' => 'required|string|max:50|unique:company_requests,tax_number',
                'tax_office' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'message' => 'nullable|string'
            ], [
                'email.unique' => 'Bu email adresi ile daha önce başvuru yapılmış.',
                'tax_number.unique' => 'Bu vergi numarası ile daha önce başvuru yapılmış.'
            ]);

            CompanyRequest::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'company_name' => $request->company_name,
                'tax_number' => $request->tax_number,
                'tax_office' => $request->tax_office,
                'phone' => $request->phone,
                'address' => $request->address,
                'message' => $request->message,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Başvurunuz başarıyla gönderildi. En kısa sürede size dönüş yapacağız.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $firstError = collect($errors)->flatten()->first();
            
            return response()->json([
                'success' => false,
                'message' => $firstError ?? 'Lütfen formu kontrol edin.'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu. Lütfen tekrar deneyin.'
            ], 500);
        }
    }
}
