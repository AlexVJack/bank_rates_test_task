<?php

namespace App\Service;

use App\Strategy\Interfaces\BankRateStrategy;

class CurrencyRateService
{
    private BankRateStrategy $strategy;

    public function setStrategy(BankRateStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function getRates(): array
    {
        return $this->strategy->fetchRates();
    }
}
