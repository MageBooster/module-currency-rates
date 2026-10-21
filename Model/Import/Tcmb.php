<?php
declare(strict_types=1);

namespace MageBooster\CurrencyRates\Model\Import;

use MageBooster\CurrencyRates\Model\TcmbFeed;
use MageBooster\License\Api\LicenseInterface;
use Magento\Directory\Model\Currency\Import\AbstractImport;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\FlagManager;
use Psr\Log\LoggerInterface;

/**
 * Magento currency import service using TCMB rates. Cross rates (e.g. EUR → USD) are calculated through TRY.
 * If the feed can't be read, the import reports an error and Magento keeps the last saved rates.
 */
class Tcmb extends AbstractImport
{
    public const PRODUCT = 'currency-rates';
    public const FLAG_ERROR = 'magebooster_tcmb_last_error';

    /** @var array<string, float>|null TRY per unit */
    private ?array $rates = null;
    private bool $failed = false;

    public function __construct(
        CurrencyFactory $currencyFactory,
        private readonly TcmbFeed $feed,
        private readonly LicenseInterface $license,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly FlagManager $flagManager,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct($currencyFactory);
    }

    public function fetchRates()
    {
        if (!$this->license->isValid(self::PRODUCT)) {
            $this->_messages[] = __('TCMB rates: enter your serial number in Stores > Configuration > MageBooster > Licenses.');
            return [];
        }
        $this->loadRates();
        if ($this->failed) {
            return [];
        }
        $data = parent::fetchRates();
        $this->flagManager->deleteFlag(self::FLAG_ERROR);
        return $data;
    }

    protected function _convert($currencyFrom, $currencyTo)
    {
        $this->loadRates();
        if ($this->failed) {
            return null;
        }
        $from = $this->rates[strtoupper((string)$currencyFrom)] ?? null;
        $to = $this->rates[strtoupper((string)$currencyTo)] ?? null;
        if (!$from || !$to) {
            $this->_messages[] = __('TCMB has no rate for %1.', $from ? $currencyTo : $currencyFrom);
            return null;
        }
        $rate = $from / $to;
        $markup = max(0.0, (float)$this->scopeConfig->getValue('currency/magebooster_tcmb/markup'));
        return $currencyFrom === $currencyTo ? 1.0 : $rate * (1 + $markup / 100);
    }

    private function loadRates(): void
    {
        if ($this->rates !== null || $this->failed) {
            return;
        }
        try {
            $result = $this->feed->fetch();
            $this->rates = $result['rates'];
            $this->logger->info('TCMB rates loaded', ['date' => $result['date'], 'currencies' => count($this->rates)]);
        } catch (\Throwable $e) {
            $this->failed = true;
            $this->_messages[] = __('TCMB rates could not be loaded (%1). The last saved rates stay in use.', $e->getMessage());
            $this->logger->error('TCMB import failed: ' . $e->getMessage());
            $this->flagManager->saveFlag(self::FLAG_ERROR, ['time' => gmdate('c'), 'error' => $e->getMessage()]);
        }
    }
}
