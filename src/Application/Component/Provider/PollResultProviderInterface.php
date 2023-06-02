<?php

namespace Meals\Application\Component\Provider;

use Meals\Domain\Poll\PollResult;

// интерфейс тестирования результатов заказа
interface PollResultProviderInterface
{
    public function getPollResult(int $PollResultId): PollResult;
}
