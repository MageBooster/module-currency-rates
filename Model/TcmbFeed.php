<?php
declare(strict_types=1);

namespace MageBooster\CurrencyRates\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\Client\CurlFactory;

/**
 * Reads https://www.tcmb.gov.tr/kurlar/today.xml and returns "how many TRY is one unit of X".
 */
class TcmbFeed
{
    public function __construct(
        private CurlFactory $curlFactory,
        private ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * @return array{date: string, rates: array<string, float>} rates: currency code => TRY per 1 unit (TRY => 1.0)
     * @throws \RuntimeException when the feed can't be read
     */
    public function fetch(): array
    {
        $url = (string)$this->scopeConfig->getValue('currency/magebooster_tcmb/url');
        $field = (string)$this->scopeConfig->getValue('currency/magebooster_tcmb/rate_type') ?: 'ForexSelling';
        $curl = $this->curlFactory->create();
        $curl->setTimeout(max(3, (int)$this->scopeConfig->getValue('currency/magebooster_tcmb/timeout')));
        $curl->addHeader('User-Agent', 'MageBooster-CurrencyRates/1.0');
        $curl->get($url);
        if ($curl->getStatus() !== 200) {
            throw new \RuntimeException(sprintf('TCMB feed answered HTTP %d', $curl->getStatus()));
        }
        return $this->parse($curl->getBody(), $field);
    }

    /**
     * @return array{date: string, rates: array<string, float>}
     */
    public function parse(string $xml, string $field): array
    {
        $previous = libxml_use_internal_errors(true);
        $doc = simplexml_load_string($xml, \SimpleXMLElement::class, LIBXML_NONET);
        libxml_use_internal_errors($previous);
        if ($doc === false || !isset($doc->Currency)) {
            throw new \RuntimeException('TCMB feed is not valid XML');
        }
        $fallback = str_starts_with($field, 'Banknote') ? 'Forex' . substr($field, 8) : null;
        $rates = ['TRY' => 1.0];
        foreach ($doc->Currency as $currency) {
            $code = strtoupper((string)$currency['CurrencyCode']);
            $unit = (float)str_replace(',', '.', (string)$currency->Unit) ?: 1.0;
            $value = trim((string)$currency->{$field});
            if ($value === '' && $fallback) {
                $value = trim((string)$currency->{$fallback});
            }
            $value = (float)str_replace(',', '.', $value);
            if ($code !== '' && $value > 0) {
                $rates[$code] = $value / $unit;
            }
        }
        if (count($rates) < 2) {
            throw new \RuntimeException('TCMB feed has no rates');
        }
        return ['date' => (string)($doc['Date'] ?? ''), 'rates' => $rates];
    }
}
