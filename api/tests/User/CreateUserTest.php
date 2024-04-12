<?php

namespace App\Tests\User;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\UserRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class CreateUserTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    public function testEmptyEmail()
    {
        $client = self::createClient();
        $data = [
            "roles" => [
                "string"
            ],
            "plainPassword" => "123123",
            "isVerified" => true
        ];

        $response = $client->request('POST', 'https://localhost/users', [
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
        $this->assertEquals($result['detail'], 'email: This value should not be blank.');
    }

    public function testNotValidEmail()
    {
        $client = self::createClient();
        $data = [
            "email" => "user_example.com",
            "roles" => [
                "string"
            ],
            "plainPassword" => "123123",
            "isVerified" => true
        ];

        $response = $client->request('POST', 'https://localhost/users', [
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
        $this->assertEquals($result['detail'], 'email: This value is not a valid email address.');
    }

    public function successfulTest()
    {
        $client = self::createClient();
        $container = self::getContainer();
        $repository = $container->get(UserRepository::class);

        $data = [
            "email" => "user@example.com",
            "roles" => [
                "string"
            ],
            "plainPassword" => "123123",
            "isVerified" => true
        ];

        $response = $client->request('POST', 'https://localhost/users', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $data,
        ]);

        $this->assertResponseStatusCodeSame(201);
        $json = $response->toArray();
        $inDb = $repository->findOneByPassword(md5($data['plainPassword']));
        $this->assertNotEmpty($inDb);
        $this->assertTrue($json['email'] == $data['email']);
        $this->assertTrue($inDb->getEmail() == $data['email']);
    }
}
