<?php

namespace App\Tests\Traits;

use App\Repository\UserRepository;

trait RegistrationTrait
{
    private function registrationSuccess(): void
    {
        $client = self::createClient();
        $container = self::getContainer();
        $repository = $container->get(UserRepository::class);

        $data = [
            'email' => 'test123@example.com',
            'plainPassword' => '123123',
        ];

        $response = $client->request('POST', 'https://localhost/api/registration', [
            'headers' => ['Content-Type' => 'application/ld+json'],
            'json' => $data,
        ]);

        $this->assertResponseStatusCodeSame(201);
        $json = $response->toArray();
        $inDb = $repository->findOneByPassword(md5("123123"));
        $this->assertNotEmpty($inDb);
        $this->assertTrue($json['email'] == $data['email']);
        $this->assertTrue($inDb->getEmail() == $data['email']);
    }
}
