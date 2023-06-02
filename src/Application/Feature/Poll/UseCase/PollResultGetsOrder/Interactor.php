<?php

namespace Meals\Application\Feature\Poll\UseCase\PollResultGetsOrder;

use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Application\Component\Provider\MenuProviderInterface;
use Meals\Application\Component\Provider\PollResultProviderInterface;

use Meals\Application\Component\Validator\PollIsActiveValidator;
use Meals\Application\Component\Validator\UserHasAccessToViewPollsValidator;
use Meals\Application\Component\Validator\DishAvailableValidator;
use Meals\Application\Component\Validator\PollTimeOrderValidator;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\Dislist;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\PollResult;

// делаем заказ
class Interactor
{
    /** @var EmployeeProviderInterface */
    private $employeeProvider;

    /** @var PollProviderInterface */
    private $pollProvider;

    /** @var DishProviderInterface */
    private $dishProvider;

    /** @var PollResultProviderInterface */
    private $pollResultProvider;

    /** @var UserHasAccessToViewPollsValidator */
    private $userHasAccessToPollsValidator;

    /** @var PollIsActiveValidator */
    private $pollIsActiveValidator;

    /** @var PollTimeOrderValidator */
    private $PollTimeOrderValidator;

    /**
     * Interactor constructor.
     * @param EmployeeProviderInterface $employeeProvider
     * @param PollProviderInterface  $pollProvider
     * @param DishProviderInterface  $dishProvider
     * @param PollResultProviderInterface  $pollResultProvider
     * @param UserHasAccessToViewPollsValidator $userHasAccessToPollsValidator
     * @param PollIsActiveValidator $pollIsActiveValidator
     * @param PollTimeOrderValidator $PollTimeOrderValidator

     */
    public function __construct(
        EmployeeProviderInterface $employeeProvider,
        PollProviderInterface $pollProvider,
        DishProviderInterface $dishProvider,
        PollResultProviderInterface  $pollResultProvider,
        UserHasAccessToViewPollsValidator $userHasAccessToPollsValidator,
        PollTimeOrderValidator $PollTimeOrderValidator
    ) {
        $this->employeeProvider = $employeeProvider;
        $this->pollProvider = $pollProvider;
        $this->dishProvider = $dishProvider;
        $this->pollResultProvider = $pollResultProvider;
        $this->userHasAccessToPollsValidator = $userHasAccessToPollsValidator;
        $this->PollTimeOrderValidator = $PollTimeOrderValidator;
    }

    public function getActivePollResultOrder(int $employeeId,
                                  int $pollId,
                                  int $dishId,
                                  int $n,
                                  int $h) : PollResult
    {
        $employee = $this->employeeProvider->getEmployee($employeeId);

        $poll = $this->pollProvider->getPoll($pollId);

        $this->userHasAccessToPollsValidator->validate($employee->getUser());

        // здесь при формировании заказа проверяем время и день недели
        $this->PollTimeOrderValidator->validate($n, $h);

        $dish = $this->dishProvider->getDish($dishId);

        $pollResult = $this->pollResultProvider->getPollResult($pollId);
        return $pollResult;
    }




}
