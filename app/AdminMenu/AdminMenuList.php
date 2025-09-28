<?php

namespace App\AdminMenu;

class AdminMenuList
{
    public private(set) array $list;

    public function add(AdminMenu $item)
    {
        $this->list[] = $item;
    }
}
