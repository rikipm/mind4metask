<?php

namespace App\Http\Controllers;

use App\Http\Requests\WeatherRequest;
use App\Http\Resources\WeatherResource;
use App\Services\WeatherService\WeatherService;

class WeatherController extends Controller
{

    public function __construct(
        protected WeatherService $weatherService,
    ) {
    }

    public function weather(WeatherRequest $request)
    {
        $serviceResponse = $this->weatherService->getWeather(
            $request->latitude,
            $request->longitude,
            $request->date
        );
        return WeatherResource::make($serviceResponse);
    }
}
