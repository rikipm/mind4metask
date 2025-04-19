<?php

namespace Tests\Unit\Services\DeliverySlotsService;

use App\Enums\CityEnum;
use App\Services\DeliverySlotsService\DeliverySlotsService;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class DeliveryServiceTest extends TestCase
{

    public function setUp(): void
    {
        Carbon::setLocale('ru');
    }

    /**
     * A basic test example.
     */
    public function test_that_response_have_normal_format(): void
    {
        $deliverySlotsService = app(DeliverySlotsService::class);
        $deliverySlotsArray = $deliverySlotsService
            ->getDeliverySlots(new CarbonImmutable('2025-01-01 15:00:00'), CityEnum::CITY_1)
            ->getDeliverySlots();

        $this->assertEquals('03.01.2025', $deliverySlotsArray[0]->date);
        $this->assertEquals('Пятница', $deliverySlotsArray[0]->day);
        $this->assertEquals('3 января', $deliverySlotsArray[0]->title);
    }

    public function test_that_response_respects_weekdays(): void
    {
        $deliverySlotsService = app(DeliverySlotsService::class);

        //CITY_1
        $deliverySlotsArray = $deliverySlotsService
            ->getDeliverySlots(new CarbonImmutable('2025-01-01 15:00:00'), CityEnum::CITY_1)
            ->getDeliverySlots();

        foreach ($deliverySlotsArray as $deliverySlot) {
            $date = CarbonImmutable::create($deliverySlot->date);
            $this->assertTrue($date->isMonday() or $date->isWednesday() or $date->isFriday());
        }

        //CITY_@
        $deliverySlotsArray = $deliverySlotsService
            ->getDeliverySlots(new CarbonImmutable('2025-01-01 15:00:00'), CityEnum::CITY_2)
            ->getDeliverySlots();

        foreach ($deliverySlotsArray as $deliverySlot) {
            $date = CarbonImmutable::create($deliverySlot->date);
            $this->assertTrue($date->isMonday() or $date->isWednesday() or $date->isFriday());
        }

        //CITY_3
        $deliverySlotsArray = $deliverySlotsService
            ->getDeliverySlots(new CarbonImmutable('2025-01-01 15:00:00'), CityEnum::CITY_3)
            ->getDeliverySlots();

        foreach ($deliverySlotsArray as $deliverySlot) {
            $date = CarbonImmutable::create($deliverySlot->date);
            $this->assertTrue($date->isTuesday() or $date->isThursday() or $date->isSaturday());
        }
    }

    public function test_that_response_respects_holidays(): void
    {
        $deliverySlotsService = app(DeliverySlotsService::class);

        //CITY_3
        $deliverySlotsArray = $deliverySlotsService
            ->getDeliverySlots(new CarbonImmutable('2024-12-01 15:00:00'), CityEnum::CITY_3)
            ->getDeliverySlots();

        foreach ($deliverySlotsArray as $deliverySlot) {
            $this->assertNotEquals('01.01.2025', $deliverySlot->date);
        }

        //CITY_1

        $deliverySlotsArray = $deliverySlotsService
            ->getDeliverySlots(new CarbonImmutable('2025-09-05 15:00:00'), CityEnum::CITY_1)
            ->getDeliverySlots();

        foreach ($deliverySlotsArray as $deliverySlot) {
            $this->assertNotEquals('09.05.2025', $deliverySlot->date);
        }
    }

    public function test_that_time_is_respected(): void
    {
        $deliverySlotsService = app(DeliverySlotsService::class);

        //CITY_1 15:00
        $deliverySlotsArray = $deliverySlotsService
            ->getDeliverySlots(new CarbonImmutable('2025-05-06 15:00:00'), CityEnum::CITY_1)
            ->getDeliverySlots();

        $this->assertEquals('07.05.2025', $deliverySlotsArray[0]->date);

        //CITY_1 17:00
        $deliverySlotsArray = $deliverySlotsService
            ->getDeliverySlots(new CarbonImmutable('2025-05-06 17:00:00'), CityEnum::CITY_1)
            ->getDeliverySlots();

        $this->assertEquals('12.05.2025', $deliverySlotsArray[0]->date);
    }
}
