<x-layouts.customer-layout title="Log in">

    <div class="card">
        <h2 style="margin-top:0;">Welcome</h2>
        <p style="color:var(--ink-soft); font-size:14px; margin-top:-6px;">
            Enter your name and mobile number — we'll text you a one-time code. New here? This creates your account too.
        </p>

        <form method="POST" action="{{ route('customer.login.start') }}">
            @csrf

            <label for="first_name">Your name</label>
            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="e.g. Priya Sharma" required autofocus>
            @error('first_name')<div class="field-error">{{ $message }}</div>@enderror

            <label for="mobile">Mobile number</label>
            <input type="tel" id="mobile" name="mobile" value="{{ old('mobile') }}" placeholder="10-digit mobile number" inputmode="numeric" pattern="\d{10}" maxlength="10" required>
            @error('mobile')<div class="field-error">{{ $message }}</div>@enderror
            <div class="hint">Already have an account with this number? You'll be logged straight in.</div>

            <label for="alternate_mobile">Alternate number <span style="font-weight:400; color:var(--ink-faint);">(optional)</span></label>
            <input type="tel" id="alternate_mobile" name="alternate_mobile" value="{{ old('alternate_mobile') }}" placeholder="10-digit backup number" inputmode="numeric" pattern="\d{10}" maxlength="10">
            @error('alternate_mobile')<div class="field-error">{{ $message }}</div>@enderror
            <div class="hint">In case a store or captain can't reach you on your main number.</div>

            <button type="submit" class="btn">Send OTP</button>
        </form>
    </div>

</x-layouts.customer-layout>
