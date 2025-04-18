<?php

namespace App\Http\Resources;

use App\Services\WeatherService\DTO\WeatherServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @property WeatherServiceResponse $resource */
class WeatherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'temperatureCelsius' => $this->resource->temperatureCelsius,
            'humanReadableForm' => $this->resource->humanReadableForm,
        ];
    }
}
