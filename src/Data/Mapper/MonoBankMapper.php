<?php

namespace App\Data\Mapper;

use App\Data\Value\CurrencyRate;

class MonoBankMapper
{
    private array $currencyCodes = [
        840 => 'USD',
        978 => 'EUR',
        980 => 'UAH',
    ];

    /**
     * @param array $response
     * @return CurrencyRate[]
     */
    public function map(array $response): array
    {
        $filtered = array_filter($response, function ($item) {
            return ($item['currencyCodeA'] === 840 && $item['currencyCodeB'] === 980) ||
                ($item['currencyCodeA'] === 978 && $item['currencyCodeB'] === 980);
        });

        return array_map(function ($item) {
            $currency = $this->currencyCodes[$item['currencyCodeA']] ?? 'Unknown';
            $baseCurrency = $this->currencyCodes[$item['currencyCodeB']] ?? 'Unknown';

            return new CurrencyRate(
                $currency,
                $baseCurrency,
                $item['rateBuy'],
                $item['rateSell']
            );
        }, $filtered);
    }
}
