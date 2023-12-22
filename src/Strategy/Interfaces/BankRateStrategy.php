<?php

namespace App\Strategy\Interfaces;

interface BankRateStrategy
{
    public function fetchRates(): array;
}
