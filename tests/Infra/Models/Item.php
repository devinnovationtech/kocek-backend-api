<?php

namespace Tests\Infra\Models;

use App\Interfaces\Customer;
use App\Interfaces\ProductLimitedInterface;
use App\Models\Transfer;
use App\Models\Wallet;
use App\Services\CastService;
use App\Services\CastServiceInterface;
use Tests\Infra\Helpers\Config;
use App\Traits\HasWallet;
use App\Traits\HasWallets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string $name
 * @property int $quantity
 * @property int $price
 *
 * @method int getKey()
 */
final class Item extends Model implements ProductLimitedInterface
{
    use HasWallet;
    use HasWallets;

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'quantity', 'price'];

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

    /**
     * @param int[] $walletIds
     *
     * @return MorphMany<Transfer>
     */
    public function boughtGoods(array $walletIds): MorphMany
    {
        return app(CastServiceInterface::class)
            ->getWallet($this)
            ->morphMany(Config::classString('wallet.transfer.model', Transfer::class), 'to')
            ->where('status', Transfer::STATUS_PAID)
            ->where('from_type', Config::classString('wallet.wallet.model', Wallet::class))
            ->whereIn('from_id', $walletIds)
        ;
    }
}
