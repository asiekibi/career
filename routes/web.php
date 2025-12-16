<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PortalCompanyController;
use App\Http\Controllers\PortalStudentController;
use App\Http\Controllers\InstructorCardController;
use setasign\Fpdi\Tcpdf\Fpdi;


//login routes
Route::get('/', function () {
    return view('login');
});

// Auth routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');

    // Password reset routes
    Route::get('/forgot-password', 'showForgotPasswordForm')->name('password.request');
    Route::post('/forgot-password', 'sendResetLinkEmail')->name('password.email');
    Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
    Route::post('/reset-password', 'reset')->name('password.update');
});

// Admin routes - Just admin can access
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // UserController routes
    Route::controller(UserController::class)->group(function () {
        Route::get('/dashboard', 'studentList')->name('admin.dashboard');
        Route::post('/students/delete', 'destroy')->name('admin.students.destroy');
        Route::get('/students/create', 'create')->name('admin.students.create');
        Route::get('/students/{id}/edit', 'edit')->name('admin.students.edit');
        Route::put('/students/{id}', 'update')->name('admin.students.update');
        Route::post('/students', 'store')->name('admin.students.store');
        Route::get('/students', 'index')->name('admin.students');
        Route::get('/partner-companies', 'partnerCompanies')->name('admin.partner-companies');
        Route::post('/partner-companies/{id}/permission', 'updatePartnerPermission')->name('admin.partner-companies.permission');
                    Route::post('/partner-companies/{id}/remove', 'removePartnerCompany')->name('admin.partner-companies.remove');
                    Route::post('/company-requests/{id}/approve', 'approveCompanyRequest')->name('admin.company-requests.approve');
                    Route::post('/company-requests/{id}/reject', 'rejectCompanyRequest')->name('admin.company-requests.reject');
    });

    // Location routes
    Route::controller(LocationController::class)->group(function () {
        Route::get('/countries', 'getCountries')->name('countries');
        Route::get('/cities', 'getCities')->name('cities');
        Route::get('/cities-by-country', 'getCitiesByCountry')->name('cities-by-country');
        Route::get('/districts', 'getDistricts')->name('districts');
    });

    //admin.students.cvs
    Route::get('/cvs', [CvController::class, 'index'])->name('admin.cvs');

     //admin.certificates
    Route::controller(CertificateController::class)->group(function () {
        Route::post('/certificates', 'store')->name('admin.certificates.store');
        Route::get('/certificates', 'index')->name('admin.certificates');
        Route::get('/certificates/{id}/edit', 'edit')->name('admin.certificates.edit');
        Route::put('/certificates/{id}', 'update')->name('admin.certificates.update');
        Route::get('/certificates/{id}/download-template', 'downloadTemplate')->name('admin.certificates.download-template');
        
        //admin.add-certificate
        Route::get('/students/{id}/assign-certificate', 'getAssignCertificate')->name('admin.students.assign-certificate');
        Route::post('/students/{id}/assign-certificate', 'storeAssignCertificate')->name('admin.students.assign-certificate.store');
        Route::put('/students/certificate/{id}', 'updateAssignCertificate')->name('admin.students.certificate.update');
        Route::post('/students/remove-certificate', 'removeCertificate')->name('admin.students.remove-certificate');
        Route::get('/students/certificate/{id}/download', 'downloadCertificate')->name('admin.students.certificate.download');
        Route::get('/add-certificate/{id}', 'addCertificate')->name('admin.add-certificate');
    });

    //admin.badges
    Route::controller(BadgeController::class)->group(function () {
        Route::get('/badges', 'index')->name('admin.badges');
        Route::post('/badges', 'store')->name('admin.badges.store');
        Route::post('/badges/delete', 'destroy')->name('admin.badges.destroy');
        
        //admin.add-badge
        Route::get('/students/{id}/assign-badge', 'getAssignBadge')->name('admin.students.assign-badge');
        Route::post('/students/{id}/assign-badge', 'storeAssignBadge')->name('admin.students.assign-badge.store');
        Route::post('/students/remove-badge', 'removeBadge')->name('admin.students.remove-badge');
        Route::get('/add-badge/{id}', 'addBadge')->name('admin.add-badge');
    });

    //admin.instructor-card-requests
    Route::get('/instructor-card-requests', [InstructorCardController::class, 'index'])->name('admin.instructor-card-requests');
    Route::get('/instructor-card-requests/{id}', [InstructorCardController::class, 'show'])->name('admin.instructor-card-requests.show');
    Route::post('/instructor-card-requests/{id}/toggle-card-status', [InstructorCardController::class, 'toggleCardStatus'])->name('admin.instructor-card-requests.toggle-card-status');
    Route::post('/instructor-card-requests/{id}/approve', [InstructorCardController::class, 'approve'])->name('admin.instructor-card-requests.approve');
    Route::post('/instructor-card-requests/{id}/reject', [InstructorCardController::class, 'reject'])->name('admin.instructor-card-requests.reject');
    Route::post('/instructor-card-requests/{id}/increase-rights', [InstructorCardController::class, 'increaseRequestRights'])->name('admin.instructor-card-requests.increase-rights');
    
    //admin.job-listings
    Route::prefix('job-listings')->group(function () {
        Route::get('/', [\App\Http\Controllers\JobListingController::class, 'index'])->name('admin.job-listings.index');
        Route::get('/create', [\App\Http\Controllers\JobListingController::class, 'create'])->name('admin.job-listings.create');
        Route::post('/', [\App\Http\Controllers\JobListingController::class, 'store'])->name('admin.job-listings.store');
        Route::get('/{id}/edit', [\App\Http\Controllers\JobListingController::class, 'edit'])->name('admin.job-listings.edit');
        Route::put('/{id}', [\App\Http\Controllers\JobListingController::class, 'update'])->name('admin.job-listings.update');
        Route::delete('/{id}', [\App\Http\Controllers\JobListingController::class, 'destroy'])->name('admin.job-listings.destroy');
    });

  
});

