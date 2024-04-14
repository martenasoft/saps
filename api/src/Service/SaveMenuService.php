<?php

namespace App\Service;

use App\Entity\Menu;
use App\Exceptions\ApiErrorException;
use App\Repository\MenuRepository;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SaveMenuService
{
    private $request;

    public function __construct(
        private RequestStack           $requestStack,
        private MenuRepository         $menuRepository,
        private EntityManagerInterface $entityManager
    )
    {
        $this->request = $this->requestStack->getCurrentRequest();
    }

    public function saveMenu(Menu $menu, array $uriVariables): Menu
    {
        $parent = null;
        try {

            if (!empty($menu->getParentId())) {
                $parent = $this->menuRepository->find($menu->getParentId());
            }

            if ($this->request->getMethod() === Request::METHOD_POST) {
                $this->menuRepository->create($menu, $parent);
                return $menu;
            }

            if (!$foundMenu = $this->menuRepository->find($uriVariables['id'] ?? 0)) {
                throw new NotFoundHttpException("Menu not found");
            }

            if (in_array($this->request->getMethod(), [Request::METHOD_PATCH, Request::METHOD_PUT])) {

                $foundMenu = MenuPrototype::get($foundMenu, $menu);
                $this->entityManager->persist($foundMenu);
                $this->entityManager->flush();

                if (str_contains($this->request->getRequestUri(), 'move-up')) {
                    $this->menuRepository->upDown($foundMenu);
                    return $foundMenu;
                }
                if (str_contains($this->request->getRequestUri(), 'move-down')) {
                    $this->menuRepository->upDown($foundMenu, false);
                    return $foundMenu;
                }

                $this->menuRepository->move($foundMenu, $parent);
                return $foundMenu;
            }
            throw new \Exception("Error saving menu. Not found an operation");
        } catch (UniqueConstraintViolationException $constraintViolationException) {
            throw new ApiErrorException(
                'Menu already exists',
                422
            );
        } catch (\Throwable|DriverException $exception) {

            throw new ApiErrorException(
                $exception->getMessage(),
                500
            );
        }
    }
}
