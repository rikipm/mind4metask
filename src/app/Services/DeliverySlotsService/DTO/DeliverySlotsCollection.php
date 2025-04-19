<?php

namespace App\Services\DeliverySlotsService\DTO;

//Костыль из-за того что в PHP до сих пор нет ни типизированных массивов, ни дженериков.
//Тащить в тестовый проект Psalm я не буду.
class DeliverySlotsCollection
{
    protected array $deliverySlots;

    public function __construct(DeliverySlot ...$deliverySlots)
    {
        $this->deliverySlots = $deliverySlots;
    }

    public function getDeliverySlots(): array
    {
        return $this->deliverySlots;
    }
}
