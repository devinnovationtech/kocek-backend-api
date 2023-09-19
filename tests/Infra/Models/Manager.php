<?php

namespace Tests\Infra\Models;

use App\Interfaces\Wallet;
use App\Traits\HasWallet;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $email
 *
 * @method int getKey()
 */
final class Manager extends Model implements Wallet
{
    use HasWallet;

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'email'];
}
