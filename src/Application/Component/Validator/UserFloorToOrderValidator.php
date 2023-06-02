<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\AccessFloorException;
use Meals\Domain\Poll\PollResult;

class UserFloorToOrderValidator
{
    public function validate(PollResult $PollResult): void
    {
        if (!($PollResult->hasCorrectFloor())) {
           throw new AccessFloorException();
        }
        return;
    }
}
