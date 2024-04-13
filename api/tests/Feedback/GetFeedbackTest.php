<?php

namespace App\Tests\Feedback;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Feedback;
use App\Entity\Page;
use App\Helper\StringHelper;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class GetFeedbackTest extends ApiTestCase
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
        self::initFeedbacks($container);
        $result = $this->get($client);
        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey('hydra:totalItems', $result);
        $this->assertEquals($result['hydra:totalItems'], 15);
        $this->assertEquals(count($result['hydra:member']), 10);
    }

    private function get($client): array
    {
        $response = $client->request('GET', $_SERVER['HTTP_HOST'] . '/feedback?page=1', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ]
        ]);
        $result = $response->toArray(false);
        return $result;
    }

    public static function initFeedbacks($container, int $max = 15): array
    {
        $entityManager = $container->get(EntityManagerInterface::class);
        $result = [];

        for ($i = 1; $i <= 15; $i++) {
            $email = "feedback_{$i}@mail.com";
            $page = new Feedback();
            $page
                ->setFromEmail($email)
                ->setSubject("subject $i")
                ->setText("text $i")
                ->setStatus($page->getDefaultStatus())
                ->setCreatedAt(new \DateTimeImmutable('now'));
            $entityManager->persist($page);
            $result[] = $page;
        }
        $entityManager->flush();
        return $result;
    }
}
