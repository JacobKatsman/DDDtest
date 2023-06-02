<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollIntervalCheckException;
use Meals\Domain\Poll\Poll;

// There is check time and day of the week criterias
class PollTimeOrderValidator
{
    public const BEGINTIMEORDERS = 6;
    public const ENDTIMEORDERS = 22;
    public const ACTIVEDAYNUMBER = 1;

    public function validate(int $n, int $h): void
    {
        if (($n!=  PollTimeOrderValidator::ACTIVEDAYNUMBER) ||
            (($h < PollTimeOrderValidator::BEGINTIMEORDERS) ||
             ($h > PollTimeOrderValidator::ENDTIMEORDERS)))  {
            throw new PollIntervalCheckException();
        }
    }
}
