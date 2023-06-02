<?php

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollList;
use Meals\Domain\Poll\PollResult;

// реализователи наш интерфейс
class FakePollResultProvider implements PollResultProviderInterface
{
    /** @var PollResult */
    private $pollResult;

    public function getPollResult(int $PollResultId): PollResult
    {
        return $this->pollResult;
    }

    /**
     * @param Poll $pollResult
     */
    public function setPollResult(PollResult $pollResult): void
    {
        $this->pollResult = $pollResult;
    }

}
