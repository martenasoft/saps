<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Menu;
use App\Service\SaveMenuService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
#[Autowire(service: 'api_platform.state_processor')]
class MenuStateProcessor implements ProcessorInterface
{

    public function __construct(private SaveMenuService $saveMenuService)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Menu|null
    {
        if (!$data instanceof Menu) {
            return null;
        }


        $this->saveMenuService->saveMenu($data, $uriVariables);
        return $data;
    }
}
