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
use Laravel\Socialite\Facades\Socialite;
use Throwable;

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
     * Redirect the user to Google's OAuth consent screen.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl('https://asiaccreditation.com/auth/google/callback')
            ->stateless()
            ->redirect();
    }

    /**
     * Handle Google's OAuth callback and ask for user confirmation.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl('https://asiaccreditation.com/auth/google/callback')
                ->stateless()
                ->user();
        } catch (Throwable $e) {
            \Log::error('Google giriş hatası', [
                'error' => $e->getMessage(),
                'timestamp' => now(),
            ]);

            return redirect()->route('login')
                ->withErrors(['email' => 'Google ile giriş yapılamadı. Lütfen tekrar deneyin.']);
        }

        if (!$googleUser->getEmail()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google hesabınızdan email bilgisi alınamadı.']);
        }

        // Kullanıcı verilerini session'a kaydet
        $userData = [
            'id' => $googleUser->getId(),
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'avatar' => $googleUser->getAvatar(),
        ];

        $request->session()->put('pending_google_user', $userData);

        return redirect()->route('google.confirm');
    }

    /**
     * Show Google account details before logging in.
     */
    public function showGoogleConfirm(Request $request)
    {
        $googleUser = $request->session()->get('pending_google_user');

        if (!$googleUser) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google oturumu bulunamadı. Lütfen tekrar deneyin.']);
        }

        $existingUser = User::where('google_id', $googleUser['id'])
            ->orWhere('email', $googleUser['email'])
            ->exists();

        return view('auth.google-confirm', [
            'googleUser' => $googleUser,
            'existingUser' => $existingUser,
        ]);
    }

    /**
     * Create or connect the user only after explicit confirmation.
     */
    public function confirmGoogleLogin(Request $request)
    {
        $googleUser = $request->session()->pull('pending_google_user');

        if (!$googleUser) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google oturumu süresi doldu. Lütfen tekrar deneyin.']);
        }

        $user = $this->findOrCreateGoogleUser($googleUser);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($user->role === 'admin' ? 'admin.dashboard' : 'user.dashboard');
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
     * Register a new user from the login page.
     */
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'gsm' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'password' => 'required|min:6|confirmed',
        ], [
            'full_name.required' => 'Ad soyad gereklidir.',
            'email.required' => 'Email adresi gereklidir.',
            'email.email' => 'Geçerli bir email adresi giriniz.',
            'email.unique' => 'Bu email adresi ile kayıtlı bir kullanıcı var.',
            'gsm.required' => 'Telefon numarası gereklidir.',
            'birth_date.required' => 'Doğum tarihi gereklidir.',
            'birth_date.date' => 'Geçerli bir doğum tarihi giriniz.',
            'password.required' => 'Şifre gereklidir.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.',
            'password.confirmed' => 'Şifre onayı eşleşmiyor.',
        ]);

        $nameParts = preg_split('/\s+/', trim($request->full_name), 2);
        $name = $nameParts[0] ?? '';
        $surname = $nameParts[1] ?? '';

        $user = User::create([
            'name' => $name,
            'surname' => $surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => 'other',
            'birth_date' => $request->birth_date,
            'gsm' => $request->gsm,
            'register_number' => $this->generateUniqueRegisterNumber(),
            'point' => '0',
            'contact_info' => true,
            'profile_photo_url' => '',
            'role' => 'user',
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kaydınız başarıyla oluşturuldu.',
                'redirect' => route('user.dashboard')
            ]);
        }

        return redirect()->route('user.dashboard')->with('success', 'Kaydınız başarıyla oluşturuldu.');
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

            // Log mail gönderim denemesi
            \Log::info('Şifre sıfırlama maili gönderiliyor', [
                'user_id' => $user->id,
                'email' => $user->email,
                'reset_url' => $resetUrl,
                'timestamp' => now()
            ]);

            // Send email (senkron - queue kullanmadan)
            try {
                Mail::to($user->email)->sendNow(new PasswordResetMail($user, $resetUrl));

                // Log başarılı gönderim
                \Log::info('Şifre sıfırlama maili başarıyla gönderildi', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'timestamp' => now()
                ]);

                return back()->with('status', 'Şifre sıfırlama bağlantısı email adresinize gönderildi.');
            } catch (\Exception $e) {
                // Log hata
                \Log::error('Şifre sıfırlama maili gönderim hatası', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'timestamp' => now()
                ]);

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

        // Tüm çıkış işlemlerinde ana login sayfasına yönlendir
        return redirect()->route('login')->with('success', 'Başarıyla çıkış yaptınız!');
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

    /**
     * Generate a unique registration number.
     */
    private function generateUniqueRegisterNumber(): string
    {
        do {
            $registerNumber = str_pad((string) random_int(10000000, 99999999), 8, '0', STR_PAD_LEFT);
        } while (User::where('register_number', $registerNumber)->exists());

        return $registerNumber;
    }

    /**
     * Find an existing user or create a new one from confirmed Google data.
     */
    private function findOrCreateGoogleUser(array $googleUser): User
    {
        $user = User::where('google_id', $googleUser['id'])
            ->orWhere('email', $googleUser['email'])
            ->first();

        if ($user) {
            $user->forceFill([
                'google_id' => $user->google_id ?: $googleUser['id'],
                'profile_photo_url' => $user->profile_photo_url ?: ($googleUser['avatar'] ?? ''),
            ])->save();

            return $user;
        }

        $nameParts = preg_split('/\s+/', trim($googleUser['name'] ?: ''), 2);

        return User::create([
            'name' => $nameParts[0] ?: 'Google',
            'surname' => $nameParts[1] ?? 'Kullanıcısı',
            'email' => $googleUser['email'],
            'password' => Hash::make(Str::random(32)),
            'google_id' => $googleUser['id'],
            'gender' => 'other',
            'birth_date' => '1970-01-01',
            'gsm' => '',
            'register_number' => $this->generateUniqueRegisterNumber(),
            'point' => '0',
            'contact_info' => true,
            'profile_photo_url' => $googleUser['avatar'] ?? '',
            'role' => 'user',
            'is_active' => true,
        ]);
    }
}
