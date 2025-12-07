<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCertificate;
use App\Models\User;
use Illuminate\View\View;
use App\Models\PartnerCompany;

class PortalCompanyController extends Controller
{
    /**
     * Open Portal login
     */
    public function showPortalLogin(): View
    {
        return view('portal-company.portal-login');
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
     * Show main page - CV görüntüleme sayfası
     */
    public function showMain(): View
    {
        // Session'dan öğrenci ID'sini al
        $studentId = session('student_id');
        
        if (!$studentId) {
            return redirect()->route('company-portal-login');
        }

        $student = User::where('id', $studentId)
            ->with(['cvs.experiences', 'cvs.educations', 'cvs.abilities', 'cvs.languages', 'userBadges.badge', 'userCertificates.certificate'])
            ->firstOrFail();

        // Giriş tipini session'dan al
        $loginType = session('login_type', 'student');
        $companyName = session('company_name');
        $isCompanyAuth = session('is_company_auth', false);

        return view('portal-company.main', compact('student', 'loginType', 'companyName', 'isCompanyAuth'));
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
}
