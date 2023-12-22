<?php

namespace App\Strategy\CurrencyRate;

use App\Data\Mapper\PrivatBankMapper;
use App\Strategy\Interfaces\BankRateStrategy;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PrivatBankRateStrategy implements BankRateStrategy
{
    private const PRIVATBANK_API_URL = 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5';

    public function __construct(
        private HttpClientInterface $client,
        private PrivatBankMapper $mapper
    ) {
    }

    public function fetchRates(): array
    {
        try {
            $response = $this->client->request('GET', self::PRIVATBANK_API_URL)->getContent();
            $responseData = json_decode($response, true);

            return $this->mapper->map($responseData);
        } catch (\Exception $e) {
            error_log('Error fetching rates from PrivatBank' . $e->getMessage());

            return ['error' => $e->getMessage()];
        }
    }
}
