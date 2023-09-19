<?php

namespace Tests\Infra\PackageModels;

use Cknow\Money\Money;

/**
 * Class Transaction.
 *
 * @property Money $currency
 */
final class TransactionMoney extends \App\Models\Transaction
{
    private ?Money $currency = null;

    public function getCurrencyAttribute(): Money
    {
        $this->currency ??= \money($this->amount, $this->meta['currency'] ?? 'USD');

        return $this->currency;
    }
}
