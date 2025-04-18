<?php

namespace App\Services\WeatherService;

use App\Models\WeatherRequestRecord;
use App\Services\WeatherService\DTO\WeatherServiceResponse;
use Carbon\Carbon;
use Http;

class WeatherService
{
    public function getWeather(float $latitude, float $longitude, Carbon $date)
    {
        $response = Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'temperature_unit' => 'celsius',
            'daily' => ['weather_code', 'temperature_2m_mean'],
            'start_date' => $date->format('Y-m-d'),
            'end_date' => $date->format('Y-m-d'),
        ])->object();

        $temperatureCelsius = $response->daily->temperature_2m_mean[0];
        $humanReadableForm = $this->convertWeatherCodeToHumanReadableForm($response->daily->weather_code[0]);

        $weatherRequestRecord = new WeatherRequestRecord();
        $weatherRequestRecord->latitude = $latitude;
        $weatherRequestRecord->longitude = $longitude;
        $weatherRequestRecord->date = $date;
        $weatherRequestRecord->temperature_celsius = $temperatureCelsius;
        $weatherRequestRecord->human_readable_form = $humanReadableForm;
        $weatherRequestRecord->save();

        return new WeatherServiceResponse(
            $temperatureCelsius,
            $humanReadableForm,
        );
    }

    protected function convertWeatherCodeToHumanReadableForm(int $weatherCode): string
    {
        return match ($weatherCode) {
            0 => 'Чистое небо',
            1, 2, 3 => 'Преимущественно ясно, переменная облачность, пасмурно',
            45 => 'Туман, изморозь',
            48 => 'Изморозь',
            51 => 'Морось слабая',
            53 => 'Морось умеренная',
            55 => 'Морось сильная',
            56 => 'Слабая ледяная морось',
            57 => 'Сильная ледяная морось',
            61, 63, 65 => 'Дождь: слабый, умеренный, сильный',
            66, 67 => 'Ледяной дождь: слабый, сильный',
            71, 73, 75 => 'Снегопад: слабый, умеренный, сильный',
            77 => 'Мокрый снег',
            80, 81, 82 => 'Ливень: слабый, умеренный, сильный',
            85, 86 => 'Снежный ливень: слабый, сильный',
            95 => 'Гроза: слабая или умеренная',
            96, 99 => 'Гроза с градом: слабая и сильная',
            default => "НЕИЗВЕСТНЫЙ КОД ПОГОДЫ ($weatherCode)",
        };
    }
}
