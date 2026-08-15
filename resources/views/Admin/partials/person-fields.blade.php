{{--
    Shared identity/login fields for any admin-created account (Store owner
    or Captain). Expects:
    - $user: existing User model on edit, or null on create
    - $nextLoginId: pre-generated login_id, only used when $user is null
    - $locations: Collection of all locations (id, name, parent_id)
--}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" name="first_name" id="first_name"
            class="form-control @error('first_name') is-invalid @enderror"
            value="{{ old('first_name', $user->first_name ?? '') }}" required>
        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="second_name" class="form-label">Last Name</label>
        <input type="text" name="second_name" id="second_name"
            class="form-control @error('second_name') is-invalid @enderror"
            value="{{ old('second_name', $user->second_name ?? '') }}" required>
        @error('second_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="login_id" class="form-label">Login ID</label>
        <input type="text" name="login_id" id="login_id"
            class="form-control @error('login_id') is-invalid @enderror"
            value="{{ old('login_id', $user->login_id ?? $nextLoginId ?? '') }}" required>
        @error('login_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="mobile" class="form-label">Mobile</label>
        <input type="text" name="mobile" id="mobile"
            class="form-control @error('mobile') is-invalid @enderror"
            value="{{ old('mobile', $user->mobile ?? '') }}" required>
        @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $user->email ?? '') }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="adhaar" class="form-label">Aadhaar No.</label>
        <input type="text" name="adhaar" id="adhaar"
            class="form-control @error('adhaar') is-invalid @enderror"
            value="{{ old('adhaar', $user->adhaar ?? '') }}" required>
        @error('adhaar')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="pan" class="form-label">PAN No.</label>
        <input type="text" name="pan" id="pan"
            class="form-control @error('pan') is-invalid @enderror"
            value="{{ old('pan', $user->pan ?? '') }}" required>
        @error('pan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-2 mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
            <option value="">Select</option>
            @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                <option value="{{ $value }}" @selected(old('gender', $user->gender ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-2 mb-3">
        <label for="dob" class="form-label">Date of Birth</label>
        <input type="date" name="dob" id="dob"
            class="form-control @error('dob') is-invalid @enderror"
            value="{{ old('dob', isset($user->dob) ? $user->dob->format('Y-m-d') : '') }}" required>
        @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="state-select" class="form-label">State</label>
        <select name="state" id="state-select" class="form-select @error('state') is-invalid @enderror" required>
            <option value="">Select State</option>
            @foreach ($locations->where('parent_id', 1) as $state)
                <option value="{{ $state->id }}" @selected(old('state', $user->state ?? '') == $state->id)>{{ $state->name }}</option>
            @endforeach
        </select>
        @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="city-select" class="form-label">City</label>
        <select name="city" id="city-select" class="form-select @error('city') is-invalid @enderror"
            data-selected="{{ old('city', $user->city ?? '') }}" required>
            <option value="">Select City</option>
        </select>
        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="pincode" class="form-label">Pincode</label>
        <input type="text" name="pincode" id="pincode" maxlength="6"
            class="form-control @error('pincode') is-invalid @enderror"
            value="{{ old('pincode', $user->pincode ?? '') }}" required>
        @error('pincode')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label for="address_line" class="form-label">Address</label>
    <textarea name="address_line" id="address_line" rows="2"
        class="form-control @error('address_line') is-invalid @enderror">{{ old('address_line', $user->address_line ?? '') }}</textarea>
    @error('address_line')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="password" class="form-label">{{ isset($user) ? 'New Password' : 'Password' }}</label>
    <input type="password" name="password" id="password"
        class="form-control @error('password') is-invalid @enderror"
        placeholder="{{ isset($user) ? 'Leave blank to keep unchanged' : '' }}" {{ isset($user) ? '' : 'required' }}>
    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

@push('scripts')
    <script>
        (function () {
            const locations = @json($locations->values());
            const stateSelect = document.getElementById('state-select');
            const citySelect = document.getElementById('city-select');

            function populateCities(stateId, preselect) {
                citySelect.innerHTML = '<option value="">Select City</option>';
                locations
                    .filter(function (l) { return l.parent_id === stateId; })
                    .forEach(function (l) {
                        const opt = document.createElement('option');
                        opt.value = l.id;
                        opt.textContent = l.name;
                        if (preselect && String(l.id) === String(preselect)) opt.selected = true;
                        citySelect.appendChild(opt);
                    });
            }

            stateSelect.addEventListener('change', function () {
                populateCities(parseInt(this.value, 10), null);
            });

            if (stateSelect.value) {
                populateCities(parseInt(stateSelect.value, 10), citySelect.dataset.selected);
            }
        })();
    </script>
@endpush
