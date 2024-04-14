<?php

namespace App\Tests\Menu;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\MenuRepository;

class MoveUpDownTest extends ApiTestCase
{
    private static $generatedMenuItems = [];
    private const NEW_NAME = 'new menu';

    public function testInit(): void
    {
        $container = self::getContainer();
        GetMenuCollectionTest::cleareMenu($container);
        self::$generatedMenuItems = GetMenuCollectionTest::initMenu($container);
        $generatedMenuItem = self::$generatedMenuItems[2];
        $newMenu = GetMenuCollectionTest::getMenu(self::NEW_NAME);

        $container->get(MenuRepository::class)->create($newMenu, $generatedMenuItem);
        sleep(1);
        $this->assertNotEmpty(self::$generatedMenuItems);
    }

    public function testSuccessUp()
    {
        $client = self::createClient();
        $data = CreateMenuTest::getMenu();
        $generatedMenuItem = self::getContainer()->get(MenuRepository::class)->findOneByName(self::NEW_NAME);

        $response = $client->request('PATCH', $_SERVER['HTTP_HOST'] . '/menus/move-up/' . $generatedMenuItem->getId(), [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/merge-patch+json'
            ],
            'json' => $data,
        ]);

        $response->toArray(false);
        $this->assertResponseStatusCodeSame(200);
        $result = GetMenuCollectionTest::get($client, [
            'lft[gte]' => 3,
            'rgt[lte]' => 6,
            'tree' => 1
        ]);

        $this->assertArrayHasKey('hydra:totalItems', $result);
        foreach ($result['hydra:member'] as $index => $item) {
            $this->assertEquals($item['name'], $index == 0 ? self::NEW_NAME : 'Menu 1 3', );
        }
    }


    public function testSuccessDown()
    {
        $client = self::createClient();
        $data = CreateMenuTest::getMenu();
        $generatedMenuItem = self::getContainer()->get(MenuRepository::class)->findOneByName(self::NEW_NAME);

        $response = $client->request('PATCH', $_SERVER['HTTP_HOST'] . '/menus/move-down/' . $generatedMenuItem->getId(), [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/merge-patch+json'
            ],
            'json' => $data,
        ]);

        $response->toArray(false);
        $this->assertResponseStatusCodeSame(200);
        $result = GetMenuCollectionTest::get($client, [
            'lft[gte]' => 3,
            'rgt[lte]' => 6,
            'tree' => 1
        ]);

        $this->assertArrayHasKey('hydra:totalItems', $result);
        foreach ($result['hydra:member'] as $index => $item) {
            $this->assertEquals($item['name'], $index == 1 ? self::NEW_NAME : 'Menu 1 3', );
        }
    }
}
