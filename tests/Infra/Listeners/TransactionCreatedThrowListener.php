<?php

namespace Tests\Infra\Listeners;

use App\Internal\Events\TransactionCreatedEventInterface;
use Tests\Infra\Exceptions\UnknownEventException;

final class TransactionCreatedThrowListener
{
    public function handle(TransactionCreatedEventInterface $transactionCreatedEvent): never
    {
        $type = $transactionCreatedEvent->getType();
        $createdAt = $transactionCreatedEvent->getCreatedAt()
            ->format(\DateTimeInterface::ATOM)
        ;

        $message = hash('sha256', $type . $createdAt);

        throw new UnknownEventException($message, $transactionCreatedEvent->getId());
    }
}
