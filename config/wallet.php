<?php

use App\Internal\Assembler\AvailabilityDtoAssembler;
use App\Internal\Assembler\BalanceUpdatedEventAssembler;
use App\Internal\Assembler\ExtraDtoAssembler;
use App\Internal\Assembler\OptionDtoAssembler;
use App\Internal\Assembler\TransactionCreatedEventAssembler;
use App\Internal\Assembler\TransactionDtoAssembler;
use App\Internal\Assembler\TransactionQueryAssembler;
use App\Internal\Assembler\TransferDtoAssembler;
use App\Internal\Assembler\TransferLazyDtoAssembler;
use App\Internal\Assembler\TransferQueryAssembler;
use App\Internal\Events\BalanceUpdatedEvent;
use App\Internal\Events\TransactionCreatedEvent;
use App\Internal\Events\WalletCreatedEvent;
use App\Internal\Repository\TransactionRepository;
use App\Internal\Repository\TransferRepository;
use App\Internal\Repository\WalletRepository;
use App\Internal\Service\ClockService;
use App\Internal\Service\ConnectionService;
use App\Internal\Service\DatabaseService;
use App\Internal\Service\DispatcherService;
use App\Internal\Service\JsonService;
use App\Internal\Service\LockService;
use App\Internal\Service\MathService;
use App\Internal\Service\StateService;
use App\Internal\Service\StorageService;
use App\Internal\Service\TranslatorService;
use App\Internal\Service\UuidFactoryService;
use App\Internal\Transform\TransactionDtoTransformer;
use App\Internal\Transform\TransferDtoTransformer;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\Wallet;
use App\Services\AssistantService;
use App\Services\AtmService;
use App\Services\AtomicService;
use App\Services\BasketService;
use App\Services\BookkeeperService;
use App\Services\CastService;
use App\Services\ConsistencyService;
use App\Services\DiscountService;
use App\Services\EagerLoaderService;
use App\Services\ExchangeService;
use App\Services\PrepareService;
use App\Services\PurchaseService;
use App\Services\RegulatorService;
use App\Services\TaxService;
use App\Services\TransactionService;
use App\Services\TransferService;
use App\Services\WalletService;

return [
    /**
     * Arbitrary Precision Calculator.
     *
     * 'scale' - length of the mantissa
     */
    'math' => [
        'scale' => 64,
    ],

    /**
     * Storage of the state of the balance of wallets.
     */
    'cache' => [
        'driver' => env('WALLET_CACHE_DRIVER', 'array'),
        'ttl' => env('WALLET_CACHE_TTL', 24 * 3600),
    ],

    /**
     * A system for dealing with race conditions.
     */
    'lock' => [
        'driver' => env('WALLET_LOCK_DRIVER', 'array'),
        'seconds' => env('WALLET_LOCK_TTL', 1),
    ],

    /**
     * Internal services that can be overloaded.
     */
    'internal' => [
        'clock' => ClockService::class,
        'connection' => ConnectionService::class,
        'database' => DatabaseService::class,
        'dispatcher' => DispatcherService::class,
        'json' => JsonService::class,
        'lock' => LockService::class,
        'math' => MathService::class,
        'state' => StateService::class,
        'storage' => StorageService::class,
        'translator' => TranslatorService::class,
        'uuid' => UuidFactoryService::class,
    ],

    /**
     * Services that can be overloaded.
     */
    'services' => [
        'assistant' => AssistantService::class,
        'atm' => AtmService::class,
        'atomic' => AtomicService::class,
        'basket' => BasketService::class,
        'bookkeeper' => BookkeeperService::class,
        'regulator' => RegulatorService::class,
        'cast' => CastService::class,
        'consistency' => ConsistencyService::class,
        'discount' => DiscountService::class,
        'eager_loader' => EagerLoaderService::class,
        'exchange' => ExchangeService::class,
        'prepare' => PrepareService::class,
        'purchase' => PurchaseService::class,
        'tax' => TaxService::class,
        'transaction' => TransactionService::class,
        'transfer' => TransferService::class,
        'wallet' => WalletService::class,
    ],

    /**
     * Repositories for fetching data from the database.
     */
    'repositories' => [
        'transaction' => TransactionRepository::class,
        'transfer' => TransferRepository::class,
        'wallet' => WalletRepository::class,
    ],

    /**
     * Objects of transformer from DTO to array.
     */
    'transformers' => [
        'transaction' => TransactionDtoTransformer::class,
        'transfer' => TransferDtoTransformer::class,
    ],

    /**
     * Builder class, needed to create DTO.
     */
    'assemblers' => [
        'availability' => AvailabilityDtoAssembler::class,
        'balance_updated_event' => BalanceUpdatedEventAssembler::class,
        'extra' => ExtraDtoAssembler::class,
        'option' => OptionDtoAssembler::class,
        'transaction' => TransactionDtoAssembler::class,
        'transfer_lazy' => TransferLazyDtoAssembler::class,
        'transfer' => TransferDtoAssembler::class,
        'transaction_created_event' => TransactionCreatedEventAssembler::class,
        'transaction_query' => TransactionQueryAssembler::class,
        'transfer_query' => TransferQueryAssembler::class,
    ],

    /**
     * Package system events.
     */
    'events' => [
        'balance_updated' => BalanceUpdatedEvent::class,
        'wallet_created' => WalletCreatedEvent::class,
        'transaction_created' => TransactionCreatedEvent::class,
    ],

    /**
     * Base model 'transaction'.
     */
    'transaction' => [
        'table' => 'transactions',
        'model' => Transaction::class,
    ],

    /**
     * Base model 'transfer'.
     */
    'transfer' => [
        'table' => 'transfers',
        'model' => Transfer::class,
    ],

    /**
     * Base model 'wallet'.
     */
    'wallet' => [
        'table' => 'wallets',
        'model' => Wallet::class,
        'creating' => [],
        'default' => [
            'name' => 'Default Wallet',
            'slug' => 'default',
            'meta' => [],
        ],
    ],
];
