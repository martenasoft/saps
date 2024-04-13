<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Interfaces\ChangeDataDayInterface;
use App\Entity\Interfaces\NameInterface;
use App\Entity\Interfaces\SlugIntrface;
use App\Entity\Interfaces\StatusInterface;
use App\Entity\Interfaces\TypeInterface;
use App\Entity\Menu;
use App\Helper\StringHelper;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class EntitySubscriber implements EventSubscriberInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private MenuRepository $menuRepository
    )
    {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (
            $method !== Request::METHOD_POST &&
            $method !== Request::METHOD_PATCH &&
            $method !== Request::METHOD_PUT &&
            $method !== Request::METHOD_DELETE
        ) {
            return;
        }

        if ($entity instanceof ChangeDataDayInterface) {
            $now = new \DateTimeImmutable('now');

            if (empty($entity->getCreatedAt())) {
                $entity->setCreatedAt($now);
            }

            if ($method === Request::METHOD_PATCH || $method === Request::METHOD_PUT) {
                $entity->setUpdatedAt($now);
            }
        }

        if ($entity instanceof SlugIntrface &&
            $entity instanceof NameInterface &&
            !empty($entity->getName()) &&
            empty($entity->getSlug())
        ) {
            $entity->setSlug(StringHelper::slug($entity->getName()));
        }

        if ($entity instanceof StatusInterface && $entity->getStatus() === null) {
            $entity->setStatus($entity->getDefaultStatus());
        }

        if ($entity instanceof TypeInterface && $entity->getType() === null) {
            $entity->setType($entity->getDefaultType());
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onKernelView', EventPriorities::PRE_WRITE],

        ];
    }
}
