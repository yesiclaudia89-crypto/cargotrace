<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    /**
     * Get live maritime weather based on latitude and longitude coordinates (Slide 9 & 15).
     * Uses Laravel HTTP Client with timeout and error fallback.
     */
    public function getMaritimeWeather(float $latitude, float $longitude): array
    {
        try {
            // Open-Meteo public API (Free, high-accuracy global weather & marine model)
            $response = Http::timeout(4)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => round($latitude, 4),
                'longitude' => round($longitude, 4),
                'current_weather' => 'true',
            ]);

            if ($response->successful()) {
                $data = $response->json('current_weather');
                $cond = $this->interpretWeatherCode($data['weathercode'] ?? 0);
                
                return [
                    'status' => 'success',
                    'available' => true,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'temperature' => $data['temperature'] ?? 28.0,
                    'windspeed' => $data['windspeed'] ?? 14.0,
                    'winddirection' => $data['winddirection'] ?? 90,
                    'weather_code' => $data['weathercode'] ?? 0,
                    'condition' => $cond,
                    'description' => $cond,
                    'time' => $data['time'] ?? now()->toIso8601String(),
                ];
            }

            Log::warning('Weather API returned non-200 status', ['status' => $response->status()]);
        } catch (\Throwable $e) {
            // Gracefully handle timeout or network failure (Slide 9: Tangani timeout & error)
            Log::info('Weather API unavailable or timed out: ' . $e->getMessage());
        }

        // Safe fallback data so UI never breaks
        return [
            'status' => 'fallback',
            'available' => false,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'temperature' => 28.5,
            'windspeed' => 12.0,
            'winddirection' => 90,
            'weather_code' => 1,
            'condition' => 'Berawan Sebagian (Kondisi Laut Normal)',
            'description' => 'Berawan Sebagian (Kondisi Laut Normal)',
            'time' => now()->toIso8601String(),
        ];
    }

    /**
     * Map WMO weather codes to human-readable maritime descriptions.
     */
    private function interpretWeatherCode(int $code): string
    {
        return match (true) {
            $code === 0 => 'Cerah / Clear Sky (Visibilitas Sangat Baik)',
            in_array($code, [1, 2, 3]) => 'Sebagian Berawan / Partly Cloudy',
            in_array($code, [45, 48]) => 'Kabut Laut / Fog (Visibilitas Terbatas)',
            in_array($code, [51, 53, 55, 61, 63]) => 'Hujan Ringan / Light Rain',
            in_array($code, [65, 80, 81, 82]) => 'Hujan Lebat / Heavy Rain (Ombak Sedang)',
            in_array($code, [95, 96, 99]) => 'Badai Petir Laut / Thunderstorm (Peringatan Gelombang Tinggi)',
            default => 'Kondisi Cuaca Standar',
        };
    }
}
