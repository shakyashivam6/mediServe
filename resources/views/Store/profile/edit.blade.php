<x-layouts.store-layout title="My Store Profile">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="header-title mb-0">My Store Profile</h4>
                        @php $variant = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$store->status]; @endphp
                        <span class="badge bg-{{ $variant }}">{{ ucfirst($store->status) }}</span>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('store.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <h5 class="fs-14 text-uppercase text-muted mb-2">Owner Details</h5>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Login ID</label>
                                <input type="text" class="form-control" value="{{ $user->login_id }}" disabled>
                                <div class="form-text">Contact Admin to change your Login ID.</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" id="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    value="{{ old('first_name', $user->first_name) }}" required>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="second_name" class="form-label">Last Name</label>
                                <input type="text" name="second_name" id="second_name"
                                    class="form-control @error('second_name') is-invalid @enderror"
                                    value="{{ old('second_name', $user->second_name) }}" required>
                                @error('second_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mobile" class="form-label">Mobile</label>
                                <input type="text" name="mobile" id="mobile"
                                    class="form-control @error('mobile') is-invalid @enderror"
                                    value="{{ old('mobile', $user->mobile) }}" required>
                                @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="state-select" class="form-label">State</label>
                                <select name="state" id="state-select" class="form-select @error('state') is-invalid @enderror" required>
                                    <option value="">Select State</option>
                                    @foreach ($locations->where('parent_id', 1) as $state)
                                        <option value="{{ $state->id }}" @selected(old('state', $user->state) == $state->id)>{{ $state->name }}</option>
                                    @endforeach
                                </select>
                                @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="city-select" class="form-label">City</label>
                                <select name="city" id="city-select" class="form-select @error('city') is-invalid @enderror"
                                    data-selected="{{ old('city', $user->city) }}" required>
                                    <option value="">Select City</option>
                                </select>
                                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="pincode" class="form-label">Pincode</label>
                                <input type="text" name="pincode" id="pincode" maxlength="6"
                                    class="form-control @error('pincode') is-invalid @enderror"
                                    value="{{ old('pincode', $user->pincode) }}" required>
                                @error('pincode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address_line" class="form-label">Address</label>
                            <textarea name="address_line" id="address_line" rows="2"
                                class="form-control @error('address_line') is-invalid @enderror">{{ old('address_line', $user->address_line) }}</textarea>
                            @error('address_line')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Leave blank to keep unchanged">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <h5 class="fs-14 text-uppercase text-muted mb-2 mt-3">Shop Details</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="shop_name" class="form-label">Shop Name</label>
                                <input type="text" name="shop_name" id="shop_name"
                                    class="form-control @error('shop_name') is-invalid @enderror"
                                    value="{{ old('shop_name', $store->shop_name) }}" required>
                                @error('shop_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="license_no" class="form-label">Drug License No. <span class="text-muted">(optional)</span></label>
                                <input type="text" name="license_no" id="license_no"
                                    class="form-control @error('license_no') is-invalid @enderror"
                                    value="{{ old('license_no', $store->license_no) }}">
                                @error('license_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="gst_no" class="form-label">GST No. <span class="text-muted">(optional)</span></label>
                                <input type="text" name="gst_no" id="gst_no"
                                    class="form-control @error('gst_no') is-invalid @enderror"
                                    value="{{ old('gst_no', $store->gst_no) }}">
                                @error('gst_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <h5 class="fs-14 text-uppercase text-muted mb-2 mt-3">Order Numbering</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="order_prefix" class="form-label">Order ID Prefix <span class="text-muted">(optional)</span></label>
                                <input type="text" name="order_prefix" id="order_prefix" maxlength="10" style="text-transform:uppercase;"
                                    class="form-control @error('order_prefix') is-invalid @enderror"
                                    value="{{ old('order_prefix', $store->order_prefix) }}" placeholder="OD">
                                <div class="form-text">
                                    Used for this store's auto-generated Order IDs once an order is confirmed — e.g.
                                    "{{ old('order_prefix', $store->order_prefix) ?: 'OD' }}-000123". Leave blank to use the default "OD" prefix.
                                </div>
                                @error('order_prefix')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        @include('Admin.partials.map-picker')

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="latitude" class="form-label">Latitude <span class="text-muted">(optional)</span></label>
                                <input type="number" step="any" name="latitude" id="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror"
                                    value="{{ old('latitude', $store->latitude) }}">
                                @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="longitude" class="form-label">Longitude <span class="text-muted">(optional)</span></label>
                                <input type="number" step="any" name="longitude" id="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror"
                                    value="{{ old('longitude', $store->longitude) }}">
                                @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="delivery_radius_km" class="form-label">Delivery Radius (km) <span class="text-muted">(optional)</span></label>
                                <input type="number" min="0" name="delivery_radius_km" id="delivery_radius_km"
                                    class="form-control @error('delivery_radius_km') is-invalid @enderror"
                                    value="{{ old('delivery_radius_km', $store->delivery_radius_km) }}">
                                @error('delivery_radius_km')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="delivery_speed_kmph" class="form-label">Delivery Speed (kmph) <span class="text-muted">(optional)</span></label>
                                <input type="number" min="0" name="delivery_speed_kmph" id="delivery_speed_kmph"
                                    class="form-control @error('delivery_speed_kmph') is-invalid @enderror"
                                    value="{{ old('delivery_speed_kmph', $store->delivery_speed_kmph) }}">
                                @error('delivery_speed_kmph')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <p class="text-muted font-13">
                            Leave radius/speed blank if you won't deliver directly for now. "Fast Delivery" is never set manually;
                            it's calculated per order from these two numbers.
                        </p>

                        <button type="submit" class="btn btn-primary mt-2">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
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

</x-layouts.store-layout>
