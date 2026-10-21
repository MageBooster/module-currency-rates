<?php
declare(strict_types=1);

namespace MageBooster\CurrencyRates\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class RateType implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'ForexSelling', 'label' => __('Forex selling (Döviz satış)')],
            ['value' => 'ForexBuying', 'label' => __('Forex buying (Döviz alış)')],
            ['value' => 'BanknoteSelling', 'label' => __('Banknote selling (Efektif satış)')],
            ['value' => 'BanknoteBuying', 'label' => __('Banknote buying (Efektif alış)')],
        ];
    }
}
