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
            'full_name' => 'nullable|string|max:255',
            'register_no' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:50'
        ]);

        if (!$request->full_name && !$request->register_no) {
            return response()->json([
                'success' => false,
                'message' => 'Lütfen ad soyad veya sertifika numarasını giriniz.'
            ]);
        }

        $query = UserCertificate::query();

        if ($request->register_no) {
            $query->where('register_no', $request->register_no);
        }

        if ($request->full_name) {
            // Ad soyadı ayır
            $nameParts = explode(' ', trim($request->full_name), 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

            $query->whereHas('user', function($q) use ($firstName, $lastName) {
                $q->where('name', 'LIKE', $firstName . '%');
                if ($lastName) {
                    $q->where('surname', 'LIKE', $lastName . '%');
                }
            });
        }

        // search by register_no and user name
        $userCertificate = $query->with(['user.cvs.experiences', 'user.cvs.educations', 'user.cvs.abilities', 'user.cvs.languages', 'user.userBadges.badge', 'user.userCertificates.certificate'])
            ->first();

        if (!$userCertificate) {
            return response()->json([
                'success' => false,
                'message' => 'Sertifika kaydına ulaşılamamıştır. Lütfen ad soyad ve register numarasını kontrol edin.'
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
            'searched_certificate_id' => $userCertificate->id, // Sorgulanan sertifika ID'sini kaydet
            'is_company_auth' => $isCompanyAuth,
            'company_tax_number' => $request->tax_number,
            'company_name' => $companyName,
            'login_type' => $loginType
        ]);

        // Kullanıcının diğer sertifikalarını al (sorgulanan hariç)
        $otherCertificates = UserCertificate::where('user_id', $userCertificate->user->id)
            ->where('id', '!=', $userCertificate->id)
            ->with('certificate')
            ->orderBy('acquisition_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'student' => $userCertificate->user,
            'searched_certificate' => $userCertificate->load('certificate'),
            'other_certificates' => $otherCertificates,
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
        
        // Sorgulanan sertifika ID'sini session'dan al
        $searchedCertificateId = session('searched_certificate_id');
        $searchedCertificate = null;
        $otherCertificates = collect();
        
        if ($searchedCertificateId) {
            // Sorgulanan sertifikayı al
            $searchedCertificate = UserCertificate::where('id', $searchedCertificateId)
                ->where('user_id', $userId)
                ->with('certificate')
                ->first();
            
            // Diğer sertifikaları al (sorgulanan hariç)
            $otherCertificates = UserCertificate::where('user_id', $userId)
                ->where('id', '!=', $searchedCertificateId)
                ->with('certificate')
                ->orderBy('acquisition_date', 'desc')
                ->get();
        } else {
            // Eğer sorgulanan sertifika yoksa tüm sertifikaları göster
            $otherCertificates = $student->userCertificates;
        }

        return view('portal-user.user-cv', compact('student', 'loginType', 'companyName', 'isCompanyAuth', 'searchedCertificate', 'otherCertificates'));
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

    /**
     * Store custom user-uploaded certificate
     */
    public function storeCustomCertificate(Request $request)
    {
        try {
            $studentId = session('student_id') ?? auth()->id();
            if (!$studentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Oturum bulunamadı. Lütfen tekrar giriş yapın.'
                ], 401);
            }

            $request->validate([
                'custom_certificate_name' => 'required|string|max:255',
                'issuing_institution' => 'required|string|max:255',
                'achievement_score' => 'nullable|integer|min:0|max:100',
                'acquisition_date' => 'required|date',
                'certificate_file' => 'required|file|mimes:pdf,jpeg,png,jpg,gif|max:5120', // Max 5MB
                'register_no' => 'nullable|string|max:50',
                'password' => 'nullable|string|max:255',
                'content1' => 'nullable|string|max:1000',
                'content2' => 'nullable|string|max:1000',
            ], [
                'custom_certificate_name.required' => 'Sertifika adı alanı zorunludur.',
                'issuing_institution.required' => 'Veren kurum alanı zorunludur.',
                'acquisition_date.required' => 'Veriliş tarihi alanı zorunludur.',
                'certificate_file.required' => 'Sertifika dosyası zorunludur.',
                'certificate_file.mimes' => 'Yalnızca PDF veya resim (JPEG, PNG, GIF) formatları yüklenebilir.',
                'certificate_file.max' => 'Dosya boyutu en fazla 5MB olabilir.',
            ]);

            $file = $request->file('certificate_file');
            $filename = 'custom_cert_' . $studentId . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Move file to storage public user_uploaded_certificates
            $file->move(public_path('user_uploaded_certificates'), $filename);
            $filePath = 'user_uploaded_certificates/' . $filename;

            $score = $request->achievement_score ?? 0;

            $registerNo = null;

            // Eğer formdan bir register_no gönderildiyse
            if ($request->filled('register_no')) {
                $candidate = trim($request->input('register_no'));
                $exists = UserCertificate::where('register_no', $candidate)->exists();
                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bu sertifika numarası (Register No) zaten sistemde kayıtlı. Lütfen kontrol edin veya boş bırakarak sistemin otomatik numara atamasını sağlayın.',
                    ], 422);
                }
                $registerNo = $candidate;
            }

            // PDF ise ve formdan gelmediyse veya çakışıyorsa, içeriği tarayıp register no bulmaya çalış
            if (!$registerNo && strtolower($file->getClientOriginalExtension()) === 'pdf') {
                try {
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf = $parser->parseFile(public_path($filePath));
                    $text = $pdf->getText();

                    // Regex to find "Sertifika No", "Register No", "Registration No", "Belge No", "Lisans No", vb.
                    // Case-insensitive, supporting Turkish characters.
                    $pattern = '/(?:sertifika\s*(?:no(?:m)?|numaras[ıi]|kodu)|register\s*no(?:m)?|registration\s*no|belge\s*(?:no|numaras[ıi])|certificate\s*(?:no|code|id))\s*[:\-#\.]*\s*([a-zA-Z0-9\-\/]{4,30})/iu';
                    
                    if (preg_match($pattern, $text, $matches)) {
                        $candidate = trim($matches[1]);
                        // En az 4, en fazla 30 karakter ve sistemde benzersiz olmalı
                        if (strlen($candidate) >= 4 && strlen($candidate) <= 30) {
                            $exists = UserCertificate::where('register_no', $candidate)->exists();
                            if (!$exists) {
                                $registerNo = $candidate;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // PDF parse edilemez veya şifreli ise hata vermeden sessizce devam et
                    \Illuminate\Support\Facades\Log::warning('PDF parsing failed for file: ' . $filePath . '. Error: ' . $e->getMessage());
                }
            }

            // Eğer hala bulunamadıysa sistemde olmayan benzersiz bir register_no üret
            if (!$registerNo) {
                do {
                    $registerNo = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
                    $exists = UserCertificate::where('register_no', $registerNo)->exists();
                } while ($exists);
            }

            // Şifre belirle (formdan gönderildiyse onu kullan, yoksa 6 haneli rastgele şifre üret)
            $certificatePassword = $request->filled('password') ? $request->input('password') : rand(100000, 999999);

            $userCertificate = UserCertificate::create([
                'user_id' => $studentId,
                'certificate_id' => null, // Custom certificate doesn't have system template
                'custom_certificate_name' => $request->custom_certificate_name,
                'issuing_institution' => $request->issuing_institution,
                'register_no' => $registerNo,
                'achievement_score' => $score,
                'success_score' => $score,
                'acquisition_date' => $request->acquisition_date,
                'file_path' => $filePath,
                'password' => $certificatePassword,
                'content1' => $request->content1,
                'content2' => $request->content2,
            ]);

            // Update user point
            $user = User::findOrFail($studentId);
            $user->point = $user->point + $score;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Sertifika başarıyla yüklendi ve profilinize eklendi!',
                'data' => $userCertificate
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sertifika kaydedilirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete custom user-uploaded certificate
     */
    public function deleteCustomCertificate(Request $request)
    {
        try {
            $studentId = session('student_id') ?? auth()->id();
            if (!$studentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Oturum bulunamadı. Lütfen tekrar giriş yapın.'
                ], 401);
            }

            $request->validate([
                'id' => 'required|integer|exists:user_certificates,id'
            ]);

            $userCertificate = UserCertificate::where('id', $request->id)
                ->where('user_id', $studentId)
                ->firstOrFail();

            // Ensure this is actually a custom uploaded certificate
            if (!$userCertificate->file_path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu sertifikayı silme yetkiniz yoktur.'
                ], 403);
            }

            // Delete physical file
            $filePath = public_path($userCertificate->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Update user point
            $user = User::findOrFail($studentId);
            $user->point = max(0, $user->point - ($userCertificate->success_score ?? 0));
            $user->save();

            // Delete database record
            $userCertificate->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sertifika başarıyla silindi.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sertifika silinirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Parse certificate using Gemini API
     */
    public function parseCustomCertificate(Request $request)
    {
        try {
            $studentId = session('student_id') ?? auth()->id();
            if (!$studentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Oturum bulunamadı. Lütfen tekrar giriş yapın.'
                ], 401);
            }

            $request->validate([
                'certificate_file' => 'required|file|mimes:pdf,jpeg,png,jpg,gif|max:5120', // Max 5MB
            ], [
                'certificate_file.required' => 'Sertifika dosyası zorunludur.',
                'certificate_file.mimes' => 'Yalnızca PDF veya resim (JPEG, PNG, GIF) formatları yüklenebilir.',
                'certificate_file.max' => 'Dosya boyutu en fazla 5MB olabilir.',
            ]);

            $geminiKey = config('services.gemini.key');
            $file = $request->file('certificate_file');
            $fileExtension = strtolower($file->getClientOriginalExtension());

            // 1. If Gemini API key is missing, trigger direct fallback for PDF, or error out
            if (!$geminiKey) {
                if ($fileExtension === 'pdf') {
                    $localParsed = $this->parsePDFLocally($file->getRealPath(), $studentId);
                    if ($localParsed) {
                        // Check if it's a wrong document (both register_no and certificate_name are empty)
                        if (empty($localParsed['register_no']) && empty($localParsed['certificate_name'])) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Sertifikanızı tekrar kontrol edip tekrar deneyiniz.',
                                'data' => null
                            ]);
                        }

                        // Check recipient name match
                        $student = \App\Models\User::find($studentId);
                        if ($student) {
                            $recipientName = isset($localParsed['recipient_name']) ? trim($localParsed['recipient_name']) : '';
                            
                            $normalize = function($str) {
                                $str = mb_strtolower($str, 'UTF-8');
                                $tr = ['ı' => 'i', 'ş' => 's', 'ğ' => 'g', 'ü' => 'u', 'ö' => 'o', 'ç' => 'c', 'i̇' => 'i'];
                                return strtr($str, $tr);
                            };

                            $normRecipient = $normalize($recipientName);
                            $studentFirstName = explode(' ', trim($student->name ?? ''))[0];
                            $normFirstName = $normalize($studentFirstName);

                            if (empty($normRecipient) || !str_contains($normRecipient, $normFirstName)) {
                                return response()->json([
                                    'success' => false,
                                    'message' => 'İsim uyuşmazlığınız bulunmakta. Lütfen sertifikanın sizin adınıza düzenlendiğinden emin olun.',
                                    'data' => null
                                ]);
                            }
                        }

                        return response()->json([
                            'success' => true,
                            'message' => 'Sertifika yerel olarak taranmıştır (Lütfen alanların doğruluğunu kontrol edin).',
                            'data' => $localParsed
                        ]);
                    }
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Yapay zeka taraması devre dışı (API Key bulunamadı). Bilgileri kendiniz girebilirsiniz.',
                    'data' => null
                ]);
            }

            $fileContent = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = $file->getMimeType();
            if ($mimeType === 'image/jpg') {
                $mimeType = 'image/jpeg';
            }

            $prompt = "Analyze this certificate file. It can be in English or Turkish. Search for fields carefully and extract:
