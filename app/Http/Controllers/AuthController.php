<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use App\Models\Cv;
use App\Models\UserBadge;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * User login
     */
    public function login(Request $request)
    {
        // Form validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            // 'g-recaptcha-response' => 'required',
        ], [
            'email.email' => 'Geçerli bir email adresi giriniz.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.',
            // 'g-recaptcha-response.required' => 'Lütfen reCAPTCHA doğrulamasını tamamlayın.',
        ]);

        // Verify reCAPTCHA
        // $recaptchaSecret = config('services.recaptcha.secret_key');
        // $recaptchaResponse = $request->input('g-recaptcha-response');
        
        // if ($recaptchaSecret) {
        //     $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
        //     $responseData = json_decode($verifyResponse);
            
        //     if (!$responseData->success || $responseData->score < 0.5) {
        //         return back()->withErrors([
        //             'g-recaptcha-response' => 'reCAPTCHA doğrulaması başarısız. Lütfen tekrar deneyin.',
        //         ])->withInput($request->except('password'));
        //     }
        // }

        // Login credentials
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember-me');

        // Validate user
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Redirect based on user role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('user.dashboard');
            }
        }

        // Invalid login - invalid login message
        return back()->withErrors([
            'email' => 'Giriş başarısız. Email veya şifre hatalı.',
        ])->withInput($request->except('password'));
    }

    
    /**
     * Send password reset email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Email adresi gereklidir.',
            'email.email' => 'Geçerli bir email adresi giriniz.',
            'email.exists' => 'Bu email adresi sistemde kayıtlı değil.'
        ]);

        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            // Create token
            $token = Str::random(64);
            
            // Save token to database
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );
            
            // Create reset URL
            $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);
            
            // Send email
            try {
                Mail::to($user->email)->send(new PasswordResetMail($user, $resetUrl));
                
                return back()->with('status', 'Şifre sıfırlama bağlantısı email adresinize gönderildi.');
            } catch (\Exception $e) {
                return back()->withErrors(['email' => 'Email gönderilemedi. Lütfen tekrar deneyin.']);
            }
        }

        return back()->withErrors(['email' => 'Kullanıcı bulunamadı.']);
    }

    /**
     * User logout
     */
    public function logout(Request $request)
    {
        // Company kullanıcısı mı kontrol et (logout'tan önce)
        $isCompanyAuth = session('is_company_auth', false);
        $loginType = session('login_type', '');
        $isCompany = Auth::check() && Auth::user() && Auth::user()->role === 'company';
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Eğer company portal'dan çıkış yapıldıysa portal-login'e yönlendir
        if ($isCompanyAuth || $loginType === 'company' || $isCompany) {
            return redirect()->route('company-portal-login')->with('success', 'Başarıyla çıkış yaptınız!');
        }
        
        return redirect('/login')->with('success', 'Başarıyla çıkış yaptınız!');
    }


    /**
     * Password reset form
     */
    public function showForgotPasswordForm()
    {
        return view('forgot-password');
    }


    /**
     * Password reset form
     */
    public function showResetForm(Request $request, $token = null)
    {
        // check token
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($token, $passwordReset->token)) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Geçersiz veya süresi dolmuş token.']);
        }

        // check token expiration time
        if (now()->diffInMinutes($passwordReset->created_at) > 60) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Token süresi dolmuş. Lütfen yeni bir şifre sıfırlama talebinde bulunun.']);
        }

        return view('reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Password reset
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.required' => 'Email adresi gereklidir.',
            'email.email' => 'Geçerli bir email adresi giriniz.',
            'password.required' => 'Şifre gereklidir.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.',
            'password.confirmed' => 'Şifre onayı eşleşmiyor.'
        ]);

        // Token'ı kontrol et
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['email' => 'Geçersiz veya süresi dolmuş token.']);
        }

        // Token'ın süresini kontrol et (60 dakika)
        if (now()->diffInMinutes($passwordReset->created_at) > 60) {
            return back()->withErrors(['email' => 'Token süresi dolmuş. Lütfen yeni bir şifre sıfırlama talebinde bulunun.']);
        }

        // Kullanıcıyı bul ve şifresini güncelle
        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            // Token'ı sil
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return redirect()->route('login')->with('status', 'Şifreniz başarıyla sıfırlandı. Giriş yapabilirsiniz.');
        }

        return back()->withErrors(['email' => 'Kullanıcı bulunamadı.']);
    }
}
