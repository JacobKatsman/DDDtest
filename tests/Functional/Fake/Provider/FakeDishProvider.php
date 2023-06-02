<?php

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;

class FakeDishProvider implements DishProviderInterface
{
    /** @var Dish */
    private $dish;

    /** @var DishList */
    private $dishList;

    public function getActiveDishLIst(): DishList
    {
        return $this->dishList;
    }

    public function getDish(int $dishId): Dish
    {
        return $this->dish;
    }

    /**
     * @param Dish $dish
     */
    public function setDish(Dish $dish): void
    {
        $this->dish = $dish;
    }

    /**
     * @param DishList $dishList
     */
    public function setDishList(dishList $dishList): void
    {
        $this->dishList = $dishList;
    }
}
