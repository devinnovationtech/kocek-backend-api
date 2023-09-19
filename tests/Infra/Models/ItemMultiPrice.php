<?php

namespace Tests\Infra\Models;

use App\Interfaces\Customer;
use App\Interfaces\ProductLimitedInterface;
use App\Models\Wallet;
use App\Services\CastService;
use Tests\Infra\Exceptions\PriceNotSetException;
use App\Traits\HasWallet;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property int $quantity
 * @property int $price
 * @property array<string, int> $prices
 *
 * @method int getKey()
 */
final class ItemMultiPrice extends Model implements ProductLimitedInterface
{
    use HasWallet;

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'quantity', 'price', 'prices'];

    protected $casts = [
        'prices' => 'array',
    ];

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

        if (array_key_exists($wallet->currency, $this->prices)) {
            return $this->prices[$wallet->currency];
        }

        throw new PriceNotSetException("Price not set for {$wallet->currency} currency");
    }

    public function getMetaProduct(): ?array
    {
        return null;
    }
}
