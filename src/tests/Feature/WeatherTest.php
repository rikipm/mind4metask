<?php

namespace Tests\Feature;

use Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeatherTest extends TestCase
{
    use RefreshDatabase;

    private string $mockResponse = '{
    "latitude": 50.003273,
    "longitude": 20.00447,
    "generationtime_ms": 0.04208087921142578,
    "utc_offset_seconds": 0,
    "timezone": "GMT",
    "timezone_abbreviation": "GMT",
    "elevation": 240,
    "daily_units": {
        "time": "iso8601",
        "weather_code": "wmo code",
        "temperature_2m_mean": "°C"
    },
    "daily": {
        "time": [
            "2025-02-02"
        ],
        "weather_code": [
            71
        ],
        "temperature_2m_mean": [
            0.7
        ]
    }
}';

    public function test_weather_retrieval(): void
    {
        Http::preventStrayRequests();

        Http::fake([
            'https://api.open-meteo.com/v1/forecast?*' => Http::response($this->mockResponse, 200),
        ])->withQueryParameters([
            'latitude' => 50,
            'longitude' => 20,
            'temperature_unit' => 'celsius',
            'daily' => ['weather_code', 'temperature_2m_mean'],
            'start_date' => today()->format('Y-m-d'),
            'end_date' => today()->format('Y-m-d'),
        ]);

        $response = $this->post('/api/weather', [
            'latitude' => 50,
            'longitude' => 20,
            'date' => today()->format('Y-m-d'),
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            "data" => [
                'temperatureCelsius' => 0.7,
                'humanReadableForm' => 'Снегопад: слабый, умеренный, сильный',
            ]
        ]);
    }
}
