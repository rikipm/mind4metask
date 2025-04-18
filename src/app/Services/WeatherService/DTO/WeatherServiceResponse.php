<?php

namespace App\Services\WeatherService\DTO;

class WeatherServiceResponse
{
    public function __construct(
        public float $temperatureCelsius,
        public string $humanReadableForm,
    ) {
    }
}
