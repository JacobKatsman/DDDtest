<?php

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\MenuProviderInterface;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Menu\MenuList;

class FakeMenuProvider implements MenuProviderInterface
{
    /** @var Poll */
    private $menu;

    // здесь поправить
    public function getMenu(int $menuId): Menu
    {
        return $this->menu;
    }

    /**
     * @param Menu $menu
     */
    public function setMenu(Menu $menu): void
    {
        $this->menu = $menu;
    }

}
