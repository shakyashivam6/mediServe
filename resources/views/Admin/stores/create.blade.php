<x-layouts.admin-layout title="Add Store">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add Store</h4>

                    <form method="POST" action="{{ route('admin.stores.store') }}">
                        @csrf

                        <h5 class="fs-14 text-uppercase text-muted mb-2">Owner &amp; Login Details</h5>
                        @include('Admin.partials.person-fields', ['user' => null, 'nextLoginId' => $nextLoginId, 'locations' => $locations])

                        <h5 class="fs-14 text-uppercase text-muted mb-2 mt-3">Shop Details</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="shop_name" class="form-label">Shop Name</label>
                                <input type="text" name="shop_name" id="shop_name"
                                    class="form-control @error('shop_name') is-invalid @enderror"
                                    value="{{ old('shop_name') }}" required>
                                @error('shop_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="license_no" class="form-label">Drug License No. <span class="text-muted">(optional)</span></label>
                                <input type="text" name="license_no" id="license_no"
                                    class="form-control @error('license_no') is-invalid @enderror"
                                    value="{{ old('license_no') }}">
                                @error('license_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="gst_no" class="form-label">GST No. <span class="text-muted">(optional)</span></label>
                                <input type="text" name="gst_no" id="gst_no"
                                    class="form-control @error('gst_no') is-invalid @enderror"
                                    value="{{ old('gst_no') }}">
                                @error('gst_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        @include('Admin.partials.map-picker')

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="latitude" class="form-label">Latitude <span class="text-muted">(optional)</span></label>
                                <input type="number" step="any" name="latitude" id="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror"
                                    value="{{ old('latitude') }}">
                                @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="longitude" class="form-label">Longitude <span class="text-muted">(optional)</span></label>
                                <input type="number" step="any" name="longitude" id="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror"
                                    value="{{ old('longitude') }}">
                                @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="delivery_radius_km" class="form-label">Delivery Radius (km) <span class="text-muted">(optional)</span></label>
                                <input type="number" min="0" name="delivery_radius_km" id="delivery_radius_km"
                                    class="form-control @error('delivery_radius_km') is-invalid @enderror"
                                    value="{{ old('delivery_radius_km') }}">
                                @error('delivery_radius_km')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="delivery_speed_kmph" class="form-label">Delivery Speed (kmph) <span class="text-muted">(optional)</span></label>
                                <input type="number" min="0" name="delivery_speed_kmph" id="delivery_speed_kmph"
                                    class="form-control @error('delivery_speed_kmph') is-invalid @enderror"
                                    value="{{ old('delivery_speed_kmph') }}">
                                @error('delivery_speed_kmph')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <p class="text-muted font-13">
                            Leave radius/speed blank if this store won't deliver directly for now — it can still be fulfilled later through a delivery partner.
                            "Fast Delivery" is never set manually; it's calculated per order from these two numbers.
                        </p>

                        <button type="submit" class="btn btn-primary mt-2">Create Store</button>
                        <a href="{{ route('admin.stores.index') }}" class="btn btn-light mt-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin-layout>
