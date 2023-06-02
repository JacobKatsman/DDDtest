<?php

namespace Meals\Application\Component\Provider;

use Meals\Domain\Menu\Menu;

// интерфейс для взятия меню
interface MenuProviderInterface
{
    public function getMenu(int $menuId): Menu;
}
