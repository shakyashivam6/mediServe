<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates the rest of the Customer panel (prescriptions, notifications, …)
 * behind having filled in the mandatory post-OTP profile fields — see
 * project memory: customer-otp-profile-completion. Applied to a route
 * group that deliberately excludes customer.profile.* (see routes/web.php)
 * so a Customer can always reach the form that fixes what's missing,
 * rather than only being pushed there once, right after OTP verify.
 */
class EnsureCustomerProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()->hasCompleteProfile()) {
            return redirect()->route('customer.profile.edit');
        }

        return $next($request);
    }
}
