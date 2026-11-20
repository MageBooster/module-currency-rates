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

namespace MageBooster\CurrencyRates\Model\System;

use MageBooster\CurrencyRates\Model\Import\Tcmb;
use Magento\Framework\FlagManager;
use Magento\Framework\Notification\MessageInterface;

/**
 * Admin notice after a failed TCMB import (cleared by the next successful import).
 */
class FeedMessage implements MessageInterface
{
    public function __construct(private FlagManager $flagManager)
    {
    }

    public function getIdentity()
    {
        return 'magebooster_tcmb_feed_' . hash('sha256', (string)json_encode($this->flagManager->getFlagData(Tcmb::FLAG_ERROR)));
    }

    public function isDisplayed()
    {
        return (bool)$this->flagManager->getFlagData(Tcmb::FLAG_ERROR);
    }

    public function getText()
    {
        $data = (array)$this->flagManager->getFlagData(Tcmb::FLAG_ERROR);
        return (string)__(
            'The last TCMB currency rate import failed (%1, %2). The previous rates are still in use.',
            htmlspecialchars((string)($data['time'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE),
            htmlspecialchars((string)($data['error'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE)
        );
    }

    public function getSeverity()
    {
        return self::SEVERITY_MAJOR;
    }
}
