<?php

namespace Tests\Infra\Transform;

use App\Internal\Dto\TransactionDtoInterface;
use App\Internal\Transform\TransactionDtoTransformer;
use App\Internal\Transform\TransactionDtoTransformerInterface;

final class TransactionDtoTransformerCustom implements TransactionDtoTransformerInterface
{
    public function __construct(
        private readonly TransactionDtoTransformer $transactionDtoTransformer
    ) {
    }

    public function extract(TransactionDtoInterface $dto): array
    {
        $bankMethod = null;
        if ($dto->getMeta() !== null) {
            $bankMethod = $dto->getMeta()['bank_method'] ?? null;
        }

        return array_merge($this->transactionDtoTransformer->extract($dto), [
            'bank_method' => $bankMethod,
        ]);
    }
}
