<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Store/Captain accounts land on their own panel, never the Admin
        // one — Customer never reaches this controller at all (OTP login
        // via Customer\Auth\OtpAuthController instead). Everyone else
        // (Admin) keeps the shared /dashboard route.
        $default = match (Auth::user()->role) {
            'store' => route('store.dashboard', absolute: false),
            'captain' => route('captain.dashboard', absolute: false),
            default => route('dashboard', absolute: false),
        };

        return redirect()->intended($default);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
