<?php

namespace App\Http\Resources;

use App\Services\DeliverySlotsService\DTO\DeliverySlot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @property DeliverySlot $resource */
class DeliverySlotsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date' => $this->resource->date,
            'day' => $this->resource->day,
            'title' => $this->resource->title,
        ];
    }
}
