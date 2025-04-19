<?php

namespace App\Http\Controllers;

use App\Enums\CityEnum;
use App\Http\Requests\DeliverySlotsRequest;
use App\Http\Resources\DeliverySlotsResource;
use App\Services\DeliverySlotsService\DeliverySlotsService;

class DeliverySlotsController extends Controller
{
    public function __construct(
        protected DeliverySlotsService $deliverySlotsService,
    ) {
    }

    public function deliverySlots(DeliverySlotsRequest $request)
    {
        $serviceResponse = $this->deliverySlotsService->getDeliverySlots(
            $request->datetime,
            CityEnum::from($request->city),
        );
        return DeliverySlotsResource::collection($serviceResponse->getDeliverySlots());
    }
}
