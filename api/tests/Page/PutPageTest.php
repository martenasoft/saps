<?php

namespace App\Tests\Page;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\PageRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class PutPageTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testErrorEmptyName()
    {
        $client = self::createClient();
        $container = self::getContainer();
        $page = GetPageTest::initPages($container, 1)[0];

        $response = $client->request('PUT', $_SERVER['HTTP_HOST'] . '/pages/'.$page->getId(), [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => [
                'name' => ''
            ],
        ]);

        $result = $response->toArray(false);
        $this->assertResponseStatusCodeSame(422);
        $this->assertArrayHasKey('detail', $result);
        $this->assertNotEmpty($result['detail']);
        $this->assertEquals($result['detail'], 'name: This value should not be blank.');
    }
    public function testErrorWithSameName()
    {
        $client = self::createClient();
        $container = self::getContainer();
        $page = GetPageTest::initPages($container, 1)[0];

        $response = $client->request('PUT', $_SERVER['HTTP_HOST'] . '/pages/'.$page->getId(), [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => [
                'name' => $page->getName()
            ],
        ]);

        $result = $response->toArray(false);
        $this->assertResponseStatusCodeSame(422);
        $this->assertArrayHasKey('detail', $result);
        $this->assertNotEmpty($result['detail']);
        $this->assertEquals($result['detail'], 'name: There is already a page with this name');
    }

    public function testSuccess()
    {
        $client = self::createClient();
        $container = self::getContainer();
        $page = GetPageTest::initPages($container, 1)[0];
        $newName = 'new name';

        $response = $client->request('PUT', $_SERVER['HTTP_HOST'] . '/pages/'.$page->getId(), [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => [
                'name' => $newName,
                'preview' => 'updated'
            ],
        ]);

        $result = $response->toArray(false);
        $this->assertResponseStatusCodeSame(200);
        $inDb = $container->get(PageRepository::class)->findOneByName($newName);
        $this->assertNotEmpty($inDb);
    }
}
