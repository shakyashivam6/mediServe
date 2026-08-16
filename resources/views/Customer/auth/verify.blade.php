<x-layouts.customer-layout title="Verify OTP">

    <div class="card">
        <h2 style="margin-top:0;">Enter the OTP</h2>
        <p style="color:var(--ink-soft); font-size:14px; margin-top:-6px;">
            We sent a 6-digit code to <strong>{{ $mobile }}</strong>.
        </p>

        @if ($devOtp)
            <div class="alert alert-info">Dev environment — OTP is always <strong>{{ $devOtp }}</strong>.</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('customer.otp.verify') }}">
            @csrf

            <label for="otp">One-time code</label>
            <input type="text" id="otp" name="otp" inputmode="numeric" pattern="\d{6}" maxlength="6" placeholder="123456" required autofocus>

            <button type="submit" class="btn">Verify &amp; Continue</button>
        </form>

        <form method="POST" action="{{ route('customer.otp.resend') }}" style="margin-top:10px;">
            @csrf
            <button type="submit" class="btn btn-soft">Resend OTP</button>
        </form>

        <p style="text-align:center; margin-top:14px;">
            <a href="{{ route('customer.login') }}" style="font-size:13px; color:var(--ink-soft); text-decoration:underline;">Wrong number? Start over</a>
        </p>
    </div>

</x-layouts.customer-layout>
