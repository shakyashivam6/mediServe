<x-layouts.customer-layout title="Your details">

    <div class="card">
        @if (! $user->hasCompleteProfile())
            <h2 style="margin-top:0;">A few more details</h2>
            <p style="color:var(--ink-soft); font-size:14px; margin-top:-6px;">
                Before you get to your dashboard, we need your name, address and a backup number — so a store or captain can always reach you.
            </p>
        @else
            <h2 style="margin-top:0;">Your details</h2>
            <p style="color:var(--ink-soft); font-size:14px; margin-top:-6px;">
                Keep these up to date so a store or captain can always reach you.
            </p>
        @endif

        <form method="POST" action="{{ route('customer.profile.update') }}">
            @csrf
            @method('PUT')

            <label for="mobile_display">Mobile number</label>
            <input type="text" id="mobile_display" value="{{ $user->mobile }}" disabled>
            <div class="hint">This is your login number — contact support to change it.</div>

            <label for="first_name">Your name <span style="color:var(--red);">*</span></label>
            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" placeholder="e.g. Priya Sharma" required autofocus>
            @error('first_name')<div class="field-error">{{ $message }}</div>@enderror

            <label for="second_name">Last name <span style="font-weight:400; color:var(--ink-faint);">(optional)</span></label>
            <input type="text" id="second_name" name="second_name" value="{{ old('second_name', $user->second_name) }}" placeholder="e.g. Sharma">
            @error('second_name')<div class="field-error">{{ $message }}</div>@enderror

            <label for="alternate_mobile">Alternate number <span style="color:var(--red);">*</span></label>
            <input type="tel" id="alternate_mobile" name="alternate_mobile" value="{{ old('alternate_mobile', $user->alternate_mobile) }}" placeholder="10-digit backup number" inputmode="numeric" pattern="\d{10}" maxlength="10" required>
            @error('alternate_mobile')<div class="field-error">{{ $message }}</div>@enderror
            <div class="hint">In case a store or captain can't reach you on your main number.</div>

            <label for="email">Email <span style="font-weight:400; color:var(--ink-faint);">(optional)</span></label>
            <input type="text" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="you@example.com">
            @error('email')<div class="field-error">{{ $message }}</div>@enderror

            <label for="address_line">Address <span style="color:var(--red);">*</span></label>
            <textarea id="address_line" name="address_line" placeholder="House/flat no., street, locality, city">{{ old('address_line', $user->address_line) }}</textarea>
            @error('address_line')<div class="field-error">{{ $message }}</div>@enderror

            <label for="pincode">Postal pincode <span style="color:var(--red);">*</span></label>
            <input type="text" id="pincode" name="pincode" value="{{ old('pincode', $user->pincode) }}" placeholder="6-digit postal code" inputmode="numeric" pattern="\d{6}" maxlength="6" required>
            @error('pincode')<div class="field-error">{{ $message }}</div>@enderror

            <button type="submit" class="btn">Save &amp; continue</button>
        </form>
    </div>

</x-layouts.customer-layout>
