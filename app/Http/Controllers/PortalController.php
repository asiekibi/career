<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCertificate;
use App\Models\User;
use Illuminate\View\View;

class PortalController extends Controller
{
    /**
     * Open Portal login
     */
    public function showPortalLogin(): View
    {
        return view('portal.portal-login');
    }

    /**
     * Search certificate
     */
    public function searchCertificate(Request $request)
    {
        $request->validate([
            'certificate_code' => 'required|string|max:255'
        ]);

        // Sertifika koduna göre kullanıcıyı bul
        $userCertificate = UserCertificate::where('certificate_code', $request->certificate_code)
            ->with(['user.cvs.experiences', 'user.cvs.educations', 'user.cvs.abilities', 'user.cvs.languages', 'user.userBadges.badge', 'user.userCertificates.certificate'])
            ->first();

        if (!$userCertificate) {
            return response()->json([
                'success' => false,
                'message' => 'Sertifika kaydına ulaşılamamıştır.'
            ]);
        }

        return response()->json([
            'success' => true,
            'student' => $userCertificate->user
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

        return view('portal.user-cv', compact('student'));
    }

    /**
     * Show career sequence
     */
    public function careerSequence(): View
    {
        $students = User::where('role', 'user')
            ->with(['userBadges.badge', 'userCertificates'])
            ->get()
            ->sortByDesc('point');
        
        return view('portal.carier-sequence', compact('students'));
    }
}
