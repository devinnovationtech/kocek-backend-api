<?php

namespace Tests\Infra\Models;

use App\Interfaces\Wallet;
use App\Interfaces\WalletFloat;
use App\Traits\HasWalletFloat;
use App\Traits\HasWallets;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $email
 *
 * @method int getKey()
 */
final class UserMulti extends Model implements Wallet, WalletFloat
{
    use HasWalletFloat;
    use HasWallets;

    public function getTable(): string
    {
        return 'users';
    }
}