1. \"register_no\": Look for a certificate number, register number, registration number, license number, certificate ID, or document number.
   - In Turkish, look for label variations like: 'Sertifika No', 'Sertifika Numarası', 'Belge No', 'Belge Numarası', 'Kayıt No', 'Kayıt Numarası', 'Lisans No', 'No:'.
   - It is typically a number, alphanumeric code, or code separated by hyphens/slashes (e.g., '102290', 'KA72', '123456').
2. \"certificate_name\": Look for the title of the certificate, course name, or certification title.
   - Examples: 'Pilates Trainer', 'Third Degree Certificate', 'Web Geliştirme', 'Katılım Belgesi', 'Başarı Sertifikası'.
   - Extract the main course/topic name if it is a general certificate.
3. \"issuing_institution\": Look for the organization, academy, company, school, or university that issued the certificate.
   - In Turkish, look for label variations like: 'Veren Kurum', 'Düzenleyen Kurum', 'Kurum', 'Okul', 'Akademi'.
   - Common examples: 'Avrasya Spor Enstitüsü', 'ASI', 'Australia Sports Institute & Fitness Academy', 'Udemy', 'Coursera'.
4. \"acquisition_date\": Look for the date of issue, completion date, or certification date.
   - If a date range is given (e.g. '28-29-30 November Pratical 2025' or '28.11.2025 - 30.11.2025'), use the end date/last date of the range (e.g., '2025-11-30') as the acquisition date.
   - Convert it to \"YYYY-MM-DD\" format. If no date is found, return null.
