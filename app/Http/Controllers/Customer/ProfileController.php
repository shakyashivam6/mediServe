<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Mandatory profile completion for a Customer — see project memory:
 * customer-otp-profile-completion. OTP itself only ever collects a mobile
 * number; name, alternate number, address and pincode are collected here
 * right after verify, and are enforced (via the `customer_profile_complete`
 * middleware) on every other Customer route until filled in. Also doubles
 * as a plain "edit my details" screen for an already-complete profile —
 * same form either way, only the copy on the page changes.
 */
class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('Customer.profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'second_name' => ['nullable', 'string', 'max:100'],
            'alternate_mobile' => ['required', 'digits:10', Rule::notIn([$user->mobile])],
            'email' => ['nullable', 'email', 'max:191'],
            'address_line' => ['required', 'string', 'max:500'],
            'pincode' => ['required', 'digits:6'],
        ], [
            'alternate_mobile.not_in' => 'Alternate number must be different from your main mobile number.',
        ]);

        $user->update($data);

        return redirect()->route('customer.prescriptions.index')->with('status', 'Profile saved.');
    }
}
