<?php

namespace App\Services;

use App\Interfaces\Wallet;
use App\Models\Wallet as WalletModel;
use Illuminate\Database\Eloquent\Model;

/**
 * @api
 */
interface CastServiceInterface
{
    public function getWallet(Wallet $object, bool $save = true): WalletModel;

    public function getHolder(Model|Wallet $object): Model;

    public function getModel(object $object): Model;
}
