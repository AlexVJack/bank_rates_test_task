<?php

namespace App\Command;

use App\Service\CurrencyRateService;
use App\Service\EmailNotificationService;
use App\Service\ThresholdService;
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
        private ThresholdService $thresholdService,
        private EmailNotificationService $emailNotificationService,
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
        try {
            $this->currencyRateService->setStrategy($this->privatBankStrategy);
            $privatBankRates = $this->currencyRateService->getRates();

            $this->currencyRateService->setStrategy($this->monoBankStrategy);
            $monoBankRates = $this->currencyRateService->getRates();

            $privatBankAlerts = $this->thresholdService->checkThreshold($privatBankRates, 'PrivatBank');
            $monoBankAlerts = $this->thresholdService->checkThreshold($monoBankRates, 'MonoBank');

            $alerts = array_merge($privatBankAlerts, $monoBankAlerts);

            foreach ($alerts as $alert) {
                $this->emailNotificationService->sendNotification('Currency Threshold Alert', $alert);
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            error_log('Error' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
