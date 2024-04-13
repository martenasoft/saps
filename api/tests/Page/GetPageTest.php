<?php

namespace App\Tests\Page;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Page;
use App\Helper\StringHelper;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class GetPageTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    public function testEmptyList()
    {
        $client = self::createClient();
        $result = $this->get($client);

        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey('hydra:totalItems', $result);
        $this->assertEquals($result['hydra:totalItems'], 0);
    }

    public function testList()
    {
        $client = self::createClient();
        $container = self::getContainer();
        self::initPages($container);
        $result = $this->get($client);
        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey('hydra:totalItems', $result);
        $this->assertEquals($result['hydra:totalItems'], 15);
        $this->assertEquals(count($result['hydra:member']), 10);
    }

    private function get($client): array
    {
        $response = $client->request('GET', $_SERVER['HTTP_HOST'] . '/pages?page=1', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ]
        ]);
        $result = $response->toArray(false);
        return $result;
    }

    public static function initPages($container, int $max = 15): array
    {
        $entityManager = $container->get(EntityManagerInterface::class);
        $result = [];

        for ($i = 1; $i <= 15; $i++) {
            $name = "page $i";
            $page = new Page();
            $page
                ->setName($name)
                ->setSlug(StringHelper::slug($name))
                ->setStatus(Page::STATUS_ACTIVE)
                ->setType(Page::PAGE_TYPE)
                ->setCreatedAt(new \DateTimeImmutable('now'));
            $entityManager->persist($page);
            $result[] = $page;
        }
        $entityManager->flush();
        return $result;
    }
}
