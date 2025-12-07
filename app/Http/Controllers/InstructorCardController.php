<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\InstructorCardRequest;
use App\Models\InstructorCardCertificate;
use Illuminate\Support\Facades\Auth;

class InstructorCardController extends Controller
{
    /**
     * Display instructor card requests page (Admin)
     */
    public function index(): View
    {
        $requests = InstructorCardRequest::with(['user', 'certificates.userCertificate.certificate'])->orderBy('created_at', 'desc')->get();
        
        // Her kullanıcı için kalan başvuru hakkını hesapla (varsayılan limit: 3)
        $maxRequestsPerUser = 3;
        foreach ($requests as $request) {
            $userTotalRequests = InstructorCardRequest::where('user_id', $request->user_id)
                ->where('is_excluded_from_count', false)
                ->count();
            $request->remaining_requests = max(0, $maxRequestsPerUser - $userTotalRequests);
        }
        
        return view('admin.instructor-card-requests', compact('requests'));
    }

    /**
     * Show the details of an instructor card request (Admin)
     */
    public function show($id)
    {
        $request = InstructorCardRequest::with([
            'user',
            'certificates.userCertificate.certificate'
        ])->findOrFail($id);
        
        // Kalan başvuru hakkını hesapla
        $maxRequestsPerUser = 3;
        $userTotalRequests = InstructorCardRequest::where('user_id', $request->user_id)
            ->where('is_excluded_from_count', false)
            ->count();
        $remainingRequests = max(0, $maxRequestsPerUser - $userTotalRequests);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $request->id,
                'instructor_name' => $request->instructor_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status,
                'notes' => $request->notes,
                'created_at' => $request->created_at->format('d.m.Y H:i'),
                'user_id' => $request->user_id,
                'remaining_requests' => $remainingRequests,
                'user' => [
                    'name' => $request->user->name ?? '',
                    'surname' => $request->user->surname ?? '',
                    'email' => $request->user->email ?? '',
                ],
                'certificates' => $request->certificates->map(function($cert) {
                    $userCert = $cert->userCertificate;
                    return [
                        'id' => $userCert->id,
                        'certificate_name' => $userCert->certificate->certificate_name ?? 'Sertifika adı bulunamadı',
                        'certificate_code' => $userCert->certificate_code ?? 'Belirtilmemiş',
                        'achievement_score' => $userCert->achievement_score ?? 0,
                        'issuing_institution' => $userCert->issuing_institution ?? 'Belirtilmemiş',
                        'acquisition_date' => $userCert->acquisition_date ? $userCert->acquisition_date->format('d.m.Y') : 'Belirtilmemiş',
                    ];
                }),
            ]
        ]);
    }

    /**
     * Show the form for creating a new instructor card request (User)
     */
    public function create(): View
    {
        $user = Auth::user();
        $userCertificates = $user->userCertificates()->with('certificate')->get();
        $currentRequestCount = InstructorCardRequest::where('user_id', $user->id)
            ->where('is_excluded_from_count', false)
            ->count();
        $nextRequestCount = $currentRequestCount + 1;
        
        // Kalan başvuru hakkını hesapla
        $maxRequestsPerUser = 3;
        $remainingRequests = max(0, $maxRequestsPerUser - $currentRequestCount);
        
        return view('user.instructor-card-request', compact('user', 'userCertificates', 'currentRequestCount', 'nextRequestCount', 'remainingRequests'));
    }

    /**
     * Store a newly created instructor card request (User)
     */
    public function store(Request $request)
    {
        $request->validate([
            'instructor_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'certificates' => 'nullable|array',
            'certificates.*' => 'exists:user_certificates,id',
        ], [
            'instructor_name.required' => 'Eğitmen adı gereklidir.',
            'email.required' => 'Email gereklidir.',
            'email.email' => 'Geçerli bir email adresi giriniz.',
            'phone.required' => 'Telefon numarası gereklidir.',
            'certificates.array' => 'Sertifikalar geçersiz format.',
            'certificates.*.exists' => 'Seçilen sertifikalardan biri bulunamadı.',
        ]);

        // Kullanıcının daha önce kaç talep oluşturduğunu hesapla
        $previousRequestCount = InstructorCardRequest::where('user_id', Auth::id())
            ->where('is_excluded_from_count', false)
            ->count();
        
        // Kalan başvuru hakkını kontrol et
        $maxRequestsPerUser = 3;
        if ($previousRequestCount >= $maxRequestsPerUser) {
            return redirect()->route('user.instructor-card-request.create')
                ->withErrors(['limit' => 'Maksimum başvuru hakkınızı kullandınız. Yeni başvuru yapamazsınız.']);
        }
        
        $requestCount = $previousRequestCount + 1;

        $instructorCardRequest = InstructorCardRequest::create([
            'user_id' => Auth::id(),
            'instructor_name' => $request->instructor_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'pending',
            'request_count' => $requestCount,
        ]);

        // Seçilen sertifikaları ekle
        if ($request->has('certificates') && is_array($request->certificates)) {
            foreach ($request->certificates as $userCertificateId) {
                InstructorCardCertificate::create([
                    'instructor_card_request_id' => $instructorCardRequest->id,
                    'user_certificate_id' => $userCertificateId,
                ]);
            }
        }

        return redirect()->route('user.instructor-card-request.create')
            ->with('success', 'Eğitmen kimlik kartı talebiniz başarıyla gönderildi!');
    }

    /**
     * Toggle kimlik kartı durumu (Verildi/Verilmedi)
     */
    public function toggleCardStatus($id)
    {
        $request = InstructorCardRequest::with(['certificates.userCertificate.certificate'])->findOrFail($id);
        
        $hasCertificates = $request->certificates()->count() > 0;
        
        if ($hasCertificates) {
            // Eğer sertifikalar varsa, tüm sertifikaları sil (Verilmedi yap)
            $request->certificates()->delete();
            $message = 'Kimlik kartı durumu "Verilmedi" olarak güncellendi.';
            $isIssued = false;
            
            // Güncellenmiş sertifikaları al (boş olacak)
            $request->refresh();
            $request->load('certificates.userCertificate.certificate');
        } else {
            // Eğer sertifika yoksa, kullanıcının ilk sertifikasını ekle (Verildi yap)
            $user = $request->user;
            $firstCertificate = $user->userCertificates()->first();
            
            if ($firstCertificate) {
                InstructorCardCertificate::create([
                    'instructor_card_request_id' => $request->id,
                    'user_certificate_id' => $firstCertificate->id,
                ]);
                $message = 'Kimlik kartı durumu "Verildi" olarak güncellendi.';
                $isIssued = true;
                
                // Güncellenmiş sertifikaları al
                $request->refresh();
                $request->load('certificates.userCertificate.certificate');
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Kullanıcının sertifikası bulunamadı.'
                ], 400);
            }
        }
        
        // Güncellenmiş sertifikaları formatla
        $certificates = $request->certificates->map(function($cert) {
            $userCert = $cert->userCertificate;
            return [
                'id' => $userCert->id,
                'certificate_name' => $userCert->certificate->certificate_name ?? 'Sertifika adı bulunamadı',
                'certificate_code' => $userCert->certificate_code ?? 'Belirtilmemiş',
                'achievement_score' => $userCert->achievement_score ?? 0,
                'issuing_institution' => $userCert->issuing_institution ?? 'Belirtilmemiş',
                'acquisition_date' => $userCert->acquisition_date ? $userCert->acquisition_date->format('d.m.Y') : 'Belirtilmemiş',
            ];
        });
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'is_issued' => $isIssued,
            'certificates' => $certificates
        ]);
    }

    /**
     * Approve instructor card request
     */
    public function approve($id)
    {
        $request = InstructorCardRequest::findOrFail($id);
        
        $request->update([
            'status' => 'approved'
        ]);
        
        // Güncellenmiş request'i yeniden yükle
        $request->refresh();
        
        return response()->json([
            'success' => true,
            'message' => 'Başvuru başarıyla onaylandı.',
            'status' => $request->status
        ]);
    }

    /**
     * Reject instructor card request
     */
    public function reject($id)
    {
        $request = InstructorCardRequest::findOrFail($id);
        
        $request->update([
            'status' => 'rejected'
        ]);
        
        // Güncellenmiş request'i yeniden yükle
        $request->refresh();
        
        return response()->json([
            'success' => true,
            'message' => 'Başvuru başarıyla reddedildi.',
            'status' => $request->status
        ]);
    }

    /**
     * Increase user's request rights by excluding oldest request from count
     */
    public function increaseRequestRights($id)
    {
        $request = InstructorCardRequest::findOrFail($id);
        $userId = $request->user_id;
        
        // Kullanıcının en eski reddedilmiş başvurusunu bul ve sayıdan çıkar
        $oldestRejectedRequest = InstructorCardRequest::where('user_id', $userId)
            ->where('status', 'rejected')
            ->where('is_excluded_from_count', false)
            ->orderBy('created_at', 'asc')
            ->first();
        
        if ($oldestRejectedRequest) {
            // Başvuruyu sayıdan çıkar
            $oldestRejectedRequest->update([
                'is_excluded_from_count' => true
            ]);
            
            // Güncellenmiş kalan hak sayısını hesapla
            $maxRequestsPerUser = 3;
            $userTotalRequests = InstructorCardRequest::where('user_id', $userId)
                ->where('is_excluded_from_count', false)
                ->count();
            $remainingRequests = max(0, $maxRequestsPerUser - $userTotalRequests);
            
            return response()->json([
                'success' => true,
                'message' => 'Başvuru hakkı başarıyla arttırıldı.',
                'remaining_requests' => $remainingRequests
            ]);
        } else {
            // Reddedilmiş başvuru yoksa, en eski bekleyen başvuruyu sayıdan çıkar
            $oldestPendingRequest = InstructorCardRequest::where('user_id', $userId)
                ->where('status', 'pending')
                ->where('is_excluded_from_count', false)
                ->orderBy('created_at', 'asc')
                ->first();
            
            if ($oldestPendingRequest) {
                // Başvuruyu sayıdan çıkar
                $oldestPendingRequest->update([
                    'is_excluded_from_count' => true
                ]);
                
                // Güncellenmiş kalan hak sayısını hesapla
                $maxRequestsPerUser = 3;
                $userTotalRequests = InstructorCardRequest::where('user_id', $userId)
                    ->where('is_excluded_from_count', false)
                    ->count();
                $remainingRequests = max(0, $maxRequestsPerUser - $userTotalRequests);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Başvuru hakkı başarıyla arttırıldı.',
                    'remaining_requests' => $remainingRequests
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Sayıdan çıkarılabilecek başvuru bulunamadı.'
                ], 400);
            }
        }
    }
}

