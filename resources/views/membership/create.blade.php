
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Membership | MemberHub</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f5ff;
            color: #30236b;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid #e6dfff;
            padding: 20px 35px;
        }

        .topbar-inner {
            max-width: 1100px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            font-size: 21px;
            font-weight: 800;
            color: #30236b;
            text-decoration: none;
        }

        .topbar-user {
            font-size: 13px;
            color: #71658e;
        }

        .content {
            max-width: 1000px;
            margin: auto;
            padding: 35px 25px 65px;
        }

        .page-heading {
            margin-bottom: 25px;
        }

        .page-heading h1 {
            font-size: 30px;
            margin: 0 0 10px;
        }

        .page-heading p {
            color: #756b92;
            line-height: 1.6;
        }

        .form-card {
            background: white;
            border: 1px solid #e4dcff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(48,35,107,.05);
        }

        .form-section {
            margin-bottom: 32px;
            padding-bottom: 30px;
            border-bottom: 1px solid #eee8ff;
        }

        .form-section:last-of-type {
            border-bottom: none;
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
        }

        .section-number {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #ede9fe;
            color: #6044c3;
            font-weight: 800;
            flex-shrink: 0;
        }

        .section-heading h2 {
            margin: 0;
            font-size: 19px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 9px;
        }

        input, select {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #dcd5f5;
            border-radius: 9px;
            background: white;
            color: #30236b;
            font-size: 14px;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #8065e8;
            box-shadow: 0 0 0 3px rgba(128,101,232,.12);
        }

        input[readonly] {
            background: #f7f5ff;
            color: #625a80;
        }

        .helper {
            font-size: 12px;
            color: #756b92;
            line-height: 1.6;
            margin-top: 7px;
        }

        .level-details {
            background: #f7f5ff;
            border: 1px solid #e4dcff;
            border-radius: 10px;
            padding: 18px;
            margin-top: 12px;
            line-height: 1.8;
        }

        .level-details strong {
            color: #50429a;
        }

        .level-details p {
            margin: 4px 0;
            overflow-wrap: anywhere;
        }

        #map {
            width: 100%;
            height: 360px;
            border-radius: 12px;
            border: 1px solid #e4dcff;
            background: #eee9ff;
        }

        .location-info, .weather-card {
            background: #f7f5ff;
            border: 1px solid #e4dcff;
            border-radius: 10px;
            padding: 17px;
            margin-top: 15px;
            line-height: 1.6;
            font-size: 13px;
        }

        .weather-card strong {
            display: block;
            margin-bottom: 7px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .submit-btn, .back-btn {
            display: inline-block;
            padding: 14px 20px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .submit-btn {
            background: #8065e8;
            color: white;
            border: none;
        }

        .submit-btn:hover { background: #684bd3; }

        .back-btn {
            background: #eee9ff;
            color: #49358f;
        }

        .error-box {
            background: #fee2e2;
            color: #991b1b;
            border-radius: 9px;
            padding: 16px;
            margin-bottom: 22px;
        }

        .success-box {
            background: #d1fae5;
            color: #065f46;
            padding: 16px;
            border-radius: 9px;
            margin-bottom: 22px;
        }

        @media (max-width: 650px) {
            .topbar { padding: 18px; }
            .content { padding: 20px; }
            .form-card { padding: 20px; }
            .form-grid { grid-template-columns: 1fr; }
            #map { height: 280px; }
        }
    </style>
</head>

<body>

<header class="topbar">
    <div class="topbar-inner">
        <a href="{{ route('user.dashboard') }}" class="brand">
            MemberHub
        </a>
        <div class="topbar-user">
            {{ auth()->user()->name }}
        </div>
    </div>
</header>

<main class="content">

    <div class="page-heading">
        <h1>Apply for Membership</h1>
        <p>
            Complete the application form below.
            Your application will be reviewed by an administrator.
        </p>
    </div>

    @if(session('success'))
        <div class="success-box" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error-box" role="alert">
            <strong>Please correct the following:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('membership.store') }}"
          class="form-card">

        @csrf

        <section class="form-section">
            <div class="section-heading">
                <div class="section-number">1</div>
                <h2>Membership Information</h2>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="membership_category_id">
                        Membership Category
                    </label>

                    <select id="membership_category_id"
                            name="membership_category_id"
                            required>
                        <option value="">Choose a category</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                @selected(old('membership_category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <p class="helper">
                        Select the category that best describes you.
                    </p>
                </div>

                <div class="form-group">
                    <label for="membership_level_id">
                        Membership Level
                    </label>

                    <select id="membership_level_id"
                            name="membership_level_id"
                            required>
                        <option value="">Choose a level</option>

                        @foreach($levels as $level)
                            <option value="{{ $level->id }}"
                                data-fee="{{ $level->fee }}"
                                data-validity="{{ $level->validity_months }}"
                                data-benefits="{{ $level->benefits }}"
                                @selected(old('membership_level_id') == $level->id)>
                                {{ $level->name }}
                                — ₱{{ number_format($level->fee ?? 0, 2) }}
                            </option>
                        @endforeach
                    </select>

                    <p class="helper">
                        Choose your preferred membership level.
                        Each level has its own fee, validity period,
                        and benefits.
                    </p>
                </div>
            </div>

            <div class="level-details"
                 id="level-details"
                 hidden
                 aria-live="polite">

                <p>
                    <strong>Membership Fee:</strong>
                    <span id="selected-fee"></span>
                </p>

                <p>
                    <strong>Validity Period:</strong>
                    <span id="selected-validity"></span>
                </p>

                <p>
                    <strong>Benefits:</strong>
                    <span id="selected-benefits"></span>
                </p>
            </div>
        </section>

        <section class="form-section">
            <div class="section-heading">
                <div class="section-number">2</div>
                <h2>Personal Information</h2>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text"
                           id="full_name"
                           name="full_name"
                           value="{{ old('full_name', auth()->user()->name) }}"
                           maxlength="150"
                           required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ auth()->user()->email }}"
                           readonly
                           required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel"
                           id="phone"
                           name="phone"
                           value="{{ old('phone') }}"
                           placeholder="09XXXXXXXXX"
                           maxlength="11"
                           minlength="11"
                           pattern="09[0-9]{9}"
                           inputmode="numeric"
                           title="Enter exactly 11 digits starting with 09."
                           required
                           oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11)">

                    <p class="helper">
                        Enter an 11-digit Philippine mobile number
                        starting with 09.
                    </p>
                </div>

                <div class="form-group">
                    <label for="address">Complete Address</label>
                    <input type="text"
                           id="address"
                           name="address"
                           value="{{ old('address') }}"
                           placeholder="Enter your complete address"
                           maxlength="255"
                           required>
                </div>
            </div>
        </section>

        <section class="form-section">
            <div class="section-heading">
                <div class="section-number">3</div>
                <h2>Location Information</h2>
            </div>

            <p class="helper">
                Click your location on the map to select it.
                Your selected coordinates will be saved
                with your application.
            </p>

            <div id="map"
                 aria-label="Google Maps location selector"></div>

            <input id="lat"
                   type="hidden"
                   name="latitude"
                   value="{{ old('latitude') }}">

            <input id="lon"
                   type="hidden"
                   name="longitude"
                   value="{{ old('longitude') }}">

            <div class="location-info"
                 id="coordinates"
                 role="status">
                No location selected yet.
                Please click a location on the map.
            </div>

            <div class="weather-card">
                <strong>Weather at Selected Location</strong>
                <div id="weather" role="status">
                    Select a location to view the weather.
                </div>
            </div>
        </section>

        <div class="form-actions">
            <button type="submit" class="submit-btn">
                Submit Membership Application
            </button>

            <a href="{{ route('user.dashboard') }}"
               class="back-btn">
                Back to Dashboard
            </a>
        </div>
    </form>
