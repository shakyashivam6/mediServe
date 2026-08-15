<x-layouts.login-layout>
    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                    <a href="{{ url('/') }}" class="auth-brand mb-4">
                        <img src="assets/images/logo-dark.png" alt="dark logo" height="26" class="logo-dark">
                        <img src="{{ $siteLogo }}" alt="logo light" height="26" class="logo-light">
                    </a>

                    <h4 class="fw-semibold mb-2 fs-18">Log in to your account</h4>

                    <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>

                    <x-auth-session-status class="alert alert-success" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="text-start mb-3">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="login_id">Login ID</label>
                            <input type="text" id="login_id" name="login_id" class="form-control @error('login_id') is-invalid @enderror" placeholder="Enter your login ID" value="{{ old('login_id') }}" required autofocus autocomplete="username">
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

                    <!-- @if (Route::has('register'))
                        <p class="text-muted fs-14 mb-4">Don't have an account? <a href="{{ route('register') }}" class="fw-semibold text-danger ms-1">Sign Up !</a></p>
                    @endif -->

                    <p class="mt-auto mb-0">
                        <script>document.write(new Date().getFullYear())</script> © By <span class="fw-bold text-decoration-underline text-uppercase text-reset fs-12"><a href="https://tejasweb.com" class="link-info">Tejes Web Solution</a></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.login-layout>
