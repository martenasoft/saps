<?php

namespace App\Tests\User;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Tests\Traits\RegistrationTrait;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class RegistrationTest extends ApiTestCase
{
    use RefreshDatabaseTrait, RegistrationTrait;
    public function testSuccess()
    {
        $this->registrationSuccess();
    }
    public function testErrorEmptyPassword()
    {
        $client = self::createClient();
        $data = [
            'email' => 'test123@example.com',
            'plainPassword' => '',
        ];

        $response = $client->request('POST', 'https://localhost/api/registration', [
            'headers' => ['Content-Type' => 'application/ld+json'],
            'json' => $data,
        ]);
        $result = $response->toArray(false);

        $this->assertResponseStatusCodeSame(422);
        $this->assertTrue($result['detail'] &&
            $result['detail'] == 'plainPassword: This value should not be blank.');
    }

    public function testErrorEmailExists()
    {
        $client = self::createClient();
        $container = self::getContainer();

        $data = [
            'email' => 'test123@example.com',
            'plainPassword' => '123123',
        ];

        $entityManager = $container->get(EntityManagerInterface::class);
        $user = new User();
        $user->setEmail($data['email']);
        $user->setStatus(User::STATUS_ACTIVE);
        $user->setPassword($container->get('security.user_password_hasher')->hashPassword($user, $data['plainPassword']));

        $entityManager->persist($user);
        $entityManager->flush();

        $response = $client->request('POST', 'https://localhost/api/registration', [
            'headers' => ['Content-Type' => 'application/ld+json'],
            'json' => $data,
        ]);

        $result = $response->toArray(false);
        $this->assertResponseStatusCodeSame(422);
        $this->assertTrue(!empty($result['detail']));
        $this->assertTrue($result['detail'] == 'email: There is already an account with this email');
    }
}
