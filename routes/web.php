<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PortalController;


//login routes
Route::get('/', function () {
    return view('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password reset routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');

// Admin routes - Just admin can access
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    //admin pages
    Route::get('/dashboard', [UserController::class, 'studentList'])->name('admin.dashboard');
    Route::post('/students/delete', [UserController::class, 'destroy'])->name('admin.students.destroy');
   
    Route::get('/students/create', [UserController::class, 'create'])->name('admin.students.create');
    Route::get('/students/{id}/edit', [UserController::class, 'edit'])->name('admin.students.edit');
    Route::put('/students/{id}', [UserController::class, 'update'])->name('admin.students.update');
    Route::post('/students', [UserController::class, 'store'])->name('admin.students.store');

    // Location routes
    Route::get('/cities', [LocationController::class, 'getCities'])->name('cities');
    Route::get('/districts', [LocationController::class, 'getDistricts'])->name('districts');

    //admin.students.cvs
    Route::get('/cvs', [CvController::class, 'index'])->name('admin.cvs');

    //admin.certificates
    Route::post('/certificates', [CertificateController::class, 'store'])->name('admin.certificates.store');
    Route::get('/certificates', [CertificateController::class, 'index'])->name('admin.certificates');

    //admin.badges
    Route::get('/badges', [BadgeController::class, 'index'])->name('admin.badges');
    Route::post('/badges', [BadgeController::class, 'store'])->name('admin.badges.store');
    Route::post('/badges/delete', [BadgeController::class, 'destroy'])->name('admin.badges.destroy');

    //admin.add-certificate
    Route::get('/students/{id}/assign-certificate', [CertificateController::class, 'getAssignCertificate'])->name('admin.students.assign-certificate');
    Route::post('/students/{id}/assign-certificate', [CertificateController::class, 'storeAssignCertificate'])->name('admin.students.assign-certificate.store');
    Route::post('/students/remove-certificate', [CertificateController::class, 'removeCertificate'])->name('admin.students.remove-certificate');
    Route::get('/add-certificate/{id}', [CertificateController::class, 'addCertificate'])->name('admin.add-certificate');

    //admin.add-badge
    Route::get('/students/{id}/assign-badge', [BadgeController::class, 'getAssignBadge'])->name('admin.students.assign-badge');
    Route::post('/students/{id}/assign-badge', [BadgeController::class, 'storeAssignBadge'])->name('admin.students.assign-badge.store');
    Route::post('/students/remove-badge', [BadgeController::class, 'removeBadge'])->name('admin.students.remove-badge');
    Route::get('/add-badge/{id}', [BadgeController::class, 'addBadge'])->name('admin.add-badge');

    
    Route::get('/students', [UserController::class, 'index'])->name('admin.students');
  



     
     


   

});

// User routes - Just user can access
Route::prefix('user')->middleware(['auth', 'role:user'])->group(function () {

    //user pages
    Route::get('/dashboard', [UserController::class, 'userDashboard'])->name('user.dashboard');

    // Profile photo update / User routes
    Route::post('/profile-photo', [UserController::class, 'updateProfilePhoto'])->name('user.profile-photo');
    
   // Carier page / User routes
    Route::get('/carier-sequence', [UserController::class, 'carierSequence'])->name('user.carier-sequence');
   
    // Show routes / User routes
   Route::get('/cv/{id}', [UserController::class, 'showCv'])->name('user.cv.show');

    // Create routes / User routes
   Route::post('/experience', [UserController::class, 'storeExperience'])->name('user.experience.store');
   Route::post('/education', [UserController::class, 'storeEducation'])->name('user.education.store');
   Route::post('/ability', [UserController::class, 'storeAbility'])->name('user.ability.store');
   Route::post('/language', [UserController::class, 'storeLanguage'])->name('user.language.store');
   
    // Delete routes / User routes
    Route::post('/delete-experience', [UserController::class, 'deleteExperience'])->name('user.delete-experience');
    Route::post('/delete-education', [UserController::class, 'deleteEducation'])->name('user.delete-education');
    Route::post('/delete-ability', [UserController::class, 'deleteAbility'])->name('user.delete-ability');
    Route::post('/delete-language', [UserController::class, 'deleteLanguage'])->name('user.delete-language');

    // update profile
Route::post('/user/update-profile', [UserController::class, 'updateProfile'])->name('user.update-profile');
});



// Portal routes - prefix ile grupla
Route::prefix('portal')->group(function () {
    Route::get('/', [PortalController::class, 'showPortalLogin'])->name('portal-login');
    Route::post('/search', [PortalController::class, 'searchCertificate'])->name('portal.search');
    Route::get('/student-cv/{userId}', [PortalController::class, 'showStudentCv'])->name('portal.student.cv');
    Route::get('/career-sequence', [PortalController::class, 'careerSequence'])->name('portal.career-sequence');
});
