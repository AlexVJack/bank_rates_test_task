<?php

namespace App\Data\Mapper;

use App\Data\Value\CurrencyRate;

class PrivatBankMapper
{
    /**
     * @param array $response
     * @return CurrencyRate[]
     */
    public function map(array $response): array
    {
        $rates = [];

        foreach ($response as $item) {
            $rates[] = new CurrencyRate(
                $item['ccy'],
                $item['base_ccy'],
                floatval($item['buy']),
                floatval($item['sale'])
            );
        }

        return $rates;
    }
}
