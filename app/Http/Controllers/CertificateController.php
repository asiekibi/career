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
use setasign\Fpdi\Tcpdf\Fpdi;

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
            'type' => 'required|in:ders,kurs',
            'course' => 'array',
            'template_file' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $templatePath = null;

        // Sertifika şablonu yükleme - PDF'i HTML'e çevir ve orijinal PDF'i de sakla
        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');
            
            // PDF dosyasını base64 encode et
            $pdfContent = file_get_contents($file->getRealPath());
            $base64Pdf = base64_encode($pdfContent);
            
            // HTML şablonu oluştur (PDF.js ile PDF görüntüleme)
            $htmlContent = $this->convertPdfToHtml($base64Pdf, $file->getClientOriginalName());
            
            // Klasör yoksa oluştur
            $templatesDir = storage_path('app/public/certificates/templates/');
            if (!file_exists($templatesDir)) {
                mkdir($templatesDir, 0755, true);
            }
            
            // HTML dosyasını kaydet
            $htmlFileName = time() . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.html';
            $htmlPath = $templatesDir . $htmlFileName;
            file_put_contents($htmlPath, $htmlContent);
            
            // Orijinal PDF dosyasını da kaydet (indirme için)
            $pdfFileName = time() . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf';
            $pdfPath = $templatesDir . $pdfFileName;
            file_put_contents($pdfPath, $pdfContent);
            
            $templatePath = 'storage/certificates/templates/' . $htmlFileName;
        }

        // Sertifikayı oluştur
        $certificate = Certificate::create([
            'certificate_name' => $request->certificate_name,
            'type' => $request->type,
            'template_path' => $templatePath,
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
            'register_no' => 'nullable|string|max:255',
            'content1' => 'nullable|string',
            'content2' => 'nullable|string',
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
            'register_no' => $request->register_no,
            'content1' => $request->content1,
            'content2' => $request->content2,
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

    /**
     * download certificate
     */
    public function downloadCertificate($id)
    {
        $userCertificate = UserCertificate::with(['user', 'certificate'])->findOrFail($id);
        
        // Sertifika şablonunu veritabanından al
        $certificate = $userCertificate->certificate;
        
        if (!$certificate) {
            return redirect()->back()->with('error', 'Sertifika bulunamadı!');
        }
        
        // Sertifika tipine göre şablon dosyasını belirle
        $templatePath = null;
        $certificateType = $certificate->type ?? 'ders'; // Varsayılan olarak 'ders'
        
        // Eğer type boşsa veya yanlışsa, certificate_name'e göre kontrol et
        if (empty($certificateType) || $certificateType === 'ders') {
            $certificateName = strtolower($certificate->certificate_name ?? '');
            if (strpos($certificateName, 'kurs') !== false) {
                $certificateType = 'kurs';
                \Log::info('Certificate Type Override - Detected kurs from certificate_name', [
                    'certificate_name' => $certificate->certificate_name,
                    'original_type' => $certificate->type,
                    'new_type' => $certificateType,
                ]);
            }
        }
        
        // Önce sertifika tipine göre özel şablon dosyasını kontrol et
        $typeBasedTemplatePaths = [
            'ders' => [
                storage_path('app/public/certificates/templates/ders_template.pdf'),
                storage_path('app/public/certificates/templates/ders_tasarım.pdf'),
                public_path('ders_tasarım.pdf'),
                public_path('ders_template.pdf'),
            ],
            'kurs' => [
                storage_path('app/public/certificates/templates/kurs_template.pdf'),
                storage_path('app/public/certificates/templates/kurs_tasarım.pdf'),
                public_path('kurs_tasarım.pdf'),
                public_path('kurs_template.pdf'),
            ],
        ];
        
        // Tip bazlı şablon dosyalarını kontrol et
        if (isset($typeBasedTemplatePaths[$certificateType])) {
            foreach ($typeBasedTemplatePaths[$certificateType] as $path) {
                if (file_exists($path)) {
                    $templatePath = $path;
                    break;
                }
            }
        }
        
        // Eğer tip bazlı şablon bulunamadıysa, sertifikanın kendi template_path'ini kullan
        if (!$templatePath && $certificate->template_path) {
            // HTML dosya adından PDF dosya adını oluştur
            $htmlFileName = basename($certificate->template_path);
            $pdfFileName = str_replace('.html', '.pdf', $htmlFileName);
            
            // PDF dosyasının tam yolu
            $templatePath = storage_path('app/public/certificates/templates/' . $pdfFileName);
        }
        
        // Eğer hala şablon bulunamadıysa, varsayılan şablonu kullan
        if (!$templatePath || !file_exists($templatePath)) {
            $templatePath = public_path('tasarım.pdf');
            
            if (!file_exists($templatePath)) {
                return redirect()->back()->with('error', 'Sertifika şablonu bulunamadı!');
            }
        }
        
        // FPDI ile PDF oluştur
        $pdf = new Fpdi();
        
        // Template PDF'i ekle
        $pageCount = $pdf->setSourceFile($templatePath);
        $tplId = $pdf->importPage(1);
        
        // PDF boyutunu al
        $size = $pdf->getTemplateSize($tplId);
        
        // Sayfa boyutuna göre sayfa ekle
        if ($size['width'] > $size['height']) {
            $pdf->AddPage('L', [$size['width'], $size['height']]);
        } else {
            $pdf->AddPage('P', [$size['width'], $size['height']]);
        }
        
        $pdf->useTemplate($tplId, 0, 0, $size['width'], $size['height'], true);
        
        // Kullanıcı adı soyadı (Türkçe karakter desteği ile)
        $userName = $userCertificate->user->name . ' ' . $userCertificate->user->surname;
        
        // UnifrakturMaguntia fontunu yükle (Register No için)
        // TCPDF_FONTS static sınıfını kullan
        $unifrakturFontName = null;
        $unifrakturFontPaths = [
            public_path('fonts/UnifrakturMaguntia-Regular.ttf'),
            public_path('fonts/UnifrakturMaguntia.ttf'),
        ];
        
        foreach ($unifrakturFontPaths as $fontPath) {
            if (file_exists($fontPath)) {
                try {
                    $unifrakturFontName = \TCPDF_FONTS::addTTFfont($fontPath, 'TrueTypeUnicode', '', 96);
                    if ($unifrakturFontName) {
                        break; // Font başarıyla yüklendi
                    }
                } catch (\Exception $e) {
                    continue; // Bu dosya yüklenemedi, bir sonrakini dene
                }
            }
        }
        
        // Playwrite USA Traditional Guides fontunu yükle (kullanıcı adı için)
        $playwriteFontName = null;
        $fontPaths = [
            public_path('fonts/PlaywriteUSATraditionalGuides-Regular.ttf'),
            public_path('fonts/PlaywriteUSATraditionalGuides.ttf'),
            public_path('fonts/Playwrite-USA-Traditional-Guides.ttf'),
            public_path('fonts/PlaywriteUSATraditionalGuides-Regular.otf'),
        ];
        
        foreach ($fontPaths as $fontPath) {
            if (file_exists($fontPath)) {
                try {
                    $playwriteFontName = \TCPDF_FONTS::addTTFfont($fontPath, 'TrueTypeUnicode', '', 96);
                    if ($playwriteFontName) {
                        break; // Font başarıyla yüklendi
                    }
                } catch (\Exception $e) {
                    continue; // Bu dosya yüklenemedi, bir sonrakini dene
                }
            }
        }
        
        // Sağ üste Register No ve içerik ekle (siyah renkte)
        $pdf->SetTextColor(0, 0, 0); // Siyah renk
        // Register No'yu user_certificates tablosundan al (register_no alanından)
        $registerNo = $userCertificate->register_no ?? '';
        // Eğer register_no boşsa, certificate_code'u kullan
        if (empty($registerNo)) {
            $registerNo = $userCertificate->certificate_code ?? 'CERT-' . str_pad($userCertificate->id, 6, '0', STR_PAD_LEFT);
        }
        // Content1 ve Content2'yi user_certificates tablosundan al
        $content1 = $userCertificate->content1 ?? $certificate->content ?? '16 Hours Theoretical';
        $content2 = $userCertificate->content2 ?? '';
        
        \Log::info('Certificate Download - Method: downloadCertificate', [
            'certificate_id' => $certificate->id,
            'certificate_type' => $certificateType,
            'certificate_name' => $certificate->certificate_name,
        ]);
        
        if ($certificateType === 'kurs') {
            $registerY = 25; // Template içindeki beyaz alanın başlangıcından 25mm aşağıda (daha aşağı)
            $content1Y = $registerY + 3; // Register No'nun 3mm altında (ders: 5mm)
            $content2Y = $content1Y + 3; // İçerik 1'in 3mm altında
            $tarihY = $content2Y + 3; // İçerik 2'nin 3mm altında (ders: 5mm)
            $userNameYPercentage = null; // Kurs için yüzde kullanmayacağız
            $userNameYDefault = null; // Kurs için varsayılan değer kullanmayacağız
            
            // LOG: Kurs tipi için kullanılan değerler
            \Log::info('Certificate Download - Kurs Type Values', [
                'registerY' => $registerY,
                'content1Y' => $content1Y,
                'content2Y' => $content2Y,
                'tarihY' => $tarihY,
                'template_path' => $templatePath,
            ]);
        } else {
            // Ders sertifikaları için mevcut pozisyonlar
            $registerY = 30; // Üstten 30mm aşağıda
            $content1Y = $registerY + 5; // Register No'nun 5mm altında
            $content2Y = $content1Y + 5; // İçerik 1'in 5mm altında
            $tarihY = $content2Y + 5; // İçerik 2'nin 5mm altında
            $userNameYPercentage = 0.48; // Sayfanın %48'i kadar yukarıdan
            $userNameYDefault = 145; // Varsayılan değer
            
            // LOG: Ders tipi için kullanılan değerler
            \Log::info('Certificate Download - Ders Type Values', [
                'registerY' => $registerY,
                'content1Y' => $content1Y,
                'content2Y' => $content2Y,
                'tarihY' => $tarihY,
            ]);
        }
        
        // Register No: yazısı - UnifrakturMaguntia fontu kullan
        if ($unifrakturFontName) {
            $pdf->SetFont($unifrakturFontName, '', 12);
        } else {
            $pdf->SetFont('dejavuserif', '', 12); // Fallback font
        }
        // Sağdan mesafe - sağa yaklaştırmak için mesafeyi azalt
        $rightMargin = 30; // Sağdan 30mm mesafe (50 -> 30, daha sağa)
        $registerX = $size['width'] - $rightMargin; // Sağdan mesafeli X pozisyonu
        $cellWidth = 70; // Cell genişliği (60 -> 70, daha geniş alan)
        
        // LOG: Register No pozisyonu
        \Log::info('Certificate Download - Register No Position', [
            'registerX' => $registerX - $cellWidth,
            'registerY' => $registerY,
            'cellWidth' => $cellWidth,
            'size_width' => $size['width'],
            'size_height' => $size['height'] ?? 'N/A',
        ]);
        
        $pdf->SetXY($registerX - $cellWidth, $registerY);
        $pdf->Cell($cellWidth, 10, 'Register No: ' . $registerNo, 0, 0, 'R');
        
        // İçerik 1 yazısı (Register No'nun altında)
        $pdf->SetFont('dejavuserif', 'I', 8); // Küçük italik font (10 -> 8, italik)
        $pdf->SetXY($registerX - $cellWidth, $content1Y);
        $pdf->Cell($cellWidth, 10, $content1, 0, 0, 'R');
        
        // İçerik 2 yazısı (İçerik 1'in altında)
        if (!empty($content2)) {
            $pdf->SetFont('dejavuserif', 'I', 8); // Küçük italik font
            $pdf->SetXY($registerX - $cellWidth, $content2Y);
            $pdf->Cell($cellWidth, 10, $content2, 0, 0, 'R');
        }
        
        // Tarih yazısı kaldırıldı
        
        $pdf->SetTextColor(0, 0, 0); // Siyah renk
        
        if ($playwriteFontName) {
            $pdf->SetFont($playwriteFontName, '', 32);
        } else {
            $pdf->SetFont('dejavuserif', 'I', 32);
        }
        
        // PDF boyutuna göre orantılı Y koordinatı hesapla
        $x = 0; // X = 0, Cell ile ortalanacak
        
        // PDF yüksekliğine göre orantılı Y koordinatı - sertifika tipine göre
        if ($certificateType === 'kurs') {
            // Kurs sertifikaları için: sayfa yüksekliğinden Cell yüksekliği ve margin çıkararak biraz aşağıya yerleştir
            // Cell yüksekliği 10mm, yazının alt kenarının sayfanın altından belirli bir mesafe yukarıda olması için
            $cellHeight = 10; // Cell yüksekliği
            $bottomMargin = 115; // Sayfanın altından margin (120 -> 115, çok az daha aşağıya iner)
            if (isset($size['height']) && $size['height'] > 0) {
                $y = $size['height'] - $cellHeight - $bottomMargin; // Yazının alt kenarı sayfanın altından 115mm yukarıda (biraz daha aşağı)
            } else {
                $y = 172; // Varsayılan değer (A4 için yaklaşık 297mm yükseklik, 297 - 10 - 115 = 172)
            }
        } else {
            // Ders sertifikaları için yüzde hesaplaması - daha yukarı pozisyon
            if (isset($size['height']) && $size['height'] > 0) {
                $y = $size['height'] * 0.48; // Sayfanın %48'i kadar yukarıdan (0.60 -> 0.48, daha yukarı)
            } else {
                $y = 143; // Varsayılan değer (A4 için yaklaşık 297mm yükseklik, %48 = 142.56)
            }
        }
        
        $pdf->SetXY($x, $y);
        // Türkçe karakter desteği için UTF-8 string kullan
        $pdf->Cell($size['width'], 10, $userName, 0, 1, 'C');
        
        // PDF metadata (downloadPirusApp metodundaki gibi)
        $pdf->SetCreator('Kariyer Sistemi');
        $pdf->SetAuthor('Kariyer Sistemi');
        $pdf->SetTitle($certificate->certificate_name ?? 'Sertifika');
        $pdf->SetSubject('Sertifika');
        
        // PDF şifre koruması ayarları (template'i ekledikten sonra)
        $password = '12345'; // Şifre
        $pdf->SetProtection(
            ['print', 'modify', 'copy', 'annot-forms'],
            $password,
            $password
        );
        
        // PDF'i output olarak döndür
        $pdfContent = $pdf->Output('', 'S');
        
        // Dosya adını oluştur (downloadPirusApp metodundaki gibi basit)
        $fileName = 'Sertifika_' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $userCertificate->user->name) . '_' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $userCertificate->user->surname) . '.pdf';
        
        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Cache-Control', 'private, must-revalidate');
    }

    /**
     * PDF'i HTML formatına çevir
     */
    private function convertPdfToHtml($base64Pdf, $originalFileName)
    {
        $html = '<!DOCTYPE html>
        <html lang="tr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Sertifika Şablonu - ' . htmlspecialchars($originalFileName) . '</title>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
                <style>
                    body {
                        margin: 0;
                        padding: 20px;
                        background: #f5f5f5;
                        font-family: Arial, sans-serif;
                    }
                    .pdf-container {
                        max-width: 100%;
                        margin: 0 auto;
                        background: white;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                        padding: 20px;
                    }
                    .pdf-page {
                        position: relative;
                        margin: 0 auto;
                        background: white;
                    }
                    .pdf-page canvas {
                        display: block;
                        width: 100%;
                        height: auto;
                    }
                    .loading {
                        text-align: center;
                        padding: 40px;
                        color: #666;
                    }
                </style>
            </head>
            <body>
                <div class="pdf-container">
                    <div id="pdf-pages" class="loading">PDF yükleniyor...</div>
                </div>

                <script>
                    // PDF.js worker ayarı
                    pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";
                    
                    // Base64 PDF verisi
                    const base64Pdf = "' . $base64Pdf . '";
                    
                    // Base64\'ü Uint8Array\'e çevir
                    const pdfData = atob(base64Pdf);
                    const pdfBytes = new Uint8Array(pdfData.length);
                    for (let i = 0; i < pdfData.length; i++) {
                        pdfBytes[i] = pdfData.charCodeAt(i);
                    }
                    
                    // PDF\'i yükle ve render et
                    pdfjsLib.getDocument({ data: pdfBytes }).promise.then(function(pdfDoc) {
                        const pdfPages = document.getElementById("pdf-pages");
                        pdfPages.innerHTML = "";
                        
                        // Tüm sayfaları render et
                        for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
                            pdfDoc.getPage(pageNum).then(function(page) {
                                const viewport = page.getViewport({ scale: 1.5 });
                                
                                const pageDiv = document.createElement("div");
                                pageDiv.className = "pdf-page";
                                pageDiv.style.width = viewport.width + "px";
                                pageDiv.style.height = viewport.height + "px";
                                
                                const canvas = document.createElement("canvas");
                                const context = canvas.getContext("2d");
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                
                                const renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };
                                
                                page.render(renderContext).promise.then(function() {
                                    pageDiv.appendChild(canvas);
                                    pdfPages.appendChild(pageDiv);
                                });
                            });
                        }
                    }).catch(function(error) {
                        document.getElementById("pdf-pages").innerHTML = "<div class=\'loading\'>PDF yüklenirken bir hata oluştu: " + error.message + "</div>";
                    });
                </script>
            </body>
        </html>';

        return $html;
    }

    /**
     * Sertifika şablonunu PDF formatında indir
     */
    public function downloadTemplate($id)
    {
        $certificate = Certificate::findOrFail($id);
        
        if (!$certificate->template_path) {
            return redirect()->route('admin.certificates')
                ->with('error', 'Sertifika şablonu bulunamadı.');
        }

        // HTML dosya adından PDF dosya adını oluştur
        $htmlFileName = basename($certificate->template_path);
        $pdfFileName = str_replace('.html', '.pdf', $htmlFileName);
        
        // Önce orijinal PDF dosyasını kontrol et
        $pdfPath = storage_path('app/public/certificates/templates/' . $pdfFileName);
        
        if (file_exists($pdfPath)) {
            // Orijinal PDF dosyasını oku
            $pdfContent = file_get_contents($pdfPath);
            
            // Orijinal PDF dosya adını al
            $originalFileName = $certificate->certificate_name . '_template.pdf';
            $originalFileName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $originalFileName);
            
            // PDF'i response olarak döndür
            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $originalFileName . '"')
                ->header('Content-Length', strlen($pdfContent))
                ->header('Cache-Control', 'private, must-revalidate');
        }
        
        // Eğer PDF dosyası yoksa, HTML'den base64 çıkar (geriye dönük uyumluluk için)
        $htmlPath = storage_path('app/public/certificates/templates/' . $htmlFileName);
        
        if (!file_exists($htmlPath)) {
            return redirect()->route('admin.certificates')
                ->with('error', 'Şablon dosyası bulunamadı.');
        }

        // HTML dosyasını oku
        $htmlContent = file_get_contents($htmlPath);
        
        // HTML içindeki base64 PDF verisini çıkar
        // Regex'i daha esnek hale getir - tek tırnak veya çift tırnak olabilir
        preg_match('/const\s+base64Pdf\s*=\s*["\']([^"\']+)["\'];/', $htmlContent, $matches);
        
        if (!isset($matches[1]) || empty($matches[1])) {
            // Alternatif regex dene
            preg_match('/base64Pdf\s*=\s*["\']([^"\']+)["\']/', $htmlContent, $matches);
        }
        
        if (!isset($matches[1]) || empty($matches[1])) {
            return redirect()->route('admin.certificates')
                ->with('error', 'PDF verisi bulunamadı. HTML dosyası bozuk olabilir.');
        }

        $base64Pdf = $matches[1];
        
        // Base64'ü decode et
        $pdfContent = base64_decode($base64Pdf, true);
        
        if ($pdfContent === false) {
            return redirect()->route('admin.certificates')
                ->with('error', 'PDF verisi decode edilemedi.');
        }
        
        // Orijinal PDF dosya adını al
        $originalFileName = $certificate->certificate_name . '_template.pdf';
        $originalFileName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $originalFileName);
        
        // PDF'i response olarak döndür
        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $originalFileName . '"')
            ->header('Content-Length', strlen($pdfContent))
            ->header('Cache-Control', 'private, must-revalidate');
    }

}