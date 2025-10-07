<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    // Menampilkan halaman utama
    public function index()
    {
        return view('weather');
    }

    // Mengambil data cuaca berdasarkan kota
    public function getWeather(Request $request)
    {
        $city = $request->input('city');

        // Langkah 1: Cari koordinat (latitude, longitude) berdasarkan nama kota
        $geoResponse = Http::get('https://geocoding-api.open-meteo.com/v1/search', [
            'name' => $city,
            'count' => 1,
        ]);

        if ($geoResponse->failed() || empty($geoResponse['results'])) {
            return back()->with('error', 'Kota tidak ditemukan. Silakan coba lagi.');
        }

        $lat = $geoResponse['results'][0]['latitude'];
        $lon = $geoResponse['results'][0]['longitude'];

        // Langkah 2: Ambil data cuaca dari Open-Meteo
        $weatherResponse = Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $lat,
            'longitude' => $lon,
            'current_weather' => true,
            'daily' => 'temperature_2m_max,temperature_2m_min',
            'timezone' => 'auto',
        ]);

        $data = [
            'city' => $city,
            'current' => $weatherResponse['current_weather'] ?? null,
            'daily' => $weatherResponse['daily'] ?? null,
        ];

        return view('weather', $data);
    }
}
