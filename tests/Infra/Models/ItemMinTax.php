<?php

namespace Tests\Infra\Models;

use App\Interfaces\Customer;
use App\Interfaces\MinimalTaxable;
use App\Interfaces\ProductLimitedInterface;
use App\Models\Wallet;
use App\Services\CastService;
use App\Traits\HasWallet;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property int $quantity
 * @property int $price
 *
 * @method int getKey()
 */
final class ItemMinTax extends Model implements ProductLimitedInterface, MinimalTaxable
{
    use HasWallet;

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'quantity', 'price'];

    public function getTable(): string
    {
        return 'items';
    }

    public function canBuy(Customer $customer, int $quantity = 1, bool $force = false): bool
    {
        $result = $this->quantity >= $quantity;

        if ($force) {
            return $result;
        }

        return $result && ! $customer->paid($this);
    }

    public function getAmountProduct(Customer $customer): int
    {
        /** @var Wallet $wallet */
        $wallet = app(CastService::class)->getWallet($customer);

        return $this->price + (int) $wallet->holder_id;
    }

    public function getMetaProduct(): ?array
    {
        return null;
    }

    public function getFeePercent(): float
    {
        return 3;
    }

    public function getMinimalFee(): int
    {
        return 90;
    }
}
