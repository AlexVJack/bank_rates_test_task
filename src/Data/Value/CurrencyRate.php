<?php

namespace App\Data\Value;

class CurrencyRate
{
    public function __construct(
        public readonly string $currency,
        public readonly string $baseCurrency,
        public readonly float $buyRate,
        public readonly float $sellRate
    ) {
    }
}
