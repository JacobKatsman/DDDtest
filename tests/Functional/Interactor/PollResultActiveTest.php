<?php

namespace tests\Meals\Functional\Interactor;

use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Application\Component\Validator\Exception\PollIntervalCheckException;
use Meals\Application\Feature\Poll\UseCase\PollResultGetsOrder\Interactor;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use tests\Meals\Functional\Fake\Provider\FakeEmployeeProvider;
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\Fake\Provider\FakePollResultProvider;
use tests\Meals\Functional\Fake\Provider\FakeMenuProvider;
use tests\Meals\Functional\Fake\Provider\FakeDishProvider;
use tests\Meals\Functional\FunctionalTestCase;

class PollResultActiveTest extends FunctionalTestCase
{

    public function testSuccessfulTime()
    {
        // проверили блюда на наличие в меню
        $dishId = 1;
        // проверим время
        $n = 1; // понедельник
        $h = 7; // 7 часов утра
        $employeeFloor = 6;
        $dish = $this->performTestMethod($this->getEmployeeWithPermissions(),
                                         $this->getPoll(true),
                                         $this->getPollResult($employeeFloor),
                                         $this->getMenu(),
                                         $this->getDish($dishId),
                                         $n, $h);
        verify($dish)->equals($dish);
     }

    public function testNonSuccessfulTime()
    {
        // должен кинуть исключение
        $this->expectException(PollIntervalCheckException::class);
        // проверили блюда на наличие в меню
        $dishId = 1;
        // проверим время
        $n = 0; // НЕ понедельник
        $h = 0; // 0 часов утра
        $employeeFloor = 6;
        $dish = $this->performTestMethod($this->getEmployeeWithPermissions(),
                                         $this->getPoll(true),
                                         $this->getPollResult($employeeFloor),
                                         $this->getMenu(),
                                         $this->getDish($dishId),
                                         $n, $h);
         verify($dish)->equals($dish);
    }

    private function performTestMethod(Employee $employee,
                                       Poll $poll,
                                       PollResult $pollResult,
                                       Menu $menu,
                                       Dish $dish,
                                       int $n, int $h): PollResult
    {
        // заглушка пользователя
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);

        // Заглушка запроса
        $this->getContainer()->get(FakePollProvider::class)->setPoll($poll);

        // Заглушка ответа
        $this->getContainer()->get(FakePollResultProvider::class)->setPollResult($pollResult);

        // заглушка меню...
        $this->getContainer()->get(FakeMenuProvider::class)->setMenu($menu);

        // заглушка блюда...
        $this->getContainer()->get(FakeDishProvider::class)->setDish($dish);

        return $this->getContainer()->get(Interactor::class)->getActivePollResultOrder(
            $employee->getId(),
            $poll->getId(),
            $dish->getId(),
            $n, $h);
    }

    private function getEmployeeWithPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithPermissions(): User
    {
        return new User(
            1,
            new PermissionList(
                [
                    new Permission(Permission::VIEW_ACTIVE_POLLS),
                ]
            ),
        );
    }

    private function getDish(int $dishId): Dish
    {
        return new Dish($dishId, 'test Title', 'test Description');
    }

    // создали нашего заказчика для теста
    private function  getPollResult(int $employeeFloor) : PollResult
    {
        return new PollResult (1, new Poll(1,true,new Menu(1,'title', new DishList([new Dish (1, "test1", "desc2")]))),
                                  new Employee(1, $this->getUserWithPermissions(),4,'Surname'),
                                  new Dish (1, "test1", "desc2"),
                                  $employeeFloor);
    }

    private function getPoll(bool $active): Poll
    {
        return new Poll(
            1,
            $active,
            new Menu(
                1,
                'title',
                new DishList([new Dish (1, "test1", "desc2")]),
            ),
            );
    }
    // поправить TODO

    private function getMenu(): Menu
    {
        return new Menu(
                1,
                'title',
                new DishList([new Dish (1, "test1", "desc2")]),
        );
    }
}
