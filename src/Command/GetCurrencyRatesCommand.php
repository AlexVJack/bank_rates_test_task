<?php

namespace App\Command;

use App\Service\CurrencyRateService;
use App\Strategy\CurrencyRate\PrivatBankRateStrategy;
use App\Strategy\CurrencyRate\MonoBankRateStrategy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetCurrencyRatesCommand extends Command
{
    protected static $defaultName = 'app:get-currency-rates';

    public function __construct(
        private CurrencyRateService $currencyRateService,
        private PrivatBankRateStrategy $privatBankStrategy,
        private MonoBankRateStrategy $monoBankStrategy
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Fetches currency rates from different banks.')
            ->setHelp('This command allows you to fetch currency rates from specified banks...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch rates from PrivatBank
        $this->currencyRateService->setStrategy($this->privatBankStrategy);
        $privatBankRates = $this->currencyRateService->getRates();

        // Fetch rates from MonoBank
        $this->currencyRateService->setStrategy($this->monoBankStrategy);
        $monoBankRates = $this->currencyRateService->getRates();

        dump($privatBankRates);
        dump($monoBankRates);

        return Command::SUCCESS;
    }
}
