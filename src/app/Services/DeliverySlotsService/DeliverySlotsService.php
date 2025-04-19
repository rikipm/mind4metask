<?php

namespace App\Services\DeliverySlotsService;

use App\Enums\CityEnum;
use App\Services\DeliverySlotsService\DTO\DeliverySlot;
use App\Services\DeliverySlotsService\DTO\DeliverySlotsCollection;
use App\Services\DeliverySlotsService\Exceptions\CityWithUndefinedScenarioException;
use Carbon\CarbonImmutable;

class DeliverySlotsService
{
    protected const HOLIDAYS = [
        '01.01',
        '08.03',
        '09.05',
    ];

    public function getDeliverySlots(CarbonImmutable $datetime, CityEnum $city): DeliverySlotsCollection
    {
        $deliverySlots = [];
        $counter = $datetime->toMutable();

        if (in_array($city, [CityEnum::CITY_1, CityEnum::CITY_2])) {
            for ($i = 1; $i <= 21; $i++) {
                $counter->addDay();
                if ($this->isHoliday($counter->toImmutable())) {
                    continue;
                }

                if ($i === 1 and $this->isAfterTime($datetime, 16, 00) and in_array(
                        $datetime->addDay()->dayOfWeek,
                        [
                            CarbonImmutable::MONDAY,
                            CarbonImmutable::WEDNESDAY,
                            CarbonImmutable::FRIDAY,
                        ]
                    )) {
                    continue;
                }

                if (!in_array($counter->dayOfWeek, [
                    CarbonImmutable::MONDAY,
                    CarbonImmutable::WEDNESDAY,
                    CarbonImmutable::FRIDAY,
                ])) {
                    continue;
                }
                $deliverySlots[] = DeliverySlot::fromCarbon($counter);
            }
        } elseif ($city === CityEnum::CITY_3) {
            for ($i = 1; $i <= 21; $i++) {
                $counter->addDay();
                if ($this->isHoliday($counter->toImmutable())) {
                    continue;
                }

                if ($i === 1 and $this->isAfterTime($datetime, 22, 00) and in_array(
                        $datetime->addDay()->dayOfWeek,
                        [
                            CarbonImmutable::TUESDAY,
                            CarbonImmutable::THURSDAY,
                            CarbonImmutable::SATURDAY,
                        ]
                    )) {
                    continue;
                }

                if (!in_array($counter->dayOfWeek, [
                    CarbonImmutable::TUESDAY,
                    CarbonImmutable::THURSDAY,
                    CarbonImmutable::SATURDAY,
                ])) {
                    continue;
                }
                $deliverySlots[] = DeliverySlot::fromCarbon($counter);
            }
        } else {
            throw new CityWithUndefinedScenarioException();
        }

        return new DeliverySlotsCollection(...$deliverySlots);
    }

    protected function isHoliday(CarbonImmutable $datetime): bool
    {
        return in_array($datetime->format('d.m'), self::HOLIDAYS);
    }

    protected function isAfterTime(CarbonImmutable $datetime, int $hours, int $minutes): bool
    {
        return $datetime->gt($datetime->setTime($hours, $minutes));
    }
}
