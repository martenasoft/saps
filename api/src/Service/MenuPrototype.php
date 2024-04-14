<?php

namespace App\Service;

use App\Entity\Menu;

class MenuPrototype
{
    private const EXCLUDE_METHODS = [
        'getId',
        'getLft',
        'getRgt',
        'getTree',
        'getLvl',
        'getParentId',
    ];
    public static function get(Menu $menuTo, Menu $menuFrom): Menu
    {
        foreach (get_class_methods($menuFrom) as $method) {
            $setter = preg_replace('/^get/', 'set', $method);
            if (!str_contains($method, 'get') ||
                !method_exists($menuTo, $method) ||
                in_array($method, self::EXCLUDE_METHODS) ||
                !method_exists($menuTo, $setter)) {
                continue;
            }
            $menuTo->$setter($menuFrom->$method());
        }

        return $menuTo;
    }

}
