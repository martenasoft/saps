<?php

namespace App\Service;

use App\Entity\Interfaces\NodeInterface;
use App\Entity\Menu;
use App\Entity\Page;

class SavePageService
{

    public function create(Page $page): void
    {

    }
    private function initNewMenu(Page $page): ?Menu
    {
        $newMenu = $page->getMenu();
        if ($newMenu === null) {
            return null;
        }

        $newMenu
            ->setName($page->getName())
            ->setSlug($page->getSlug())
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setType(Menu::ITEM_MENU_TYPE)
            ->setStatus(Menu::STATUS_ACTIVE);
        return $newMenu;
    }
}