// User routes - Just user can access
Route::prefix('user')->middleware(['auth', 'role:user'])->controller(UserController::class)->group(function () {
    //user pages
    Route::get('/dashboard', 'userDashboard')->name('user.dashboard');

    // Profile photo update / User routes
    Route::post('/profile-photo', 'updateProfilePhoto')->name('user.profile-photo');
    
    // Carier page / User routes
    Route::get('/carier-sequence', 'carierSequence')->name('user.carier-sequence');
    
    // Job listings page / User routes
    Route::get('/job-listings', 'jobListings')->name('user.job-listings.index');
    
    // Instructor card request / User routes
    Route::get('/instructor-card-request', [InstructorCardController::class, 'create'])->name('user.instructor-card-request.create');
    Route::post('/instructor-card-request', [InstructorCardController::class, 'store'])->name('user.instructor-card-request.store');
   
    // Show routes / User routes
    Route::get('/cv/{id}', 'showCv')->name('user.cv.show');

    // Create routes / User routes
    Route::post('/experience', 'storeExperience')->name('user.experience.store');
    Route::post('/education', 'storeEducation')->name('user.education.store');
    Route::post('/ability', 'storeAbility')->name('user.ability.store');
    Route::post('/language', 'storeLanguage')->name('user.language.store');
   
    // Delete routes / User routes
    Route::post('/delete-experience', 'deleteExperience')->name('user.delete-experience');
    Route::post('/delete-education', 'deleteEducation')->name('user.delete-education');
    Route::post('/delete-ability', 'deleteAbility')->name('user.delete-ability');
    Route::post('/delete-language', 'deleteLanguage')->name('user.delete-language');

    // update profile
    Route::post('/user/update-profile', 'updateProfile')->name('user.update-profile');
    
    // Certificate download route for user
    Route::get('/certificate/{id}/download', [CertificateController::class, 'downloadCertificate'])->name('user.certificate.download');
});

// Portal routes - prefix ile grupla
Route::prefix('student-portal')->middleware('portal.auth')->controller(PortalStudentController::class)->group(function () {
    Route::get('/', 'showPortalLogin')->name('portal-login');
    Route::post('/search', 'searchCertificate')->name('portal.search');
    Route::get('/student-cv/{userId}', 'showStudentCv')->name('portal.student.cv');
    // Kariyer sıralaması ve partner firma route'ları kaldırıldı
});

// Public certificate download route (for portals and users)
Route::get('/certificate/{id}/download', [CertificateController::class, 'downloadCertificate'])->name('certificate.download');

// Public company request form (no auth required)
Route::get('/company-request', function () {
    return view('company-request-form');
})->name('company-request.form');

Route::post('/company-request', [PortalCompanyController::class, 'storeCompanyRequest'])->name('company-request.store');

// Company Portal routes - prefix ile grupla
Route::prefix('company-portal')->middleware('portal.auth')->controller(PortalCompanyController::class)->group(function () {
    Route::post('/login', 'login')->name('company-portal.login');
    Route::post('/search', 'searchCertificate')->name('company-portal.search');
    Route::get('/main', 'showMain')->name('company-portal.main');
    Route::get('/student-cv/{userId}', 'showStudentCv')->name('company-portal.student.cv');
    Route::get('/career-sequence', 'careerSequence')->name('company-portal.career-sequence');
    Route::get('/partner-company', 'partnerCompany')->name('company-portal.partner-company');
    Route::post('/partner-company', 'storePartnerCompany')->name('company-portal.partner-company.store');
});
