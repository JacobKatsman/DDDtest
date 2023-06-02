<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\dishAvailableCheckException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;


class DishAvailableValidator
{
    public function validate(int $dishId, DishList $dishList): void
    {
        // проверить есть ли такое блюдо в текущем меню
        foreach ($dishList->getDishes() as $dish) {
            if ($dish->getId() == $dishId) {
                return;
            }
        }
        throw new dishAvailableCheckException();
    }
}
