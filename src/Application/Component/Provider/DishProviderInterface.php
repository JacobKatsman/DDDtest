<?php

namespace Meals\Application\Component\Provider;

use Meals\Domain\Dish\Dish;

// интерфейс для взятия блюда по DishId
interface DishProviderInterface
{
    public function getDish(int $dishId): Dish;
}
