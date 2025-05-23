<?php

namespace App\Services\DeliverySlotsService\DTO;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

class DeliverySlot
{
    public function __construct(
        public string $date,
        public string $day,
        public string $title,
    ) {
    }

    public static function fromCarbon(CarbonImmutable $datetime): self
    {
        return new self(
            $datetime->format('d.m.Y'),
            Str::title($datetime->translatedFormat('l')),
            $datetime->translatedFormat('j F')
        );
    }
}
