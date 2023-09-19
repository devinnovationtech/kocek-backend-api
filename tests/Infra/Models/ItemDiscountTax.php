<?php

namespace Tests\Infra\Models;

use App\Interfaces\Customer;
use App\Interfaces\Discount;
use App\Interfaces\ProductLimitedInterface;
use App\Interfaces\Taxable;
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
final class ItemDiscountTax extends Model implements ProductLimitedInterface, Discount, Taxable
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

    public function getPersonalDiscount(Customer $customer): int
    {
        return (int) app(CastService::class)
            ->getWallet($customer)
            ->holder_id;
    }

    /**
     * Specify the percentage of the amount. For example, the product costs $100, the equivalent of 15%. That's $115.
     *
     * Minimum 0; Maximum 100 Example: return 7.5; // 7.5%
     */
    public function getFeePercent(): float
    {
        return 7.5;
    }
}
