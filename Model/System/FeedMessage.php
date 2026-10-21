<?php
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
    public function __construct(private readonly FlagManager $flagManager)
    {
    }

    public function getIdentity()
    {
        return 'magebooster_tcmb_feed_' . md5((string)json_encode($this->flagManager->getFlagData(Tcmb::FLAG_ERROR)));
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
            htmlspecialchars((string)($data['time'] ?? '')),
            htmlspecialchars((string)($data['error'] ?? ''))
        );
    }

    public function getSeverity()
    {
        return self::SEVERITY_MAJOR;
    }
}
