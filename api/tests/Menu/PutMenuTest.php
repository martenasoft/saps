<?php

namespace App\Tests\Menu;


use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\MenuRepository;

class PutMenuTest extends ApiTestCase
{
    public function testSuccess(): void
    {
        $client = self::createClient();
        $data = CreateMenuTest::getMenu();
        $container = self::getContainer();
        GetMenuCollectionTest::cleareMenu($container);
        $data['name'] = 'changed menu name';
        $generatedMenuItem = GetMenuCollectionTest::initMenu($container, 1, 1)[0];
        $response = $client->request('PUT', $_SERVER['HTTP_HOST'] . '/menus/'.$generatedMenuItem->getId(), [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $data,
        ]);

        $response->toArray(false);
        $this->assertResponseStatusCodeSame(200);
        $inDb = $container->get(MenuRepository::class)->findOneById($generatedMenuItem->getId());
        $this->assertEquals($inDb->getName(), $data['name']);
    }
}
