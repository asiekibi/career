<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Models\CertificateEducation;
use App\Models\UserCertificate;
use App\Models\User;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $certificates = Certificate::with('certificateEducations')->get();
        return view('admin.certificates', compact('certificates'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'certificate_name' => 'required|string|max:255',
            'course' => 'array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Sertifikayı oluştur
        $certificate = Certificate::create([
            'certificate_name' => $request->certificate_name,
        ]);

        // Boş olmayan dersleri filtrele ve ekle
        if ($request->has('course')) {
            $validCourses = array_filter($request->course, function($courseName) {
                return !empty(trim($courseName));
            });

            foreach ($validCourses as $courseName) {
                CertificateEducation::create([
                    'certificate_id' => $certificate->id,
                    'course_name' => $courseName,
                ]);
            }
        }

        return redirect()->route('admin.certificates')->with('success', 'Sertifika başarıyla eklendi!');
    }

    /**
     * get assign certificate
     */
    public function getAssignCertificate($id)
    {
        $user = User::findOrFail($id);
        $certificates = Certificate::all();
        $userCertificates = $user->userCertificates()->with('certificate')->get();
        
        return view('admin.add-certificate', compact('user', 'certificates', 'userCertificates'));
    }

    /**
     * store assign certificate
     */
    public function storeAssignCertificate(Request $request, $id)
    {
        $request->validate([
            'certificate_id' => 'required|exists:certificates,id',
            'certificate_code' => 'nullable|string|max:255|unique:user_certificates,certificate_code',
            'score' => 'nullable|numeric|min:0',
            'issuer' => 'nullable|string|max:255',
            'issue_date' => 'nullable|date',
            'validity_period' => 'nullable|numeric|min:1|max:10',
        ], [
            'certificate_id.required' => 'Sertifika seçimi gereklidir.',
            'certificate_id.exists' => 'Seçilen sertifika bulunamadı.',
            'certificate_code.unique' => 'Bu sertifika kodu zaten kullanılmış.',
            'score.numeric' => 'Başarı puanı sayısal olmalıdır.',
            'score.min' => 'Başarı puanı 0\'dan küçük olamaz.',
            'validity_period.numeric' => 'Geçerlilik süresi sayısal olmalıdır.',
            'validity_period.min' => 'Geçerlilik süresi en az 1 yıl olmalıdır.',
            'validity_period.max' => 'Geçerlilik süresi en fazla 10 yıl olabilir.',
        ]);

        $user = User::findOrFail($id);
        
        // Check if the same certificate is already assigned
        $existingCertificate = $user->userCertificates()->where('certificate_id', $request->certificate_id)->first();
        
        if ($existingCertificate) {
            return redirect()->back()->with('error', 'Bu sertifika zaten atanmış!');
        }
        
        // Assign certificate
        $userCertificate = $user->userCertificates()->create([
            'certificate_id' => $request->certificate_id,
            'certificate_code' => $request->certificate_code,
            'achievement_score' => $request->score ?? 0,
            'issuing_institution' => $request->issuer,
            'acquisition_date' => $request->issue_date,
            'validity_period' => $request->validity_period,
            'success_score' => $request->score ?? 0,
        ]);

        // User's point value update
        $user->point = $user->point + ($request->score ?? 0);
        $user->save();

        return redirect()->back()->with('success', 'Sertifika başarıyla atandı!');
    }

    /**
     * remove certificate
     */
    public function removeCertificate(Request $request)
    {
        $userCertificateId = $request->input('id');
        $userCertificate = UserCertificate::findOrFail($userCertificateId);
        
        // user point update
        $user = $userCertificate->user;
        $user->point = $user->point - $userCertificate->success_score;
        $user->save();
        
        $userCertificate->delete();
        return redirect()->back()->with('success', 'Sertifika başarıyla kaldırıldı!');
    }

}