<?php

namespace App\Tests\Page;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\PageRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class DeletePageTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    public function testSuccess()
    {
        $client = self::createClient();
        $container = self::getContainer();
        $page = GetPageTest::initPages($container, 1)[0];
        $id = $page->getId();
        $client->request('DELETE', $_SERVER['HTTP_HOST'] . '/pages/'.$id, [
            'headers' => [
                'accept' => '*/*'
            ]
        ]);

        $this->assertResponseStatusCodeSame(204);
        $inDb = $container->get(PageRepository::class)->find($id);
        $this->assertEmpty($inDb);
    }
}
