<?php

namespace App\Providers;

use App\Models\Wallet;
use App\Models\Transfer;
use App\Models\Transaction;
use App\Services\AtmService;
use App\Services\TaxService;
use App\Services\CastService;
use App\Services\AtomicService;
use App\Services\BasketService;
use App\Services\WalletService;
use App\Services\PrepareService;
use App\Services\DiscountService;
use App\Services\ExchangeService;
use App\Services\PurchaseService;
use App\Services\TransferService;
use App\Services\AssistantService;
use App\Services\RegulatorService;
use App\Services\BookkeeperService;
use App\Services\ConsistencyService;
use App\Services\EagerLoaderService;
use App\Services\TransactionService;
use App\Internal\Service\JsonService;
use App\Internal\Service\LockService;
use App\Internal\Service\MathService;
use App\Services\AtmServiceInterface;
use App\Services\TaxServiceInterface;
use Illuminate\Support\Facades\Event;
use App\Internal\Service\ClockService;
use App\Internal\Service\StateService;
use App\Services\CastServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Internal\Service\StorageService;
use App\Services\AtomicServiceInterface;
use App\Services\BasketServiceInterface;
use App\Services\WalletServiceInterface;
use App\Internal\Service\DatabaseService;
use App\Services\PrepareServiceInterface;
use App\External\Api\TransferQueryHandler;
use App\Services\DiscountServiceInterface;
use App\Services\ExchangeServiceInterface;
use App\Services\PurchaseServiceInterface;
use App\Services\TransferServiceInterface;
use App\Internal\Events\WalletCreatedEvent;
use App\Internal\Service\ConnectionService;
use App\Internal\Service\DispatcherService;
use App\Internal\Service\TranslatorService;
use App\Services\AssistantServiceInterface;
use App\Services\RegulatorServiceInterface;
use App\Internal\Events\BalanceUpdatedEvent;
use App\Internal\Service\UuidFactoryService;
use App\Services\BookkeeperServiceInterface;
use App\External\Api\TransactionQueryHandler;
use App\Internal\Assembler\ExtraDtoAssembler;
use App\Internal\Repository\WalletRepository;
use App\Services\ConsistencyServiceInterface;
use App\Services\EagerLoaderServiceInterface;
use App\Services\TransactionServiceInterface;
use App\Internal\Assembler\OptionDtoAssembler;
use App\Internal\Service\JsonServiceInterface;
use App\Internal\Service\LockServiceInterface;
use App\Internal\Service\MathServiceInterface;
use App\Internal\Repository\TransferRepository;
use App\Internal\Service\ClockServiceInterface;
use App\Internal\Service\StateServiceInterface;
use App\Internal\Assembler\TransferDtoAssembler;
use App\Internal\Events\TransactionCreatedEvent;
use App\Internal\Service\StorageServiceInterface;
use App\Internal\Assembler\TransferQueryAssembler;
use App\Internal\Repository\TransactionRepository;
use App\Internal\Service\DatabaseServiceInterface;
use App\Internal\Transform\TransferDtoTransformer;
use App\External\Api\TransferQueryHandlerInterface;
use App\Internal\Assembler\TransactionDtoAssembler;
use App\Internal\Assembler\AvailabilityDtoAssembler;
use App\Internal\Assembler\TransferLazyDtoAssembler;
use App\Internal\Events\WalletCreatedEventInterface;
use App\Internal\Service\ConnectionServiceInterface;
use App\Internal\Service\DispatcherServiceInterface;
use App\Internal\Service\TranslatorServiceInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\Events\TransactionCommitted;
use App\Internal\Assembler\TransactionQueryAssembler;
use App\Internal\Events\BalanceUpdatedEventInterface;
use App\Internal\Service\UuidFactoryServiceInterface;
use App\Internal\Transform\TransactionDtoTransformer;
use Illuminate\Database\Events\TransactionCommitting;
use Illuminate\Database\Events\TransactionRolledBack;
use App\External\Api\TransactionQueryHandlerInterface;
use App\Internal\Assembler\ExtraDtoAssemblerInterface;
use App\Internal\Repository\WalletRepositoryInterface;
use App\Internal\Assembler\OptionDtoAssemblerInterface;
use App\Internal\Assembler\WalletCreatedEventAssembler;
use App\Internal\Decorator\StorageServiceLockDecorator;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use App\Internal\Assembler\BalanceUpdatedEventAssembler;
use App\Internal\Listeners\TransactionBeginningListener;
use App\Internal\Listeners\TransactionCommittedListener;
use App\Internal\Repository\TransferRepositoryInterface;
use App\Internal\Assembler\TransferDtoAssemblerInterface;
use App\Internal\Events\TransactionCreatedEventInterface;
use App\Internal\Listeners\TransactionCommittingListener;
use App\Internal\Listeners\TransactionRolledBackListener;
use App\Internal\Assembler\TransferQueryAssemblerInterface;
use App\Internal\Repository\TransactionRepositoryInterface;
use App\Internal\Transform\TransferDtoTransformerInterface;
use App\Internal\Assembler\TransactionCreatedEventAssembler;
use App\Internal\Assembler\TransactionDtoAssemblerInterface;
use App\Internal\Assembler\AvailabilityDtoAssemblerInterface;
use App\Internal\Assembler\TransferLazyDtoAssemblerInterface;
use App\Internal\Assembler\TransactionQueryAssemblerInterface;
use App\Internal\Transform\TransactionDtoTransformerInterface;
use App\Internal\Assembler\WalletCreatedEventAssemblerInterface;
use App\Internal\Assembler\BalanceUpdatedEventAssemblerInterface;
use App\Internal\Assembler\TransactionCreatedEventAssemblerInterface;

