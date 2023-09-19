<?php

namespace App\Models;

use App\Interfaces\Confirmable;
use App\Interfaces\Customer;
use App\Interfaces\Exchangeable;
use App\Interfaces\WalletFloat;
use App\Internal\Exceptions\ExceptionInterface;
use App\Internal\Exceptions\TransactionFailedException;
use App\Internal\Service\MathServiceInterface;
use App\Internal\Service\UuidFactoryServiceInterface;
use App\Services\AtomicServiceInterface;
use App\Services\RegulatorServiceInterface;
use App\Traits\CanConfirm;
use App\Traits\CanExchange;
use App\Traits\CanPayFloat;
use App\Traits\HasGift;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Support\Str;

use function array_key_exists;
use function config;

/**
 * Class Wallet.
 *
 * @property class-string $holder_type
 * @property int|string $holder_id
 * @property string $name
 * @property string $slug
 * @property string $uuid
 * @property string $description
 * @property null|array $meta
 * @property int $decimal_places
 * @property Model $holder
 * @property string $credit
 * @property string $currency
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 *
 * @method int getKey()
 */
class Wallet extends Model implements Customer, WalletFloat, Confirmable, Exchangeable
{
    use CanConfirm;
    use CanExchange;
    use CanPayFloat;
    use HasGift;

    /**
     * @var string[]
     */
    protected $fillable = [
        'holder_type',
        'holder_id',
        'name',
        'slug',
        'uuid',
        'description',
        'meta',
        'balance',
        'decimal_places',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'decimal_places' => 'int',
        'meta' => 'json',
    ];

    /**
     * @var array<string, int|string>
     */
    protected $attributes = [
        'balance' => 0,
        'decimal_places' => 2,
    ];

    public function getTable(): string
    {
        if ((string) $this->table === '') {
            $this->table = config('wallet.wallet.table', 'wallets');
        }

        return parent::getTable();
    }

    public function setNameAttribute(string $name): void
    {
        $this->attributes['name'] = $name;

        /**
         * Must be updated only if the model does not exist or the slug is empty.
         */
        if (! $this->exists && ! array_key_exists('slug', $this->attributes)) {
            $this->attributes['slug'] = Str::slug($name);
        }
    }

    /**
     * Under ideal conditions, you will never need a method. Needed to deal with out-of-sync.
     *
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     */
    public function refreshBalance(): bool
    {
        return app(AtomicServiceInterface::class)->block($this, function () {
            $whatIs = $this->getBalanceAttribute();
            $balance = $this->getAvailableBalanceAttribute();
            if (app(MathServiceInterface::class)->compare($whatIs, $balance) === 0) {
                return true;
            }

            return app(RegulatorServiceInterface::class)->sync($this, $balance);
        });
    }

    public function getOriginalBalanceAttribute(): string
    {
        return (string) $this->getRawOriginal('balance', 0);
    }

    public function getAvailableBalanceAttribute(): float|int|string
    {
        return $this->walletTransactions()
            ->where('confirmed', true)
            ->sum('amount')
        ;
    }

    /**
     * @return MorphTo<Model, self>
     */
    public function holder(): MorphTo
    {
        return $this->morphTo();
    }

    public function getCreditAttribute(): string
    {
        return (string) ($this->meta['credit'] ?? '0');
    }

    public function getCurrencyAttribute(): string
    {
        return $this->meta['currency'] ?? Str::upper($this->slug);
    }

    protected function initializeMorphOneWallet(): void
    {
        $this->uuid = app(UuidFactoryServiceInterface::class)->uuid4();
    }
}