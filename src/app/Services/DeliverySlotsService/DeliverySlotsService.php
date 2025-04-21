<?php

namespace App\Services\DeliverySlotsService;

use App\Enums\CityEnum;
use App\Services\DeliverySlotsService\DTO\DeliverySlot;
use App\Services\DeliverySlotsService\DTO\DeliverySlotsCollection;
use App\Services\DeliverySlotsService\Exceptions\CityWithUndefinedScenarioException;
use Carbon\Carbon;
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
        /** @var DeliverySlot $availableSlots */
        $availableSlots = [];

        if ($city === CityEnum::CITY_1 or $city === CityEnum::CITY_2) {
            $allowedDays = [Carbon::MONDAY, Carbon::WEDNESDAY, Carbon::FRIDAY];
            $timeThreshold = CarbonImmutable::createFromTime(16, 00);
            $skipNextDayIfTimeAfter = $allowedDays;
        } elseif ($city === CityEnum::CITY_3) {
            $allowedDays = [Carbon::TUESDAY, Carbon::THURSDAY, Carbon::SATURDAY];
            $timeThreshold = CarbonImmutable::createFromTime(22, 00);
            $skipNextDayIfTimeAfter = $allowedDays;
        } else {
            throw new CityWithUndefinedScenarioException();
        }

        for ($i = 1; $i <= 21; $i++) {
            $currentDate = $datetime->addDays($i);
            $currentDayOfWeek = $currentDate->dayOfWeekIso;

            if ($i === 1 && in_array($currentDayOfWeek, $skipNextDayIfTimeAfter)) {
                if ($this->isAfterTime($currentDate, $timeThreshold)) {
                    continue;
                }
            }

            if ($this->isHoliday($currentDate)) {
                continue;
            }

            if (!in_array($currentDayOfWeek, $allowedDays)) {
                continue;
            }

            $availableSlots[] = DeliverySlot::fromCarbon($currentDate);
        }

        return new DeliverySlotsCollection(...$availableSlots);
    }

    protected function isHoliday(CarbonImmutable $datetime): bool
    {
        return in_array($datetime->format('d.m'), self::HOLIDAYS);
    }

    protected function isAfterTime(CarbonImmutable $datetime, CarbonImmutable $time): bool
    {
        return $datetime->gt($datetime->setTime($time->hour, $time->minute));
    }
}
