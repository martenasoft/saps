<?php

namespace App\Tests\Page;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\PageRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class CreatePageTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    public function testErrorEmptyName(): void
    {
        $client = self::createClient();
        $data = self::getPageData();
        unset($data['name']);

        $response = $client->request('POST', $_SERVER['HTTP_HOST'] . '/pages', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $data,
        ]);

        $result = $response->toArray(false);
        $this->assertResponseStatusCodeSame(422);
        $this->assertArrayHasKey('detail', $result);
        $this->assertNotEmpty($result['detail']);
        $this->assertEquals($result['detail'], 'name: This value should not be blank.');
    }

    public function testNameNotUnique(): void
    {
        $client = self::createClient();
        $container = self::getContainer();
        $pageName = GetFeedbackTest::initPages($container, 1)[0];
        $data = self::getPageData();
        $data['name'] = $pageName->getName();

        $response = $client->request('POST', $_SERVER['HTTP_HOST'] . '/pages', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $data,
        ]);

        $result = $response->toArray(false);
        $this->assertResponseStatusCodeSame(422);
        $this->assertArrayHasKey('detail', $result);
        $this->assertNotEmpty($result['detail']);
        $this->assertEquals($result['detail'], 'name: There is already a page with this name');

    }
    public function testSuccess(): void
    {
        $client = self::createClient();
        $container = self::getContainer();
        $data = self::getPageData();

        $response = $client->request('POST', $_SERVER['HTTP_HOST'] . '/pages', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $data,
        ]);

        $response->toArray(false);
        $this->assertResponseStatusCodeSame(201);
        $inDb = $container->get(PageRepository::class)->findOneByName($data['name']);
        $this->assertNotEmpty($inDb);
    }

    public static function getPageData(): array
    {
        return [
            "name" => "new page",
            "preview" => "string",
            "body" => "string",
            "position" => 0,
            "publicAt" => "2024-04-13T05:54:58.642Z",
            "image" => "string",
            "isPreviewOnMain" => true,
            "seoTitle" => "string",
            "seoDescription" => "string",
            "seoKeywords" => "string",
            "ogTitle" => "string",
            "ogDescription" => "string",
            "ogUrl" => "string",
            "ogImage" => "string",
            "ogType" => "string",
            "status" => 0,
            "slug" => "string",
            "type" => 0
        ];
    }
}
