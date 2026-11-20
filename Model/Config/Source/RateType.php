<?php
/*
 * Copyright (c) 2026 WebWorks 101 OÜ (MageBooster). All rights reserved.
 * Commercial licence, see LICENSE.txt. https://magebooster.com
 * Licensed per serial number for one production installation (one store domain); staging and
 * development copies of that store are allowed. Serial numbers and downloads are tied to the customer.
 * You may modify this code for your own licensed store; keep this notice.
 * Not allowed: copying to other stores, redistributing, reselling, sublicensing, publishing it publicly,
 * removing licence checks or notices, sharing serial numbers. Unauthorised use: the serial number is
 * revoked without refund, updates and support stop, damages and legal action may follow.
 * Estonian law applies; court: Harju County Court (Harju Maakohus).
 */
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
