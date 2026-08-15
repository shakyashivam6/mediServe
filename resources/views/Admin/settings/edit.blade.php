<x-layouts.admin-layout title="Settings">

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">App Config</h4>
                    <p class="text-muted font-14">Basic site details used across the app (e.g. the logo shown in the sidebar).</p>

                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        @method('PUT')

                        @foreach ($fields as $key => $label)
                            <div class="mb-3">
                                <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                                <input type="text" name="{{ $key }}" id="{{ $key }}"
                                    class="form-control @error($key) is-invalid @enderror"
                                    value="{{ old($key, $settings[$key]) }}">
                                @error($key)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-primary mt-2">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin-layout>
