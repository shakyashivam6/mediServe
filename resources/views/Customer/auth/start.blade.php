<x-layouts.customer-layout title="Log in">

    <div class="card">
        <h2 style="margin-top:0;">Welcome</h2>
        <p style="color:var(--ink-soft); font-size:14px; margin-top:-6px;">
            Enter your mobile number — we'll text you a one-time code. New here? This creates your account too.
        </p>

        <form method="POST" action="{{ route('customer.login.start') }}">
            @csrf

            <label for="mobile">Mobile number</label>
            <input type="tel" id="mobile" name="mobile" value="{{ old('mobile') }}" placeholder="10-digit mobile number" inputmode="numeric" pattern="\d{10}" maxlength="10" required autofocus>
            @error('mobile')<div class="field-error">{{ $message }}</div>@enderror
            <div class="hint">Already have an account with this number? You'll be logged straight in.</div>

            <button type="submit" class="btn">Send OTP</button>
        </form>
    </div>

</x-layouts.customer-layout>