</main>

<script>
    // Display the selected membership level's details.
    const levelSelect = document.getElementById(
        'membership_level_id'
    );

    function updateLevelDetails() {
        const option = levelSelect.selectedOptions[0];
        const details = document.getElementById('level-details');

        if (!levelSelect.value || !option) {
            details.hidden = true;
            return;
        }

        details.hidden = false;

        const fee = option.dataset.fee;
        const validity = option.dataset.validity;
        const benefits = option.dataset.benefits;

        document.getElementById('selected-fee').textContent =
            fee !== undefined && fee !== ''
                ? '₱' + Number(fee).toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })
                : 'Not specified';

        document.getElementById('selected-validity').textContent =
            validity
                ? validity + ' months'
                : 'Not specified';

        document.getElementById('selected-benefits').textContent =
            benefits || 'No benefits specified';
    }

    levelSelect.addEventListener('change', updateLevelDetails);
    updateLevelDetails();

    // Google Maps and weather integration.
    let map;
    let marker;

    function loadWeather(lat, lon) {
        const weatherElement = document.getElementById('weather');
        weatherElement.textContent = 'Loading weather...';

        const url = @json(route('weather.show'));

        fetch(`${url}?lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lon)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Weather request failed');
                }

                return response.json();
            })
            .then(weather => {
                weatherElement.textContent =
                    `${weather.place}: ${weather.temperature}°C, ` +
                    `${weather.description}, humidity ${weather.humidity}%`;
            })
            .catch(() => {
                weatherElement.textContent =
                    'Weather is currently unavailable.';
            });
    }

    function setLocation(lat, lon) {
        document.getElementById('lat').value = lat.toFixed(7);
        document.getElementById('lon').value = lon.toFixed(7);

        document.getElementById('coordinates').textContent =
            `Selected Location — Latitude: ${lat.toFixed(5)}, ` +
            `Longitude: ${lon.toFixed(5)}`;

        if (marker) {
            marker.setPosition({ lat, lng: lon });
        } else {
            marker = new google.maps.Marker({
                position: { lat, lng: lon },
                map: map
            });
        }

        loadWeather(lat, lon);
    }

    function initMap() {
        const savedLat = document.getElementById('lat').value;
        const savedLon = document.getElementById('lon').value;

        const hasSavedLocation =
            savedLat !== '' && savedLon !== '' &&
            Number.isFinite(Number(savedLat)) &&
            Number.isFinite(Number(savedLon)) &&
            Number(savedLat) >= -90 &&
            Number(savedLat) <= 90 &&
            Number(savedLon) >= -180 &&
            Number(savedLon) <= 180;

        const lat = hasSavedLocation
            ? Number(savedLat)
            : 14.5995;

        const lon = hasSavedLocation
            ? Number(savedLon)
            : 120.9842;

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

        if (hasSavedLocation) {
            setLocation(lat, lon);
        }
    }

    window.initMap = initMap;

    document.querySelector('.form-card')
        .addEventListener('submit', event => {
            const lat = document.getElementById('lat').value;
            const lon = document.getElementById('lon').value;

            if (!lat || !lon) {
                event.preventDefault();

                document.getElementById('coordinates').textContent =
                    'Please select a location on the map before submitting.';

                document.getElementById('coordinates')
                    .scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
            }
        });
</script>

<script async
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&amp;loading=async&amp;callback=initMap">
</script>

</body>
</html>
