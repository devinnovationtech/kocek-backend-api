<?php

namespace App\Internal\Transform;

use App\Internal\Dto\TransferDtoInterface;
use DateTimeImmutable;

interface TransferDtoTransformerInterface
{
    /**
     * @return array{
     *     uuid: string,
     *     deposit_id: int,
     *     withdraw_id: int,
     *     status: string,
     *     from_type: string,
     *     from_id: int|string,
     *     to_type: string,
     *     to_id: int|string,
     *     discount: int,
     *     fee: string,
     *     created_at: DateTimeImmutable,
     *     updated_at: DateTimeImmutable,
     * }
     */
    public function extract(TransferDtoInterface $dto): array;
}
