<?php

namespace App\Service;

class ThresholdService
{
    public function __construct(
        private float $eurThreshold,
        private float $usdThreshold
    ) {
    }

    public function checkThreshold(array $rates, string $bankName): array
    {
        $alerts = [];
        foreach ($rates as $rate) {
            if ($rate->currency === 'EUR' && ($rate->buyRate >= $this->eurThreshold || $rate->sellRate >= $this->eurThreshold)) {
                $alerts[] = "EUR threshold reached at {$bankName} in {$rate->baseCurrency}";
            }
            if ($rate->currency === 'USD' && ($rate->buyRate >= $this->usdThreshold || $rate->sellRate >= $this->usdThreshold)) {
                $alerts[] = "USD threshold reached at {$bankName} in {$rate->baseCurrency}";
            }
        }
        return $alerts;
    }
}
