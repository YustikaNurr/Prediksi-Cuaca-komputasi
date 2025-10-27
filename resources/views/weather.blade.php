<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Prediksi Cuaca Laravel</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS Eksternal -->
    <link rel="stylesheet" href="{{ asset('css/weather.css') }}">
</head>
<body class="p-4">

<div class="container fade-in">
    <h1 class="text-center mb-4">🌦️ Aplikasi Prediksi Cuaca</h1>

    <!-- Form Input Kota -->
    <form action="/weather" method="POST" class="d-flex justify-content-center mb-5">
        @csrf
        <input type="text" name="city" class="form-control w-50 shadow-sm" placeholder="Masukkan nama kota (contoh: Jakarta)" required>
        <button type="submit" class="btn btn-primary ms-2 shadow-sm">Cek Cuaca</button>
    </form>

    <!-- Pesan Error -->
    @if (session('error'))
        <div class="alert alert-danger text-center fade-in">{{ session('error') }}</div>
    @endif

    <!-- Cuaca Saat Ini -->
    @isset($current)
        <div class="card text-center p-4 mb-5 mx-auto fade-in" style="max-width: 500px;">
            <div class="card-body">
                <div class="city-title">{{ ucfirst($city) }}</div>
                <div class="temperature mt-3">{{ $current['temperature'] }}°C</div>
                <p class="subtitle mt-2 mb-1">Suhu Saat Ini</p>
                <p class="mb-0">Kecepatan Angin: {{ $current['windspeed'] }} km/jam</p>
            </div>
        </div>
    @endisset

    <!-- Prakiraan 7 Hari -->
    @isset($daily)
        <h4 class="text-center mb-4 fade-in">📅 Perkiraan 7 Hari ke Depan</h4>
        <div class="row justify-content-center">
            @foreach ($daily['time'] as $i => $tanggal)
                <div class="col-md-3 col-sm-6 mb-4 fade-in">
                    <div class="forecast-card text-center p-3 shadow-sm">
                        <h6 class="fw-bold text-primary">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</h6>
                        <p class="mb-0 text-danger">🌡️ Maks: {{ $daily['temperature_2m_max'][$i] }}°C</p>
                        <p class="mb-0 text-info">❄️ Min: {{ $daily['temperature_2m_min'][$i] }}°C</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endisset

    <footer>
        Data cuaca disediakan oleh <a href="https://open-meteo.com/" target="_blank">Open-Meteo API</a>
    </footer>
</div>

</body>
</html>
