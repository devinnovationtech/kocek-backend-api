<?php

namespace Tests\Infra\Models;

use App\Interfaces\Wallet;
use App\Interfaces\WalletFloat;
use App\Traits\HasWalletFloat;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $email
 *
 * @method int getKey()
 */
final class UserFloat extends Model implements Wallet, WalletFloat
{
    use HasWalletFloat;

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'email'];

    public function getTable(): string
    {
        return 'users';
    }
}
