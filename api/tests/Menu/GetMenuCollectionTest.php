<?php

namespace App\Tests\Menu;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Menu;
use App\Helper\StringHelper;
use App\Repository\MenuRepository;

class GetMenuCollectionTest extends ApiTestCase
{
    public function testEmptyList(): void
    {
        $client = self::createClient();
        $result = $this->get($client);

        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey('hydra:totalItems', $result);
        $this->assertEquals($result['hydra:totalItems'], 0);
    }
    public function testList(): void
    {
        $client = self::createClient();
        $container = self::getContainer();
        $menu = self::initMenu($container);

        dd($menu);
    }
    public static function initMenu($container): array
    {
        $menuRepository = $container->get(MenuRepository::class);
        $result = [];
        $now = new \DateTimeImmutable('now');

        for ($i = 1; $i <= 15; $i++) {
            $parent = null;
            for ($f = 1; $f <= 3; $f++) {

                $name = "Menu $i $f";
                $menu = new Menu();
                $menu
                    ->setName($name)
                    ->setStatus($menu->getDefaultStatus())
                    ->setType($menu->getDefaultType())
                    ->setCreatedAt($now)
                    ->setSlug(StringHelper::slug($name));
                $menuRepository->create($menu, $parent);
                $result[$name] = $menu;
                if ($parent === null) {
                    $parent = $menu;
                }
            }
        }
        return $result;
    }

    private function get($client, array $options = []): array
    {
        $response = $client->request('GET', $_SERVER['HTTP_HOST'] . '/menus', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ]
        ],
            $options
        );
        $result = $response->toArray(false);
        return $result;
    }
}
