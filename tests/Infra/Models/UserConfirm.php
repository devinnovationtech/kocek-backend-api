<?php

namespace Tests\Infra\Models;

use App\Interfaces\Confirmable;
use App\Interfaces\Wallet;
use App\Traits\CanConfirm;
use App\Traits\HasWallet;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $email
 *
 * @method int getKey()
 */
final class UserConfirm extends Model implements Wallet, Confirmable
{
    use HasWallet;
    use CanConfirm;

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'email'];

    public function getTable(): string
    {
        return 'users';
    }
}
