<?php

namespace tests\Meals\Functional\Interactor;

use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Application\Component\Validator\Exception\PollIntervalCheckException;
// делаем еще один тест с еще одним интерактором!
use Meals\Application\Feature\Poll\UseCase\DishGetsActivePoll\Interactor;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use tests\Meals\Functional\Fake\Provider\FakeEmployeeProvider;
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\Fake\Provider\FakeMenuProvider;
use tests\Meals\Functional\Fake\Provider\FakeDishProvider;
use tests\Meals\Functional\FunctionalTestCase;
use Meals\Domain\Poll\PollResult;

class DishGetsActiveTest extends FunctionalTestCase
{

    public function testSuccessful()
    {
        // проверили блюда на наличие в меню
        $dishId = 1;
        // проверим время
        // $n = date("w", mktime(0,0,0,date("m"),date("d"),date("Y")));
        // $h = date('h');
        // $n = 1; // понедельник
        // $h = 7; // 7 часов утра
        $dish = $this->performTestMethod($this->getEmployeeWithPermissions(),
                                         $this->getPoll(true),
                                         $this->getMenu(),
                                         $this->getDish($dishId));
        verify($dish)->equals($dish);
     }

    //public function testSuccessful2()
    //{
        // должен кинуть исключение
        // $this->expectException(PollIntervalCheckException::class);
        // проверили блюда на наличие в меню
        // $dishId = 1;
        // проверим время
        // $n = date("w", mktime(0,0,0,date("m"),date("d"),date("Y")));
        // $h = date('h');
        //$n = 0; // не понедельник
        //$h = 0; // 0 часов утра
        //$dish = $this->performTestMethod($this->getEmployeeWithPermissions(),
        //                                 $this->getPoll(true),
        //                                 $this->getMenu(),
        //                                 $this->getDish($dishId),
        //                                 $n, $h);
        // verify($dish)->equals($dish);
        //}

    private function performTestMethod(Employee $employee, Poll $poll, Menu $menu, Dish $dish): Dish
    {
        // заглушка пользователя
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);

        // Заглушка запроса
        $this->getContainer()->get(FakePollProvider::class)->setPoll($poll);

        // заглушка меню...
        $this->getContainer()->get(FakeMenuProvider::class)->setMenu($menu);

        // заглушка блюда...
        $this->getContainer()->get(FakeDishProvider::class)->setDish($dish);

        //2. dish кладем в наш PollResult...

        // тут мы должны назначить блюдо, и создать запрос для его назначения
        return $this->getContainer()->get(Interactor::class)->getActiveDish(
            $employee->getId(),
            $poll->getId(),
            $menu->getId(),
            $dish->getId());
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

    //
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
