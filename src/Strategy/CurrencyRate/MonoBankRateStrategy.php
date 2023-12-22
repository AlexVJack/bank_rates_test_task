<?php

namespace App\Strategy\CurrencyRate;

use App\Data\Mapper\MonoBankMapper;
use App\Strategy\Interfaces\BankRateStrategy;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MonoBankRateStrategy implements BankRateStrategy
{
    private const MONOBANK_API_URL = 'https://api.monobank.ua/bank/currency';

    public function __construct(
        private HttpClientInterface $client,
        private MonoBankMapper $mapper
    ) {
    }

    public function fetchRates(): array
    {
        try {
            $response = $this->client->request('GET', self::MONOBANK_API_URL)->getContent();
            $responseData = json_decode($response, true);

            return $this->mapper->map($responseData);
        } catch (\Exception $e) {
            error_log('Error fetching rates from MonoBank' . $e->getMessage());

            return ['error' => $e->getMessage()];
        }
    }
}
