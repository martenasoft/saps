<?php

namespace App\Tests\Menu;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Menu;
use App\Helper\StringHelper;
use App\Repository\MenuRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class CreateMenuTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    public function testErrorEmptyNameAndSlug(): void
    {
        $client = self::createClient();
        $data = self::getMenu();
        unset($data['name']);
        unset($data['slug']);

        $response = $client->request('POST', $_SERVER['HTTP_HOST'] . '/menus', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $data,
        ]);

        $result = $response->toArray(false);

        $this->assertResponseStatusCodeSame(422);
        $this->assertArrayHasKey('violations', $result);
        $this->assertNotEmpty($result['violations']);
        $this->assertArrayHasKey(0, $result['violations']);
        $this->assertArrayHasKey(1, $result['violations']);


        $this->assertEquals($result['violations'][0]['propertyPath'], 'name');
        $this->assertEquals($result['violations'][0]['message'], 'This value should not be blank.');

        $this->assertEquals($result['violations'][1]['propertyPath'], 'slug');
        $this->assertEquals($result['violations'][1]['message'], 'This value should not be blank.');
    }

    public function testSuccess(): void
    {
        $client = self::createClient();
        $data = self::getMenu();
        $container = self::getContainer();

        $response = $client->request('POST', $_SERVER['HTTP_HOST'] . '/menus', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $data,
        ]);

        $response->toArray(false);
        $this->assertResponseStatusCodeSame(201);
        $inDb = $container->get(MenuRepository::class)->findOneByName($data['name']);
        $this->assertNotEmpty($inDb);
    }

    public function testSuccessSubItem(): void
    {
        $client = self::createClient();
        $data = self::getMenu();
        $container = self::getContainer();
        $menuRepository = $container->get(MenuRepository::class);


        $rootMenu = GetMenuCollectionTest::getMenu("root menu");
        $menuRepository->create($rootMenu);

        $data['parentId'] = $rootMenu->getId();

        $response = $client->request('POST', $_SERVER['HTTP_HOST'] . '/menus', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $data,
        ]);

        $result = $response->toArray(false);
        $this->assertResponseStatusCodeSame(201);
        $inDb = $container->get(MenuRepository::class)->findOneByName($data['name']);
        $this->assertNotEmpty($inDb);
        $this->assertEquals($result['lft'], 2);
        $this->assertEquals($result['rgt'], 3);
    }

    public static function getMenu(): array
    {
        $name = "new menu";
        return [
            "name" => $name,
            "path" => "string",
            "isBottomMenu" => true,
            "isLeftMenu" => true,
            "isTopMenu" => true,
            "status" => Menu::STATUS_ACTIVE,
            "type" => 1,
            "types" => [
                "1" => "Item menu",
                "2" => "External page"
            ],
            "slug" => StringHelper::slug($name),
            "parentId" => 0
        ];
    }
}
