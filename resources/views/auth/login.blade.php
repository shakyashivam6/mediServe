@php
    // Which tab opens by default: Customer OTP is the leftmost/primary tab
    // and active unless the Staff form is the one that just failed (that
    // POST bounces back here via back() — same page it was submitted from,
    // so its own errors/old input need to re-show on the Staff tab instead).
    $staffTabActive = $errors->hasAny(['login_id', 'password']);
@endphp
<x-layouts.login-layout>
    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                    <a href="{{ url('/') }}" class="auth-brand mb-2">
                        <img src="{{ $siteLogo }}" alt="logo" height="50" class="logo-dark">
                        <img src="{{ $siteLogo }}" alt="logo" height="50" class="logo-light">
                    </a>

                    <h4 class="fw-semibold mb-3 fs-18">Log in to your account</h4>

                    <x-auth-session-status class="alert alert-success" :status="session('status')" />

                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="#customer-login" data-bs-toggle="tab" role="tab" aria-selected="{{ $staffTabActive ? 'false' : 'true' }}" class="nav-link rounded {{ $staffTabActive ? '' : 'active' }}">
                                <i class="ri-smartphone-line align-middle me-1"></i> User/Guest
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#staff-login" data-bs-toggle="tab" role="tab" aria-selected="{{ $staffTabActive ? 'true' : 'false' }}" class="nav-link rounded {{ $staffTabActive ? 'active' : '' }}">
                                <i class="ri-shield-user-line align-middle me-1"></i> Staff
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- Customer — OTP-only, no password (see project memory:
                             customer-auth-and-admin-driven-signup). Same form as
                             Customer.auth.start, embedded here so a Customer never
                             has to leave the home page just to find where to log
                             in/sign up. Posts to the same customer.login.start
                             route, which sends them on to the OTP-entry page. --}}
                        <div class="tab-pane {{ $staffTabActive ? '' : 'show active' }}" id="customer-login" role="tabpanel">
                            <p class="text-muted fs-13 mb-3">Enter your mobile number — we'll text you a one-time code. New here? This creates your account too.</p>

                            <form method="POST" action="{{ route('customer.login.start') }}" class="text-start mb-1">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label" for="customer_mobile">Mobile number</label>
                                    <input type="tel" id="customer_mobile" name="mobile" class="form-control @error('mobile') is-invalid @enderror" placeholder="10-digit mobile number" inputmode="numeric" pattern="\d{10}" maxlength="10" value="{{ old('mobile') }}" required {{ $staffTabActive ? '' : 'autofocus' }}>
                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Already have an account with this number? You'll be logged straight in.</div>
                                </div>

                                <div class="d-grid">
                                    <button class="btn btn-primary fw-semibold" type="submit">Send OTP</button>
                                </div>
                            </form>
                        </div>

                        {{-- Admin / Store / Captain — login_id + password. Store/
                             Captain accounts are created by Admin, not public
                             signup (see project memory: customer-auth-and-admin-
                             driven-signup). --}}
                        <div class="tab-pane {{ $staffTabActive ? 'show active' : '' }}" id="staff-login" role="tabpanel">
                            <form method="POST" action="{{ route('login') }}" class="text-start mb-1">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label" for="login_id">Login ID</label>
                                    <input type="text" id="login_id" name="login_id" class="form-control @error('login_id') is-invalid @enderror" placeholder="Enter your login ID" value="{{ old('login_id') }}" required {{ $staffTabActive ? 'autofocus' : '' }} autocomplete="username">
                                    @error('login_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="password">Password</label>
                                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" required autocomplete="current-password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                        <label class="form-check-label" for="remember_me">Remember me</label>
                                    </div>

                                    <!-- @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-muted border-bottom border-dashed">Forget Password</a>
                                    @endif -->
                                </div>

                                <div class="d-grid">
                                    <button class="btn btn-primary fw-semibold" type="submit">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <p class="mt-auto mb-0">
                        <script>document.write(new Date().getFullYear())</script> © By <span class="fw-bold text-reset fs-12"><a href="https://tejasweb.com" class="link-danger" target="_blank">Tejesweb Solutions</a></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.login-layout>
