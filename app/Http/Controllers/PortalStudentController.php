<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCertificate;
use App\Models\User;
use Illuminate\View\View;
use App\Models\PartnerCompany;

class PortalStudentController extends Controller
{
    /**
     * Open Portal login
     */
    public function showPortalLogin(): View
    {
        return view('portal-user.portal-login');
    }

    /**
     * Search certificate
     */
    public function searchCertificate(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'certificate_code' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:50'
        ]);

        // Ad soyadı ayır
        $nameParts = explode(' ', trim($request->full_name), 2);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

        // search by certificate code and user name
        $userCertificate = UserCertificate::where('certificate_code', $request->certificate_code)
            ->whereHas('user', function($query) use ($firstName, $lastName) {
                $query->where('name', 'LIKE', $firstName . '%');
                if ($lastName) {
                    $query->where('surname', 'LIKE', $lastName . '%');
                }
            })
            ->with(['user.cvs.experiences', 'user.cvs.educations', 'user.cvs.abilities', 'user.cvs.languages', 'user.userBadges.badge', 'user.userCertificates.certificate'])
            ->first();

        if (!$userCertificate) {
            return response()->json([
                'success' => false,
                'message' => 'Sertifika kaydına ulaşılamamıştır. Lütfen ad soyad ve sertifika numarasını kontrol edin.'
            ]);
        }

        // company check
        $isCompanyAuth = false;
        $companyName = null;
        $loginType = 'student'; // default student login
        
        if ($request->tax_number) {
            // if tax number is provided, company login
            $company = PartnerCompany::where('tax_number', $request->tax_number)
                ->where('has_permission', true)
                ->first();
            
            if ($company) {
                $isCompanyAuth = true;
                $companyName = $company->company_name;
                $loginType = 'company';
            } else {
                // wrong tax number - permission denied
                return response()->json([
                    'success' => false,
                    'message' => 'Girilen vergi numarası ile yetkili firma bulunamadı.'
                ]);
            }
        } else {
            // if tax number is not provided, only student login
            $isCompanyAuth = false;
            $loginType = 'student';
        }

        // save information to session
        session([
            'student_id' => $userCertificate->user->id,
            'is_company_auth' => $isCompanyAuth,
            'company_tax_number' => $request->tax_number,
            'company_name' => $companyName,
            'login_type' => $loginType
        ]);

        return response()->json([
            'success' => true,
            'student' => $userCertificate->user,
            'is_company_auth' => $isCompanyAuth,
            'login_type' => $loginType,
            'company_name' => $companyName
        ]);
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

        return view('portal-user.user-cv', compact('student', 'loginType', 'companyName', 'isCompanyAuth'));
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
        
        return view('portal-user.carier-sequence', compact('students'));
    }

    /**
     * Show partner company page
     */
    public function partnerCompany(): View
    {
        return view('portal-user.partner-company');
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
}

