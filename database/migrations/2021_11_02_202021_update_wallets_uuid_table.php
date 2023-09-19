<?php

use App\Internal\Service\UuidFactoryServiceInterface;
use App\Models\Wallet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn($this->table(), 'uuid')) {
            return;
        }

        // upgrade from 6.x
        Schema::table($this->table(), static function (Blueprint $table) {
            $table->uuid('uuid')
                ->after('slug')
                ->nullable()
                ->unique()
            ;
        });

        Wallet::query()->chunk(10000, static function (Collection $wallets) {
            $wallets->each(function (Wallet $wallet) {
                $wallet->uuid = app(UuidFactoryServiceInterface::class)->uuid4();
                $wallet->save();
            });
        });

        Schema::table($this->table(), static function (Blueprint $table) {
            $table->uuid('uuid')
                ->change()
            ;
        });
    }

    public function down(): void
    {
        Schema::dropColumns($this->table(), ['uuid']);
    }

    private function table(): string
    {
        return (new Wallet())->getTable();
    }
};
