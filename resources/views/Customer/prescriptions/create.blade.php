@php $mapsKey = config('services.google_maps.key'); @endphp

<x-layouts.customer-layout title="Upload Prescription">

    <div class="card">
        <h2 style="margin-top:0;">Upload your prescription</h2>
        <p style="color:var(--ink-soft); font-size:14px; margin-top:-6px;">
            Add a photo or PDF of the prescription, tell us where to deliver, and a store will review it and call you.
        </p>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('customer.prescriptions.store') }}" enctype="multipart/form-data">
            @csrf

            <label for="files">Prescription photo(s) or PDF</label>
            <input type="file" id="files" name="files[]" accept="image/*,.pdf" multiple required>
            <div class="hint">Up to 5 files, 10MB each. Multiple pages? Add each as a separate file.</div>

            <label for="remark">Remark <span style="font-weight:400; color:var(--ink-faint);">(optional)</span></label>
            <textarea id="remark" name="remark" placeholder="e.g. This is a refill for my father, please call before 6pm">{{ old('remark') }}</textarea>

            <label for="delivery_address">Delivery address</label>
            <textarea id="delivery_address" name="delivery_address" placeholder="Full address for delivery" required>{{ old('delivery_address', $savedAddress) }}</textarea>
            @if ($savedAddress)
                <div class="hint">Filled in from your last order — edit it if this delivery is going somewhere else.</div>
            @endif

            <div style="margin-top:14px; padding:12px 14px; background:var(--bg); border:1px solid var(--line); border-radius:10px;">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                    <div>
                        <strong style="font-size:13px;">Delivery location</strong>
                        <div class="hint" style="margin-top:2px;">Optional — pin it on the map, or use your current location.</div>
                    </div>
                    <button type="button" id="share-location" class="btn btn-soft" style="width:auto; margin:0; white-space:nowrap;">
                        <i class="ri-crosshair-2-line"></i> Use current location
                    </button>
                </div>

                <div class="alert alert-info" style="margin:10px 0 0;">
                    Your package will be delivered to the location you share on the map below.
                </div>

                @if ($mapsKey)
                    <div id="customer-map" style="height:220px; border:1px solid var(--line); border-radius:10px; margin-top:10px;"></div>
                    <div id="customer-map-error" class="hint" style="display:none; margin-top:6px;">
                        Map couldn't load — you can still use "Use current location" above, or just type your address.
                    </div>
                @else
                    <div class="hint" style="margin-top:10px;">
                        Map picker isn't set up yet — use "Use current location" above, or just type your address.
                    </div>
                @endif

                <p id="location-status" class="hint" style="margin:8px 0 0; @if ($savedLatitude && $savedLongitude) color:var(--green); @endif">
                    @if ($savedLatitude && $savedLongitude)
                        Using the location from your last order — drag the pin to update it.
                    @endif
                </p>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $savedLatitude) }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $savedLongitude) }}">
            </div>

            <button type="submit" class="btn">Upload Prescription</button>
        </form>
    </div>

    <script>
        (function () {
            const btn = document.getElementById('share-location');
            const statusEl = document.getElementById('location-status');
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            function setLocation(lat, lng, message) {
                latInput.value = lat.toFixed(7);
                lngInput.value = lng.toFixed(7);
                statusEl.textContent = message;
                statusEl.style.color = 'var(--green)';

                if (window.customerMap && window.customerMapMarker) {
                    const pos = { lat: lat, lng: lng };
                    window.customerMapMarker.setPosition(pos);
                    window.customerMap.setCenter(pos);
                    window.customerMap.setZoom(15);
                }
            }

            btn.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    statusEl.textContent = 'Geolocation is not supported by this browser.';
                    statusEl.style.color = '';
                    return;
                }

                const originalHtml = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = 'Locating…';

                navigator.geolocation.getCurrentPosition(function (position) {
                    setLocation(position.coords.latitude, position.coords.longitude, 'Location captured ✓');
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }, function (error) {
                    statusEl.textContent = 'Could not get your location: ' + error.message;
                    statusEl.style.color = '';
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }, { enableHighAccuracy: true, timeout: 10000 });
            });
        })();
    </script>

    @if ($mapsKey)
        <script>
            // Google calls this globally when the key itself is rejected — bad
            // key, Maps JavaScript API not enabled, billing off, or the current
            // domain not allowed by the key's referrer restrictions. Swap the
            // map for the plain fallback hint instead of leaving it broken.
            window.gm_authFailure = function () {
                const mapEl = document.getElementById('customer-map');
                const errorEl = document.getElementById('customer-map-error');
                if (mapEl) mapEl.style.display = 'none';
                if (errorEl) errorEl.style.display = 'block';
            };

            function initCustomerMap() {
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                const statusEl = document.getElementById('location-status');
                const hasExisting = latInput.value && lngInput.value;
                const startLat = hasExisting ? parseFloat(latInput.value) : 22.9734;
                const startLng = hasExisting ? parseFloat(lngInput.value) : 78.6569;

                const map = new google.maps.Map(document.getElementById('customer-map'), {
                    center: { lat: startLat, lng: startLng },
                    zoom: hasExisting ? 15 : 5,
                });

                const marker = new google.maps.Marker({
                    position: { lat: startLat, lng: startLng },
                    map: map,
                    draggable: true,
                });

                // Exposed so "Use current location" can re-center the map and
                // move the pin once a position comes back.
                window.customerMap = map;
                window.customerMapMarker = marker;

                function setFromLatLng(latLng) {
                    latInput.value = latLng.lat().toFixed(7);
                    lngInput.value = latLng.lng().toFixed(7);
                    statusEl.textContent = 'Location set on map ✓';
                    statusEl.style.color = 'var(--green)';
                }

                marker.addListener('dragend', function () {
                    setFromLatLng(marker.getPosition());
                });

                map.addListener('click', function (e) {
                    marker.setPosition(e.latLng);
                    setFromLatLng(e.latLng);
                });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $mapsKey }}&callback=initCustomerMap" async defer></script>
    @endif

</x-layouts.customer-layout>
