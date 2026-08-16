<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Customer login is OTP-only, no password (see project memory: customer-
 * auth-and-admin-driven-signup). One form doubles as signup + login: a
 * first-time mobile number self-registers here (name + mobile only, per
 * the customer-requirements draft); a returning number just gets an OTP —
 * "duplicate mobile routes straight into login, not a dead end".
 */
class OtpAuthController extends Controller
{
    public function showStart(): View
    {
        return view('Customer.auth.start');
    }

    public function start(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'mobile' => ['required', 'digits:10'],
            'alternate_mobile' => ['nullable', 'digits:10', 'different:mobile'],
        ]);

        $this->ensureNotRateLimited($request, $data['mobile'], 'customer-otp-request:', 5, 600);

        $user = User::where('mobile', $data['mobile'])->first();

        if ($user && $user->role !== 'customer') {
            return back()->withInput()->withErrors([
                'mobile' => 'This mobile number is already registered as a different account type.',
            ]);
        }

        if (! $user) {
            $user = User::create([
                'first_name' => $data['first_name'],
                'login_id' => User::generateLoginId(),
                'mobile' => $data['mobile'],
                'alternate_mobile' => $data['alternate_mobile'] ?? null,
                'role' => 'customer',
                'isActive' => true,
                'otp' => (string) random_int(100000, 999999),
                'password' => Hash::make(Str::random(40)),
                'email_verified_at' => now(),
            ]);
        }

        if (! $user->isActive) {
            return back()->withInput()->withErrors([
                'mobile' => 'Your account has been deactivated. Contact support.',
            ]);
        }

        $this->issueOtp($user);

        $request->session()->put('customer_otp_mobile', $user->mobile);

        return redirect()->route('customer.otp.show');
    }

    public function showVerify(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('customer_otp_mobile')) {
            return redirect()->route('customer.login');
        }

        return view('Customer.auth.verify', [
            'mobile' => $request->session()->get('customer_otp_mobile'),
            'devOtp' => app()->environment('local') ? '123456' : null,
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $mobile = $request->session()->get('customer_otp_mobile');

        abort_unless($mobile, 419);

        $request->validate(['otp' => ['required', 'digits:6']]);

        $throttleKey = 'customer-otp-verify:'.$mobile;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors(['otp' => "Too many attempts. Try again in {$seconds}s."]);
        }

        $user = User::where('mobile', $mobile)->where('role', 'customer')->first();

        if (! $user || $user->otp !== $request->input('otp')
            || ! $user->otp_expires_at || $user->otp_expires_at->isPast()) {
            RateLimiter::hit($throttleKey, 600);

            return back()->withErrors(['otp' => 'Incorrect or expired OTP.']);
        }

        RateLimiter::clear($throttleKey);

        if (! $user->isActive) {
            return back()->withErrors(['otp' => 'Your account has been deactivated. Contact support.']);
        }

        // One-time use — rotate the code so it can't be replayed.
        $user->update(['otp' => (string) random_int(100000, 999999), 'otp_expires_at' => null]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->forget('customer_otp_mobile');

        return redirect()->intended(route('customer.prescriptions.index', absolute: false));
    }

    public function resend(Request $request): RedirectResponse
    {
        $mobile = $request->session()->get('customer_otp_mobile');

        abort_unless($mobile, 419);

        $this->ensureNotRateLimited($request, $mobile, 'customer-otp-resend:', 1, 30);

        $user = User::where('mobile', $mobile)->where('role', 'customer')->firstOrFail();

        $this->issueOtp($user);

        return back()->with('status', 'A new OTP has been sent.');
    }

    /**
     * Local/dev: static 123456, matching the pattern already used
     * everywhere else (see UserSeeder, roadmap Core Logic §OTP). Non-local:
     * a real random code, logged rather than sent — no SMS gateway is
     * wired yet (still an open decision on the customer-requirements doc),
     * so this is the honest current state, not a finished send.
     */
    protected function issueOtp(User $user): void
    {
        $otp = app()->environment('local') ? '123456' : (string) random_int(100000, 999999);

        $user->update(['otp' => $otp, 'otp_expires_at' => now()->addMinutes(5)]);

        if (! app()->environment('local')) {
            Log::info("Customer OTP for {$user->mobile}: {$otp}");
        }
    }

    /**
     * Mirrors LoginRequest::ensureIsNotRateLimited — same RateLimiter
     * pattern, keyed by mobile+IP instead of login_id+IP.
     */
    protected function ensureNotRateLimited(Request $request, string $mobile, string $prefix, int $max, int $decaySeconds): void
    {
        $key = $prefix.$mobile.'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, $max)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'mobile' => "Too many attempts. Try again in {$seconds}s.",
            ]);
        }

        RateLimiter::hit($key, $decaySeconds);
    }
}
