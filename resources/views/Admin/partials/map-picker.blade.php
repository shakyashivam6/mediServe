{{--
    Google Maps picker for the latitude/longitude fields on the Store
    create/edit forms. Reads/writes the existing #latitude and #longitude
    inputs directly — no extra hidden fields needed. Degrades gracefully
    to those plain inputs if GOOGLE_MAPS_API_KEY isn't set, or if Google
    rejects the key at runtime (see gm_authFailure below).
--}}
@php $mapsKey = config('services.google_maps.key'); @endphp

<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <label class="form-label mb-0">Pick Location on Map <span class="text-muted">(optional)</span></label>
        <button type="button" id="use-current-location" class="btn btn-soft-primary btn-sm">
            <i class="ri-crosshair-2-line align-middle"></i> Use my current location
        </button>
    </div>

    @if ($mapsKey)
        <div id="store-map" class="border rounded mt-2" style="height: 300px;"></div>
        <div id="store-map-error" class="alert alert-warning mt-2 mb-0 d-none">
            Google Maps couldn't load for this key — it's usually the <code>Maps JavaScript API</code> not being
            enabled, billing not active, or this domain being blocked by the key's HTTP referrer restrictions.
            You can still type latitude/longitude manually below, or use "Use my current location" above.
        </div>
        <p class="text-muted font-13 mb-0 mt-1">Click the map or drag the pin — latitude/longitude below fill in automatically.</p>
    @else
        <div class="alert alert-secondary mt-2 mb-0">
            Map picker needs <code>GOOGLE_MAPS_API_KEY</code> set in <code>.env</code> — you can still type
            latitude/longitude manually below, or use "Use my current location" above.
        </div>
    @endif
</div>

@push('scripts')
    <script>
        (function () {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const locateBtn = document.getElementById('use-current-location');

            locateBtn.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by this browser.');
                    return;
                }

                const originalHtml = locateBtn.innerHTML;
                locateBtn.disabled = true;
                locateBtn.innerHTML = '<span class="spinner-border spinner-border-sm align-middle"></span> Locating…';

                navigator.geolocation.getCurrentPosition(function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    latInput.value = lat.toFixed(7);
                    lngInput.value = lng.toFixed(7);

                    if (window.storeMap && window.storeMapMarker) {
                        const pos = { lat: lat, lng: lng };
                        window.storeMapMarker.setPosition(pos);
                        window.storeMap.setCenter(pos);
                        window.storeMap.setZoom(15);
                    }

                    locateBtn.disabled = false;
                    locateBtn.innerHTML = originalHtml;
                }, function (error) {
                    alert('Could not get your location: ' + error.message);
                    locateBtn.disabled = false;
                    locateBtn.innerHTML = originalHtml;
                }, { enableHighAccuracy: true, timeout: 10000 });
            });
        })();
    </script>
@endpush

@if ($mapsKey)
    @push('scripts')
        <script>
            // Google calls this globally when the key itself is rejected — bad
            // key, Maps JavaScript API not enabled, billing off, or the current
            // domain not allowed by the key's referrer restrictions. Without
            // this, Google leaves the broken grey map + "Do you own this
            // website?" popup on screen; we swap it for a plain fallback instead.
            window.gm_authFailure = function () {
                const mapEl = document.getElementById('store-map');
                const errorEl = document.getElementById('store-map-error');
                if (mapEl) mapEl.classList.add('d-none');
                if (errorEl) errorEl.classList.remove('d-none');
            };

            function initStoreMap() {
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                const hasExisting = latInput.value && lngInput.value;
                const startLat = hasExisting ? parseFloat(latInput.value) : 22.9734;
                const startLng = hasExisting ? parseFloat(lngInput.value) : 78.6569;

                const map = new google.maps.Map(document.getElementById('store-map'), {
                    center: { lat: startLat, lng: startLng },
                    zoom: hasExisting ? 15 : 5,
                });

                const marker = new google.maps.Marker({
                    position: { lat: startLat, lng: startLng },
                    map: map,
                    draggable: true,
                });

                // Exposed so "Use my current location" can re-center the map
                // and move the pin once a position comes back.
                window.storeMap = map;
                window.storeMapMarker = marker;

                function setFromLatLng(latLng) {
                    latInput.value = latLng.lat().toFixed(7);
                    lngInput.value = latLng.lng().toFixed(7);
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
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $mapsKey }}&callback=initStoreMap" async defer></script>
    @endpush
@endif
