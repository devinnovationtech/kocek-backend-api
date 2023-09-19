<?php

namespace Tests\Infra\Models;

use App\Interfaces\Customer;
use App\Traits\CanPay;
use App\Traits\HasWallets;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $email
 *
 * @method int getKey()
 */
final class Buyer extends Model implements Customer
{
    use CanPay;
    use HasWallets;

    public function getTable(): string
    {
        return 'users';
    }
}
