<x-layouts.admin-layout title="Add Captain">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add Captain</h4>

                    @if ($stores->isEmpty())
                        <div class="alert alert-warning">No approved Stores yet — a Captain needs to be linked to one. Approve a Store first.</div>
                    @endif

                    <form method="POST" action="{{ route('admin.captains.store') }}">
                        @csrf

                        <h5 class="fs-14 text-uppercase text-muted mb-2">Login Details</h5>
                        @include('Admin.partials.person-fields', ['user' => null, 'nextLoginId' => $nextLoginId, 'locations' => $locations])

                        <h5 class="fs-14 text-uppercase text-muted mb-2 mt-3">Delivery Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="store_id" class="form-label">Store</label>
                                <select name="store_id" id="store_id" class="form-select @error('store_id') is-invalid @enderror" required>
                                    <option value="">Select Store</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}" @selected(old('store_id') == $store->id)>{{ $store->store->shop_name }}</option>
                                    @endforeach
                                </select>
                                @error('store_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vehicle_type" class="form-label">Vehicle Type <span class="text-muted">(optional)</span></label>
                                <select name="vehicle_type" id="vehicle_type" class="form-select @error('vehicle_type') is-invalid @enderror">
                                    <option value="">Select</option>
                                    @foreach ($vehicleTypes as $type)
                                        <option value="{{ $type }}" @selected(old('vehicle_type') === $type)>{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                                @error('vehicle_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Create Captain</button>
                        <a href="{{ route('admin.captains.index') }}" class="btn btn-light mt-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin-layout>
