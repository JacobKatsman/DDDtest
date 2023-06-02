<?php

namespace Meals\Application\Feature\Poll\UseCase\DishGetsActivePoll;

use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Application\Component\Provider\MenuProviderInterface;
use Meals\Application\Component\Validator\PollIsActiveValidator;
use Meals\Application\Component\Validator\UserHasAccessToViewPollsValidator;
use Meals\Application\Component\Validator\DishAvailableValidator;
use Meals\Application\Component\Validator\PollTimeOrdervalidator;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\Dislist;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\PollResult;

// получаем блюдо по пользователю и опросу проверяем с помощью меню
class Interactor
{
    /** @var EmployeeProviderInterface */
    private $employeeProvider;

    /** @var PollProviderInterface */
    private $pollProvider;

    /** @var MenuProviderInterface */
    private $menuProvider;

    /** @var DishProviderInterface */
    private $dishProvider;

    /** @var UserHasAccessToViewPollsValidator */
    private $userHasAccessToPollsValidator;

    /** @var PollIsActiveValidator */
    private $pollIsActiveValidator;

    /** @var dishAvailableValidator */
    private $dishAvailableValidator;

    /**
     * Interactor constructor.
     * @param EmployeeProviderInterface $employeeProvider
     * @param PollProviderInterface  $pollProvider
     * @param MenuProviderInterface  $menuProvider
     * @param DishProviderInterface  $dishProvider
     * @param UserHasAccessToViewPollsValidator $userHasAccessToPollsValidator
     * @param PollIsActiveValidator $pollIsActiveValidator
     * @param DishAvailableValidator $dishAvailableValidator

     */
    public function __construct(
        EmployeeProviderInterface $employeeProvider,
        PollProviderInterface $pollProvider,
        MenuProviderInterface $menuProvider,
        DishProviderInterface $dishProvider,
        UserHasAccessToViewPollsValidator $userHasAccessToPollsValidator,
        PollIsActiveValidator $pollIsActiveValidator,
        DishAvailableValidator $dishAvailableValidator
    ) {
        $this->employeeProvider = $employeeProvider;
        $this->pollProvider = $pollProvider;
        $this->menuProvider = $menuProvider;
        $this->dishProvider = $dishProvider;
        $this->userHasAccessToPollsValidator = $userHasAccessToPollsValidator;
        $this->pollIsActiveValidator = $pollIsActiveValidator;
        $this->dishAvailableValidator = $dishAvailableValidator;

    }

    // просто моделируем проверку блюда, просто проверка наличия блюда в меню
    public function getActiveDish(int $employeeId,
                                  int $pollId,
                                  int $menuId,
                                  int $dishId) : Dish
    {
        $employee = $this->employeeProvider->getEmployee($employeeId);

        $poll = $this->pollProvider->getPoll($pollId);
        $menu = $this->menuProvider->getMenu($menuId);   // взяли меню
        $dishList = $menu->getDishes();                  // взяли список доступных блюд

        $this->userHasAccessToPollsValidator->validate($employee->getUser());
        $this->pollIsActiveValidator->validate($poll);

        // 1.  валидатор: проверили что такое блюдо есть в меню
        $this->dishAvailableValidator->validate($dishId, $dishList);

        // НЕ проверяем здесь время заказа! проверяем его при формировании заказа
        // 2. валидатор времени заказа, проверили время заказа
        // $this->PollTimeOrderValidator->validate($poll, $n, $h);

        $dish = $this->dishProvider->getDish($dishId);

        return $dish;
    }

}