5. \"content1\": Look for the first extra text line printed at the bottom or body of the certificate, which typically describes the hours of theory or lessons (e.g. '17 Hours Theoretical', '16 Hours Theoretical', or similar syllabus description).
6. \"content2\": Look for the second extra text line printed at the bottom or body of the certificate, which typically describes the practical training dates or completion date range (e.g. '28-29-30 November Pratical 2025', '28-29-30 November Practical 2025', or similar date/place details).
7. \"recipient_name\": Look for the name of the person (student/recipient) to whom the certificate was issued (e.g. 'İrem Öğrünç').

Return ONLY a valid JSON object matching this schema. Do not include markdown code block formatting (do not wrap with ```json). Do not include any other text or explanation.
Schema:
{
  \"register_no\": \"string or null\",
  \"certificate_name\": \"string or null\",
  \"issuing_institution\": \"string or null\",
  \"acquisition_date\": \"string (YYYY-MM-DD) or null\",
  \"content1\": \"string or null\",
  \"content2\": \"string or null\",
  \"recipient_name\": \"string or null\"
}";

            $models = ['gemini-2.5-flash', 'gemini-2.0-flash', 'gemini-3.5-flash', 'gemini-flash-latest'];
            $success = false;
            $data = null;
            $isQuotaExceeded = false;
            $isServiceUnavailable = false;

            foreach ($models as $model) {
                try {
                    $response = \Illuminate\Support\Facades\Http::timeout(15)
                        ->retry(2, 100)
                        ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$geminiKey}", [
                            'contents' => [
                                [
                                    'parts' => [
                                        ['text' => $prompt],
                                        ['inlineData' => ['mimeType' => $mimeType, 'data' => $fileContent]]
                                    ]
                                ]
                            ]
                        ]);

                    if ($response->successful()) {
                        $result = $response->json();
                        $extractedText = trim($result['candidates'][0]['content']['parts'][0]['text'] ?? '');
                        
                        // Clean markdown formatting if returned
                        $extractedText = preg_replace('/^```json\s*/i', '', $extractedText);
                        $extractedText = preg_replace('/```$/', '', $extractedText);
                        $extractedText = trim($extractedText);

                        $parsedData = json_decode($extractedText, true);

                        if (json_last_error() === JSON_ERROR_NONE) {
                            $data = $parsedData;
                            $success = true;
                            break; // Success! Break loop
                        }
                    } else {
                        if ($response->status() === 429) {
                            $isQuotaExceeded = true;
                        } elseif ($response->status() === 503) {
                            $isServiceUnavailable = true;
                        }
                        \Illuminate\Support\Facades\Log::warning("Gemini model {$model} failed: " . $response->status() . " - " . $response->body());
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning("Gemini model {$model} exception: " . $e->getMessage());
                }
            }

            if ($success && $data) {
                // 1. Tarama başarısızlığı veya yanlış belge kontrolü
                if (empty($data['register_no']) && empty($data['certificate_name'])) {
                    // Try PDF fallback before failing
                    if ($fileExtension === 'pdf') {
                        $localParsed = $this->parsePDFLocally($file->getRealPath(), $studentId);
                        if ($localParsed) {
                            // Check if it's a wrong document
                            if (empty($localParsed['register_no']) && empty($localParsed['certificate_name'])) {
                                return response()->json([
                                    'success' => false,
                                    'message' => 'Sertifikanızı tekrar kontrol edip tekrar deneyiniz.',
                                    'data' => null
                                ]);
                            }

                            // Check recipient name match
                            $student = \App\Models\User::find($studentId);
                            if ($student) {
                                $recipientName = isset($localParsed['recipient_name']) ? trim($localParsed['recipient_name']) : '';
                                
                                $normalize = function($str) {
                                    $str = mb_strtolower($str, 'UTF-8');
                                    $tr = ['ı' => 'i', 'ş' => 's', 'ğ' => 'g', 'ü' => 'u', 'ö' => 'o', 'ç' => 'c', 'i̇' => 'i'];
                                    return strtr($str, $tr);
                                };

                                $normRecipient = $normalize($recipientName);
                                $studentFirstName = explode(' ', trim($student->name ?? ''))[0];
                                $normFirstName = $normalize($studentFirstName);

                                if (empty($normRecipient) || !str_contains($normRecipient, $normFirstName)) {
                                    return response()->json([
                                        'success' => false,
                                        'message' => 'İsim uyuşmazlığınız bulunmakta. Lütfen sertifikanın sizin adınıza düzenlendiğinden emin olun.',
                                        'data' => null
                                    ]);
                                }
                            }

                            return response()->json([
                                'success' => true,
                                'message' => 'Sertifika yerel olarak taranmıştır (Lütfen alanların doğruluğunu kontrol edin).',
                                'data' => $localParsed
                            ]);
                        }
                    }
                    return response()->json([
                        'success' => false,
                        'message' => 'Sertifikanızı tekrar kontrol edip tekrar deneyiniz.',
                        'data' => null
                    ]);
                }

                // 2. İsim uyuşmazlığı kontrolü
                $student = \App\Models\User::find($studentId);
                if ($student) {
                    $recipientName = isset($data['recipient_name']) ? trim($data['recipient_name']) : '';
                    
                    // Normalizasyon fonksiyonu (Türkçe karakter duyarlı küçük harfe çevirme ve temizleme)
                    $normalize = function($str) {
                        $str = mb_strtolower($str, 'UTF-8');
                        $tr = ['ı' => 'i', 'ş' => 's', 'ğ' => 'g', 'ü' => 'u', 'ö' => 'o', 'ç' => 'c', 'i̇' => 'i'];
                        return strtr($str, $tr);
                    };

                    $normRecipient = $normalize($recipientName);
                    
                    // Öğrencinin ilk adını ve soyadını ayırarak kontrol edelim
                    $studentFirstName = explode(' ', trim($student->name ?? ''))[0];
                    $normFirstName = $normalize($studentFirstName);

                    // Alıcı adı boşsa veya öğrencinin adı alıcı adı içinde geçmiyorsa
                    if (empty($normRecipient) || !str_contains($normRecipient, $normFirstName)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'İsim uyuşmazlığınız bulunmakta. Lütfen sertifikanın sizin adınıza düzenlendiğinden emin olun.',
                            'data' => null
                        ]);
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Sertifika başarıyla tarandı!',
                    'data' => $data
                ]);
            }

            // Fallback to local parsing for PDF if AI failed/quota exceeded
            if ($fileExtension === 'pdf') {
                $localParsed = $this->parsePDFLocally($file->getRealPath(), $studentId);
                if ($localParsed) {
                    // Check if it's a wrong document
                    if (empty($localParsed['register_no']) && empty($localParsed['certificate_name'])) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Sertifikanızı tekrar kontrol edip tekrar deneyiniz.',
                            'data' => null
                        ]);
                    }

                    // Check recipient name match
                    $student = \App\Models\User::find($studentId);
                    if ($student) {
                        $recipientName = isset($localParsed['recipient_name']) ? trim($localParsed['recipient_name']) : '';
                        
                        $normalize = function($str) {
                            $str = mb_strtolower($str, 'UTF-8');
                            $tr = ['ı' => 'i', 'ş' => 's', 'ğ' => 'g', 'ü' => 'u', 'ö' => 'o', 'ç' => 'c', 'i̇' => 'i'];
                            return strtr($str, $tr);
                        };

                        $normRecipient = $normalize($recipientName);
                        $studentFirstName = explode(' ', trim($student->name ?? ''))[0];
                        $normFirstName = $normalize($studentFirstName);

                        if (empty($normRecipient) || !str_contains($normRecipient, $normFirstName)) {
                            return response()->json([
                                'success' => false,
                                'message' => 'İsim uyuşmazlığınız bulunmakta. Lütfen sertifikanın sizin adınıza düzenlendiğinden emin olun.',
                                'data' => null
                            ]);
                        }
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'Sertifika yerel tarayıcı ile taranmıştır (Lütfen alanların doğruluğunu kontrol edin).',
                        'data' => $localParsed
                    ]);
                }
            }

            // Return custom errors for quota/service availability
            if ($isQuotaExceeded) {
                return response()->json([
                    'success' => false,
                    'message' => 'Yapay zeka tarama servisinin kotası dolmuştur. Lütfen bilgileri kendiniz doldurarak devam ediniz.',
                    'data' => null
                ]);
            }

            if ($isServiceUnavailable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Yapay zeka servisi şu anda yoğun. Lütfen bilgileri kendiniz doldurarak devam ediniz.',
                    'data' => null
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Sertifikanızı tekrar kontrol edip tekrar deneyiniz.',
                'data' => null
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gemini certificate parser exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Sertifikanızı tekrar kontrol edip tekrar deneyiniz.',
                'data' => null
            ], 500);
        }
    }

    /**
     * Parse PDF locally using Smalot\PdfParser
     */
    private function parsePDFLocally(string $path, int $studentId): ?array
    {
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($path);
            $text = $pdf->getText();
            
            if (empty(trim($text))) {
                return null;
            }

            $localData = [
                'register_no' => null,
                'certificate_name' => null,
                'issuing_institution' => null,
                'acquisition_date' => null,
                'content1' => null,
                'content2' => null,
                'recipient_name' => null
            ];

            // Split into lines for analysis
            $lines = array_filter(array_map('trim', explode("\n", $text)));

            // 1. Extract Register No
            $pattern = '/(?:sertifika\s*(?:no(?:m)?|numaras[ıi]|kodu)|register\s*no(?:m)?|registration\s*no|belge\s*(?:no|numaras[ıi])|certificate\s*(?:no|code|id))\s*[:\-#\.]*\s*([a-zA-Z0-9\-\/]{4,30})/iu';
            if (preg_match($pattern, $text, $matches)) {
                $localData['register_no'] = trim($matches[1]);
            }

            // 2. Extract recipient name (check student name in database)
            $student = \App\Models\User::find($studentId);
            if ($student) {
                $normalize = function($str) {
                    $str = mb_strtolower($str, 'UTF-8');
                    $tr = ['ı' => 'i', 'ş' => 's', 'ğ' => 'g', 'ü' => 'u', 'ö' => 'o', 'ç' => 'c', 'i̇' => 'i'];
                    return strtr($str, $tr);
                };

                $normText = $normalize($text);
                $studentNameNorm = $normalize($student->name);
                $studentSurnameNorm = $normalize($student->surname);
                
                $fullNameNorm = trim($studentNameNorm . ' ' . $studentSurnameNorm);
                $firstNameNorm = explode(' ', trim($studentNameNorm))[0];

                if (str_contains($normText, $fullNameNorm) || str_contains($normText, $firstNameNorm)) {
                    $localData['recipient_name'] = trim($student->name . ' ' . $student->surname);
                }
            }

            // 3. Extract content1 (e.g. "17 Hours Theoretical", "15 Saat Teorik")
            foreach ($lines as $line) {
                if (preg_match('/(?:hours?|saat|theoretical|theory|teorik)/iu', $line)) {
                    // Make sure it doesn't contain "practical" / "pratik"
                    if (!preg_match('/(?:practical|pratical|pratik)/iu', $line)) {
                        if (strlen($line) > 5 && strlen($line) < 100) {
                            $localData['content1'] = $line;
                            break;
                        }
                    }
                }
            }

            // 4. Extract content2 (e.g. "28-29-30 November Pratical 2025")
            foreach ($lines as $line) {
                if (preg_match('/(?:practical|pratical|pratik)/iu', $line) || preg_match('/\b\d{1,2}[-\/\.]\d{1,2}[-\/\.]\d{4}\b/u', $line)) {
                    if (strlen($line) > 5 && strlen($line) < 100) {
                        $localData['content2'] = $line;
                        break;
                    }
                }
            }

            // 5. Extract Certificate Name (check smart quotes first, then check common course name)
            if (preg_match('/(?:“|")([^”"]+)(?:”|")/iu', $text, $matches)) {
                $localData['certificate_name'] = trim($matches[1]);
            } else {
                foreach ($lines as $line) {
                    if (preg_match('/^(?:sertifikas[ıi]|kat[ıi]l[ıi]m belgesi|başar[ıi] belgesi|uzmanl[ıi]k belgesi|certificate|diploma)\b/iu', $line) ||
                        preg_match('/\b(?:trainer|coach|antrenörlük|uzmanl[ıi]k|pilates|fitness)\s*(?:eğitimi|kursu|program[ıi]|sertifikas[ıi]|certificate|course)\b/iu', $line)) {
                        if (!preg_match('/(?:coordinated|coordinated by|tarafından)/iu', $line)) {
                            if (strlen($line) > 5 && strlen($line) < 70) {
                                $localData['certificate_name'] = $line;
                                break;
                            }
                        }
                    }
                }
            }

            // 6. Extract Issuing Institution
            if (preg_match('/(?:coordinated by|organized by|issued by|tarafından düzenlenen)\s*(?:the\s*)?([a-zA-Z\s]{5,100})/iu', $text, $matches)) {
                $localData['issuing_institution'] = trim($matches[1]);
            } else {
                foreach ($lines as $line) {
                    if (preg_match('/(?:institute|school|enstitü|akademi|academy|university|üniversite)/iu', $line)) {
                        if (strlen($line) > 5 && strlen($line) < 80) {
                            $localData['issuing_institution'] = $line;
                            break;
                        }
                    }
                }
            }

            // 7. Extract Acquisition Date
            if ($localData['content2']) {
                $localData['acquisition_date'] = $this->parseDateFromString($localData['content2']);
            }
            if (!$localData['acquisition_date']) {
                $localData['acquisition_date'] = $this->parseDateFromString($text);
            }

            // If we found at least register_no or certificate_name, return as success
            if ($localData['register_no'] || $localData['certificate_name']) {
                return $localData;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Local PDF parse failure: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Parse date from a string
     */
    private function parseDateFromString(string $str): ?string
    {
        // 1. Check DD.MM.YYYY, DD/MM/YYYY, DD-MM-YYYY
        $pattern1 = '/\b(\d{1,2})[\.\/\-](\d{1,2})[\.\/\-](\d{4})\b/u';
        if (preg_match($pattern1, $str, $matches)) {
            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $year = $matches[3];
            return "{$year}-{$month}-{$day}";
        }

        // 2. Check YYYY-MM-DD
        $pattern2 = '/\b(\d{4})[\.\/\-](\d{1,2})[\.\/\-](\d{1,2})\b/u';
        if (preg_match($pattern2, $str, $matches)) {
            $year = $matches[1];
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $day = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
            return "{$year}-{$month}-{$day}";
        }

        // 3. Check English month format (e.g. 28-29-30 November Practical 2025)
        $months = [
            'january' => '01', 'february' => '02', 'march' => '03', 'april' => '04', 'may' => '05', 'june' => '06',
            'july' => '07', 'august' => '08', 'september' => '09', 'october' => '10', 'november' => '11', 'december' => '12'
        ];
        
        $monthsTr = [
            'ocak' => '01', 'şubat' => '02', 'mart' => '03', 'nisan' => '04', 'mayıs' => '05', 'haziran' => '06',
            'temmuz' => '07', 'ağustos' => '08', 'eylül' => '09', 'ekim' => '10', 'kasım' => '11', 'aralık' => '12'
        ];

        // Search for Month and Year
        foreach ($months as $name => $num) {
            if (mb_stripos($str, $name) !== false) {
                if (preg_match('/\b(20\d{2}|19\d{2})\b/u', $str, $yMatches)) {
                    $year = $yMatches[1];
                    if (preg_match('/(\d{1,2})\s*-\s*(\d{1,2})\s*-\s*(\d{1,2})\s+' . $name . '/iu', $str, $dMatches)) {
                        $day = str_pad($dMatches[3], 2, '0', STR_PAD_LEFT);
                    } elseif (preg_match('/(\d{1,2})\s+' . $name . '/iu', $str, $dMatches)) {
                        $day = str_pad($dMatches[1], 2, '0', STR_PAD_LEFT);
                    } else {
                        $day = '01';
                    }
                    return "{$year}-{$num}-{$day}";
                }
            }
        }

        foreach ($monthsTr as $name => $num) {
            if (mb_stripos($str, $name) !== false) {
                if (preg_match('/\b(20\d{2}|19\d{2})\b/u', $str, $yMatches)) {
                    $year = $yMatches[1];
                    if (preg_match('/(\d{1,2})\s*-\s*(\d{1,2})\s*-\s*(\d{1,2})\s+' . $name . '/iu', $str, $dMatches)) {
                        $day = str_pad($dMatches[3], 2, '0', STR_PAD_LEFT);
                    } elseif (preg_match('/(\d{1,2})\s+' . $name . '/iu', $str, $dMatches)) {
                        $day = str_pad($dMatches[1], 2, '0', STR_PAD_LEFT);
                    } else {
                        $day = '01';
                    }
                    return "{$year}-{$num}-{$day}";
                }
            }
        }

        return null;
    }
}


