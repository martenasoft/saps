<?php

namespace App\Service;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SaveMenuService
{
    private $request;

    public function __construct(
        private RequestStack   $requestStack,
        private MenuRepository $menuRepository
    )
    {
        $this->request = $this->requestStack->getCurrentRequest();
    }

    public function saveMenu(Menu $menu, array $uriVariables): void
    {
        if ($this->request->getMethod() === Request::METHOD_POST) {
            $this->menuRepository->create($menu, $this->getParentMenu($menu));
            return;
        }

        if (!$foundMenu = $this->menuRepository->find($uriVariables['id'] ?? 0)) {
            throw new NotFoundHttpException("Menu not found");
        }

        if ($this->request->getMethod() === Request::METHOD_POST) {
            $this->menuRepository->create($menu, $this->getParentMenu($menu));
        }

        if (in_array($this->request->getMethod(), [Request::METHOD_PATCH, Request::METHOD_PUT])) {
            $foundParentId = $foundMenu->getParentId();

            foreach (get_class_methods($menu) as $method) {
                $setter = preg_replace('/^get/', 'set', $method);
                if (strpos($method, 'get') === false ||
                    !method_exists($foundMenu, $method) ||
                    $method == 'getId' || !method_exists($foundMenu, $setter) ) {
                    continue;
                }
                $foundMenu->$setter($menu->$method());
            }

            $parent = $this->menuRepository->find($menu->getParentId() ?? 0);
            if (empty($foundParentId) && !empty($menu->getParentId())) {
                $this->menuRepository->create($foundMenu, $parent);
            } elseif (!empty($foundParentId) && !empty($menu->getParentId()) && $foundParentId != $menu->getParentId()) {
                $this->menuRepository->move($foundMenu, $parent);
            } elseif (!empty($foundParentId) && empty($menu->getParentId())) {
                $this->menuRepository->move($foundMenu);
            }
        }
    }

    public function getParentMenu(Menu $menu): Menu|null
    {
        if (empty($menu->getId())) {
            return null;
        }

        $queryBuilder = $this
            ->menuRepository
            ->getParentsByItemQueryBuilder(
                $menu,
                1,
                isIncludeCurrentNode: false);

        $result = $queryBuilder->getQuery()->setMaxResults(1)->getOneOrNullResult();
        dd($result);
        return $result;
    }
}
