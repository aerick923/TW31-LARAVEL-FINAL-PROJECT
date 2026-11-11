
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Application</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 760px;
            margin: 40px auto;
            padding: 0 16px;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }

        input, select, button {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-top: 5px;
        }

        #map {
            height: 320px;
            margin-top: 12px;
        }

        .ok {
            color: #155724;
        }

        .error {
            color: #9b1c1c;
        }
    </style>
</head>
<body>

    <h1>Membership Application</h1>

    @if(session('success'))
        <p class="ok">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <ul class="error">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('membership.store') }}">
        @csrf

        <label>Membership Category</label>
        <select name="membership_category_id" required>
            <option value="">Choose a category</option>

            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(old('membership_category_id') == $category->id)>
                    {{ $category->name }}
                    {{ $category->level ? ' - '.$category->level : '' }}
                    (₱{{ number_format($category->fee, 2) }})
                </option>
            @endforeach
        </select>

        <label>Full Name</label>
        <input name="full_name"
               value="{{ old('full_name') }}" required>

        <label>Email</label>
        <input type="email" name="email"
               value="{{ old('email') }}" required>

        <label>Phone</label>
        <input name="phone" value="{{ old('phone') }}">

        <label>Address</label>
        <input name="address" value="{{ old('address') }}">

        <label>Location on Map</label>
        <div id="map"></div>

        <input id="lat" type="hidden" name="latitude"
               value="{{ old('latitude', '14.5995') }}">

        <input id="lon" type="hidden" name="longitude"
               value="{{ old('longitude', '120.9842') }}">

        <p id="coordinates">
            Click the map to choose a location.
        </p>

        <p id="weather">
            Weather has not been loaded.
        </p>

        <button type="submit">
            Create Membership Application
        </button>
    </form>

    <script>
        let map, marker;

        function loadWeather(lat, lon) {
            document.getElementById('weather').textContent =
                'Loading weather...';

            const url = @json(route('weather.show'));

            fetch(`${url}?lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lon)}`)
                .then(r => r.ok ? r.json() : Promise.reject())
                .then(w => {
                    document.getElementById('weather').textContent =
                        `${w.place}: ${w.temperature}°C, ${w.description}, humidity ${w.humidity}%`;
                })
                .catch(() => {
                    document.getElementById('weather').textContent =
                        'Weather is currently unavailable.';
                });
        }

        function setLocation(lat, lon) {
            document.getElementById('lat').value = lat.toFixed(7);
            document.getElementById('lon').value = lon.toFixed(7);

            document.getElementById('coordinates').textContent =
                `Latitude: ${lat.toFixed(5)}, Longitude: ${lon.toFixed(5)}`;

            if (marker) {
                marker.setMap(null);
            }

            marker = new google.maps.Marker({
                position: { lat, lng: lon },
                map: map
            });

            loadWeather(lat, lon);
        }

        function initMap() {
            const lat = Number(document.getElementById('lat').value);
            const lon = Number(document.getElementById('lon').value);

            map = new google.maps.Map(
                document.getElementById('map'),
                {
                    center: { lat, lng: lon },
                    zoom: 13
                }
            );

            map.addListener('click', event => {
                setLocation(
                    event.latLng.lat(),
                    event.latLng.lng()
                );
            });

            setLocation(lat, lon);
        }

        window.initMap = initMap;
    </script>

    <script async
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&loading=async&callback=initMap">
    </script>

</body>
</html>
