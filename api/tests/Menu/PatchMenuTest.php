<?php

namespace App\Tests\Menu;


use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\MenuRepository;
use PHPUnit\Framework\Attributes\Depends;

class PatchMenuTest extends ApiTestCase
{
    private static $generatedMenuItems = [];
    public function testInit(): void
    {
        $client = self::createClient();
        $data = CreateMenuTest::getMenu();
        $container = self::getContainer();
        GetMenuCollectionTest::cleareMenu($container);
        self::$generatedMenuItems = GetMenuCollectionTest::initMenu($container);
        sleep(1);
        $this->assertNotEmpty(self::$generatedMenuItems);
    }
    public function testSuccessMove()
    {
        $client = self::createClient();
        $data = CreateMenuTest::getMenu();
        $generatedMenuItem =  self::$generatedMenuItems[1] ;
        $data['name'] = 'changed menu name';
        $newParent = null;
        foreach ( self::$generatedMenuItems  as $item) {
            if ($item->getTree() == 2) {
                $newParent = $item;
                break;
            }
        }

        $this->assertNotEmpty($newParent);
        $data['parentId'] = $newParent->getId();

        $response = $client->request('PUT', $_SERVER['HTTP_HOST'] . '/menus/'.$generatedMenuItem->getId(), [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $data,
        ]);

        $response->toArray(false);
        $result = GetMenuCollectionTest::get($client);
        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey('hydra:totalItems', $result);
        $this->assertEquals($result['hydra:totalItems'], 15);

        foreach ($result['hydra:member'] as $item) {
            if ($item['tree'] == 1) {
                $this->assertNotEquals($item['name'], $data['name']);
            }

            if ($item['tree'] == 2 && $item['id'] == 2) {
                $this->assertEquals($item['name'], $data['name']);
                break;
            }
        }
    }
}
