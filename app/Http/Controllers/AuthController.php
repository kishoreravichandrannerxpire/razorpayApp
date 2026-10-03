<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Show the CUSTOMER login form.
     * - Guests always see the customer login page.
     * - Authenticated customers go to the store.
     * - Authenticated admins go to the admin dashboard.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.login')
                    ->with('error', 'You are signed in as an admin. Use the Admin Portal below.');
            }
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Show the ADMIN login form.
     * - Guests always see the admin login page.
     * - Authenticated admins go to the admin dashboard.
     * - Authenticated customers go to the store (they shouldn't be here).
     */
    public function showAdminLoginForm()
    {
        if (Auth::check()) {
            // Customer accidentally hit /admin/login → send them to their own login portal
            if (! Auth::user()->isAdmin()) {
                return redirect()->route('login')
                    ->with('error', 'You are signed in as a customer. Use the Customer Login below.');
            }
            return redirect()->route('admin.dashboard');
        }

        // Always show admin login — never customer login
        return view('auth.admin-login');
    }

    /**
     * Handle customer login.
     * If customer does not exist, automatically registers them.
     * Preserves and restores cart across logins.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:15'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Existing user: verify credentials
            if (Hash::check($request->password, $user->password)) {
                $sessionCart = session()->get('cart', []);

                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();

                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard')
                        ->with('success', "Welcome back, Admin {$user->name}!");
                }

                // Restore/merge user's saved DB cart with current session cart
                $this->syncUserCartAfterLogin($user, $sessionCart);

                return redirect()->intended(route('home'))
                    ->with('success', "Welcome back, {$user->name}!");
            }

            return back()->withInput($request->only('email', 'remember'))
                ->withErrors(['password' => 'Incorrect password. Please try again.']);
        }

        // User does not exist: prompt them to register
        return back()->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'No account found with this email. Please click "Create New Account / Register" below.']);
    }

    /**
     * Handle dedicated admin login.
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Invalid admin email or password.']);
        }

        if (!$user->isAdmin()) {
            return back()->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Access denied. This login portal is strictly reserved for administrators.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', "Welcome to the Admin Portal, {$user->name}!");
    }

    /**
     * Show explicit registration form.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        return view('auth.register');
    }

    /**
     * Handle explicit user registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:15', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        $sessionCart = session()->get('cart', []);
        Auth::login($user);
        $request->session()->regenerate();

        $this->syncUserCartAfterLogin($user, $sessionCart);

        return redirect()->route('home')
            ->with('success', "Registration successful! Welcome, {$user->name}.");
    }

    /**
     * Log the user out of the application.
     * Redirects Admin to /admin/login and Customer to /login.
     * Retains the customer's cart actions both in database and in session.
     */
    public function logout(Request $request)
    {
        $wasAdmin = Auth::check() && Auth::user()->isAdmin();
        $user = Auth::user();

        // If customer, save their cart to the carts table before logging out
        $cart = session()->get('cart', []);
        if (!$wasAdmin && $user && !empty($cart)) {
            Cart::where('user_id', $user->id)->delete();
            foreach ($cart as $productId => $item) {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // For customers: re-populate the cart in the renewed session so cart items are NOT lost
        if (!$wasAdmin && !empty($cart)) {
            session()->put('cart', $cart);
        }

        if ($wasAdmin) {
            return redirect()->route('admin.login')
                ->with('success', 'Admin logged out successfully.');
        }

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully. Your cart items have been saved!');
    }

    /**
     * Helper to sync database cart with session cart upon login.
     */
    protected function syncUserCartAfterLogin(User $user, array $sessionCart): void
    {
        $dbCarts = Cart::where('user_id', $user->id)->with('product')->get();

        // Merge DB cart into session cart
        foreach ($dbCarts as $c) {
            if ($c->product && !isset($sessionCart[$c->product_id])) {
                $sessionCart[$c->product_id] = [
                    'id' => $c->product_id,
                    'name' => $c->product->product_name,
                    'price' => (float) $c->product->price,
                    'quantity' => $c->quantity,
                    'description' => $c->product->description,
                ];
            }
        }

        session()->put('cart', $sessionCart);

        // Sync session cart back to DB
        foreach ($sessionCart as $productId => $item) {
            Cart::updateOrCreate(
                ['user_id' => $user->id, 'product_id' => $productId],
                ['quantity' => $item['quantity']]
            );
        }
    }

    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP to the user's email.
     */
   public function sendOtp(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'No account found with this email address.']);
    }

    $otp = sprintf("%06d", mt_rand(100000, 999999));

    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $user->email],
        ['token' => $otp, 'created_at' => now()]
    );

    Mail::to($user->email)->send(new OtpMail($otp));

    session(['reset_email' => $user->email]);

    return redirect()->route('password.reset')
        ->with('success', 'A 6-digit OTP has been sent to your email address.');
}

    /**
     * Show the reset password & OTP verification form.
     */
    public function showResetPasswordForm(Request $request)
    {
        $email = session('reset_email', $request->query('email', ''));
        return view('auth.reset-password', compact('email'));
    }

    /**
     * Verify OTP and reset password for both customer and admin.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->withInput($request->only('email', 'otp'))
                ->withErrors(['otp' => 'No OTP request found for this email. Please request a new OTP.']);
        }

        // Verify expiration (15 minutes)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(15)->isPast()) {
            return back()->withInput($request->only('email', 'otp'))
                ->withErrors(['otp' => 'This OTP has expired. Please click Resend OTP to get a new code.']);
        }

        // Verify OTP match
        if (trim($resetRecord->token) !== trim($request->otp)) {
            return back()->withInput($request->only('email', 'otp'))
                ->withErrors(['otp' => 'Invalid OTP code. Please check and enter the 6-digit code correctly.']);
        }

        // Find user
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'User account not found.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete used token and clear reset session
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        session()->forget('reset_email');

        // Role-based redirection
        if ($user->isAdmin()) {
            return redirect()->route('admin.login')
                ->with('success', 'Admin password reset successfully! Please sign in with your new password.');
        }

        return redirect()->route('login')
            ->with('success', 'Your password has been reset successfully! Please sign in with your new password.');
    }

    /**
     * Resend a fresh OTP code (customer).
     */
    public function resendOtp(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'User not found.']);
    }

    $otp = sprintf("%06d", mt_rand(100000, 999999));

    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $user->email],
        ['token' => $otp, 'created_at' => now()]
    );

    Mail::to($user->email)->send(new OtpMail($otp));

    session(['reset_email' => $user->email]);

    return back()->with('success', 'A fresh OTP code has been sent to your email.');
}

    // ─────────────────────────────────────────────────────────────────────────
    // ADMIN-ONLY Password Reset Flow (completely separate from customer flow)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Show the admin forgot-password form.
     */
    public function showAdminForgotPasswordForm()
    {
        return view('auth.admin-forgot-password');
    }

    /**
     * Send OTP to the admin's email (validates the user is an admin).
     */
    public function sendAdminOtp(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !$user->isAdmin()) {
        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'No administrator account found with this email address.']);
    }

    $otp = sprintf("%06d", mt_rand(100000, 999999));

    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $user->email],
        ['token' => $otp, 'created_at' => now()]
    );

    Mail::to($user->email)->send(new OtpMail($otp));

    session(['admin_reset_email' => $user->email]);

    return redirect()->route('admin.password.reset')
        ->with('success', 'A 6-digit OTP has been sent to the admin email.');
}

    /**
     * Show the admin reset-password & OTP verification form.
     */
    public function showAdminResetPasswordForm(Request $request)
    {
        $email = session('admin_reset_email', $request->query('email', ''));
        return view('auth.admin-reset-password', compact('email'));
    }

    /**
     * Verify OTP and reset password for admin only.
     */
    public function resetAdminPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp'   => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->withInput($request->only('email', 'otp'))
                ->withErrors(['otp' => 'No OTP request found for this email. Please request a new OTP.']);
        }

        if (Carbon::parse($resetRecord->created_at)->addMinutes(15)->isPast()) {
            return back()->withInput($request->only('email', 'otp'))
                ->withErrors(['otp' => 'This OTP has expired. Please click Resend OTP to get a new code.']);
        }

        if (trim($resetRecord->token) !== trim($request->otp)) {
            return back()->withInput($request->only('email', 'otp'))
                ->withErrors(['otp' => 'Invalid OTP code. Please check and enter the 6-digit code correctly.']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->isAdmin()) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Administrator account not found.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        session()->forget('admin_reset_email');

        return redirect()->route('admin.login')
            ->with('success', 'Admin password reset successfully! Please sign in with your new password.');
    }

    /**
     * Resend a fresh OTP code (admin).
     */
    public function resendAdminOtp(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !$user->isAdmin()) {
        return back()->withErrors(['email' => 'Administrator account not found.']);
    }

    $otp = sprintf("%06d", mt_rand(100000, 999999));

    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $user->email],
        ['token' => $otp, 'created_at' => now()]
    );

    Mail::to($user->email)->send(new OtpMail($otp));

    session(['admin_reset_email' => $user->email]);

    return back()->with('success', 'A fresh OTP code has been sent to your email.');
}
}