final class WalletServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(TransactionBeginning::class, TransactionBeginningListener::class);
        Event::listen(TransactionCommitting::class, TransactionCommittingListener::class);
        Event::listen(TransactionCommitted::class, TransactionCommittedListener::class);
        Event::listen(TransactionRolledBack::class, TransactionRolledBackListener::class);
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        /**
         * @var array{
         *     internal?: array<class-string|null>,
         *     services?: array<class-string|null>,
         *     cache?: array{driver: string|null},
         *     repositories?: array<class-string|null>,
         *     transformers?: array<class-string|null>,
         *     assemblers?: array<class-string|null>,
         *     events?: array<class-string|null>,
         *     transaction?: array{model?: class-string|null},
         *     transfer?: array{model?: class-string|null},
         *     wallet?: array{model?: class-string|null},
         * } $configure
         */
        $configure = config('wallet', []);

        $this->internal($configure['internal'] ?? []);
        $this->services($configure['services'] ?? [], $configure['cache'] ?? []);

        $this->repositories($configure['repositories'] ?? []);
        $this->transformers($configure['transformers'] ?? []);
        $this->assemblers($configure['assemblers'] ?? []);
        $this->events($configure['events'] ?? []);

        $this->bindObjects($configure);
    }

    /**
     * @return class-string[]
     */
    public function provides(): array
    {
        return array_merge(
            $this->internalProviders(),
            $this->servicesProviders(),
            $this->repositoriesProviders(),
            $this->transformersProviders(),
            $this->assemblersProviders(),
            $this->eventsProviders(),
            $this->bindObjectsProviders(),
        );
    }

    /**
     * @param array<class-string|null> $configure
     */
    private function repositories(array $configure): void
    {
        $this->app->singleton(
            TransactionRepositoryInterface::class,
            $configure['transaction'] ?? TransactionRepository::class
        );

        $this->app->singleton(
            TransferRepositoryInterface::class,
            $configure['transfer'] ?? TransferRepository::class
        );

        $this->app->singleton(WalletRepositoryInterface::class, $configure['wallet'] ?? WalletRepository::class);
    }

    /**
     * @param array<class-string|null> $configure
     */
    private function internal(array $configure): void
    {
        $this->app->alias($configure['storage'] ?? StorageService::class, 'wallet.internal.storage');
        $this->app->when($configure['storage'] ?? StorageService::class)
            ->needs('$ttl')
            ->giveConfig('wallet.cache.ttl');

        $this->app->singleton(ClockServiceInterface::class, $configure['clock'] ?? ClockService::class);
        $this->app->singleton(ConnectionServiceInterface::class, $configure['connection'] ?? ConnectionService::class);
        $this->app->singleton(DatabaseServiceInterface::class, $configure['database'] ?? DatabaseService::class);
        $this->app->singleton(DispatcherServiceInterface::class, $configure['dispatcher'] ?? DispatcherService::class);
        $this->app->singleton(JsonServiceInterface::class, $configure['json'] ?? JsonService::class);

        $this->app->when($configure['lock'] ?? LockService::class)
            ->needs('$seconds')
            ->giveConfig('wallet.lock.seconds', 1);

        $this->app->singleton(LockServiceInterface::class, $configure['lock'] ?? LockService::class);

        $this->app->when($configure['math'] ?? MathService::class)
            ->needs('$scale')
            ->giveConfig('wallet.math.scale', 64);

        $this->app->singleton(MathServiceInterface::class, $configure['math'] ?? MathService::class);
        $this->app->singleton(StateServiceInterface::class, $configure['state'] ?? StateService::class);
        $this->app->singleton(TranslatorServiceInterface::class, $configure['translator'] ?? TranslatorService::class);
        $this->app->singleton(UuidFactoryServiceInterface::class, $configure['uuid'] ?? UuidFactoryService::class);
    }

    /**
     * @param array<class-string|null> $configure
     * @param array{driver?: string|null} $cache
     */
    private function services(array $configure, array $cache): void
    {
        $this->app->singleton(AssistantServiceInterface::class, $configure['assistant'] ?? AssistantService::class);
        $this->app->singleton(AtmServiceInterface::class, $configure['atm'] ?? AtmService::class);
        $this->app->singleton(AtomicServiceInterface::class, $configure['atomic'] ?? AtomicService::class);
        $this->app->singleton(BasketServiceInterface::class, $configure['basket'] ?? BasketService::class);
        $this->app->singleton(CastServiceInterface::class, $configure['cast'] ?? CastService::class);
        $this->app->singleton(
            ConsistencyServiceInterface::class,
            $configure['consistency'] ?? ConsistencyService::class
        );
        $this->app->singleton(DiscountServiceInterface::class, $configure['discount'] ?? DiscountService::class);
        $this->app->singleton(
            EagerLoaderServiceInterface::class,
            $configure['eager_loader'] ?? EagerLoaderService::class
        );
        $this->app->singleton(ExchangeServiceInterface::class, $configure['exchange'] ?? ExchangeService::class);
        $this->app->singleton(PrepareServiceInterface::class, $configure['prepare'] ?? PrepareService::class);
        $this->app->singleton(PurchaseServiceInterface::class, $configure['purchase'] ?? PurchaseService::class);
        $this->app->singleton(TaxServiceInterface::class, $configure['tax'] ?? TaxService::class);
        $this->app->singleton(
            TransactionServiceInterface::class,
            $configure['transaction'] ?? TransactionService::class
        );
        $this->app->singleton(TransferServiceInterface::class, $configure['transfer'] ?? TransferService::class);
        $this->app->singleton(WalletServiceInterface::class, $configure['wallet'] ?? WalletService::class);

        // bookkeepper service
        $this->app->when(StorageServiceLockDecorator::class)
            ->needs(StorageServiceInterface::class)
            ->give(function () use ($cache) {
                return $this->app->make(
                    'wallet.internal.storage',
                    [
                        'cacheRepository' => $this->app->get(CacheFactory::class)
                            ->store($cache['driver'] ?? 'array'),
                    ],
                );
            });

        $this->app->when($configure['bookkeeper'] ?? BookkeeperService::class)
            ->needs(StorageServiceInterface::class)
            ->give(StorageServiceLockDecorator::class);

        $this->app->singleton(BookkeeperServiceInterface::class, $configure['bookkeeper'] ?? BookkeeperService::class);

        // regulator service
        $this->app->when($configure['regulator'] ?? RegulatorService::class)
            ->needs(StorageServiceInterface::class)
            ->give(function () {
                return $this->app->make(
                    'wallet.internal.storage',
                    [
                        'cacheRepository' => clone $this->app->make(CacheFactory::class)
                            ->store('array'),
                    ],
                );
            });

        $this->app->singleton(RegulatorServiceInterface::class, $configure['regulator'] ?? RegulatorService::class);
    }

    /**
     * @param array<class-string|null> $configure
     */
    private function assemblers(array $configure): void
    {
        $this->app->singleton(
            AvailabilityDtoAssemblerInterface::class,
            $configure['availability'] ?? AvailabilityDtoAssembler::class
        );

        $this->app->singleton(
            BalanceUpdatedEventAssemblerInterface::class,
            $configure['balance_updated_event'] ?? BalanceUpdatedEventAssembler::class
        );

        $this->app->singleton(ExtraDtoAssemblerInterface::class, $configure['extra'] ?? ExtraDtoAssembler::class);

        $this->app->singleton(
            OptionDtoAssemblerInterface::class,
            $configure['option'] ?? OptionDtoAssembler::class
        );

        $this->app->singleton(
            TransactionDtoAssemblerInterface::class,
            $configure['transaction'] ?? TransactionDtoAssembler::class
        );

        $this->app->singleton(
            TransferLazyDtoAssemblerInterface::class,
            $configure['transfer_lazy'] ?? TransferLazyDtoAssembler::class
        );

        $this->app->singleton(
            TransferDtoAssemblerInterface::class,
            $configure['transfer'] ?? TransferDtoAssembler::class
        );

        $this->app->singleton(
            TransactionQueryAssemblerInterface::class,
            $configure['transaction_query'] ?? TransactionQueryAssembler::class
        );

        $this->app->singleton(
            TransferQueryAssemblerInterface::class,
            $configure['transfer_query'] ?? TransferQueryAssembler::class
        );

        $this->app->singleton(
            WalletCreatedEventAssemblerInterface::class,
            $configure['wallet_created_event'] ?? WalletCreatedEventAssembler::class
        );

        $this->app->singleton(
            TransactionCreatedEventAssemblerInterface::class,
            $configure['transaction_created_event'] ?? TransactionCreatedEventAssembler::class
        );
    }

    /**
     * @param array<class-string|null> $configure
     */
    private function transformers(array $configure): void
    {
        $this->app->singleton(
            TransactionDtoTransformerInterface::class,
            $configure['transaction'] ?? TransactionDtoTransformer::class
        );

        $this->app->singleton(
            TransferDtoTransformerInterface::class,
            $configure['transfer'] ?? TransferDtoTransformer::class
        );
    }

    /**
     * @param array<class-string|null> $configure
     */
    private function events(array $configure): void
    {
        $this->app->bind(
            BalanceUpdatedEventInterface::class,
            $configure['balance_updated'] ?? BalanceUpdatedEvent::class
        );

        $this->app->bind(
            WalletCreatedEventInterface::class,
            $configure['wallet_created'] ?? WalletCreatedEvent::class
        );

        $this->app->bind(
            TransactionCreatedEventInterface::class,
            $configure['transaction_created'] ?? TransactionCreatedEvent::class
        );
    }

    /**
     * @param array{
     *     transaction?: array{model?: class-string|null},
     *     transfer?: array{model?: class-string|null},
     *     wallet?: array{model?: class-string|null},
     * } $configure
     */
    private function bindObjects(array $configure): void
    {
        $this->app->bind(Transaction::class, $configure['transaction']['model'] ?? null);
        $this->app->bind(Transfer::class, $configure['transfer']['model'] ?? null);
        $this->app->bind(Wallet::class, $configure['wallet']['model'] ?? null);

        // api
        $this->app->bind(TransactionQueryHandlerInterface::class, TransactionQueryHandler::class);
        $this->app->bind(TransferQueryHandlerInterface::class, TransferQueryHandler::class);
    }

    /**
     * @return class-string[]
     */
    private function internalProviders(): array
    {
        return [
            ClockServiceInterface::class,
            ConnectionServiceInterface::class,
            DatabaseServiceInterface::class,
            DispatcherServiceInterface::class,
            JsonServiceInterface::class,
            LockServiceInterface::class,
            MathServiceInterface::class,
            StateServiceInterface::class,
            TranslatorServiceInterface::class,
            UuidFactoryServiceInterface::class,
        ];
    }

    /**
     * @return class-string[]
     */
    private function servicesProviders(): array
    {
        return [
            AssistantServiceInterface::class,
            AtmServiceInterface::class,
            AtomicServiceInterface::class,
            BasketServiceInterface::class,
            CastServiceInterface::class,
            ConsistencyServiceInterface::class,
            DiscountServiceInterface::class,
            EagerLoaderServiceInterface::class,
            ExchangeServiceInterface::class,
            PrepareServiceInterface::class,
            PurchaseServiceInterface::class,
            TaxServiceInterface::class,
            TransactionServiceInterface::class,
            TransferServiceInterface::class,
            WalletServiceInterface::class,

            BookkeeperServiceInterface::class,
            RegulatorServiceInterface::class,
        ];
    }

    /**
     * @return class-string[]
     */
    private function repositoriesProviders(): array
    {
        return [
            TransactionRepositoryInterface::class,
            TransferRepositoryInterface::class,
            WalletRepositoryInterface::class,
        ];
    }

    /**
     * @return class-string[]
     */
    private function transformersProviders(): array
    {
        return [
            AvailabilityDtoAssemblerInterface::class,
            BalanceUpdatedEventAssemblerInterface::class,
            ExtraDtoAssemblerInterface::class,
            OptionDtoAssemblerInterface::class,
            TransactionDtoAssemblerInterface::class,
            TransferLazyDtoAssemblerInterface::class,
            TransferDtoAssemblerInterface::class,
            TransactionQueryAssemblerInterface::class,
            TransferQueryAssemblerInterface::class,
            WalletCreatedEventAssemblerInterface::class,
            TransactionCreatedEventAssemblerInterface::class,
        ];
    }

    /**
     * @return class-string[]
     */
    private function assemblersProviders(): array
    {
        return [TransactionDtoTransformerInterface::class, TransferDtoTransformerInterface::class];
    }

    /**
     * @return class-string[]
     */
    private function eventsProviders(): array
    {
        return [
            BalanceUpdatedEventInterface::class,
            WalletCreatedEventInterface::class,
            TransactionCreatedEventInterface::class,
        ];
    }

    /**
     * @return class-string[]
     */
    private function bindObjectsProviders(): array
    {
        return [TransactionQueryHandlerInterface::class, TransferQueryHandlerInterface::class];
    }
}
