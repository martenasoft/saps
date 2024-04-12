<?php

namespace App\Tests\User;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PatchPutDeleteTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    private const NEW_USER_EMAIL = 'new@user.com';

    public function testPutErrorEmailExists()
    {
        $data = [
            "email" => self::NEW_USER_EMAIL,
            "roles" => [
                "string1"
            ],
            "plainPassword" => "string",
            "isVerified" => true
        ];

        $response = $this->sendData('PUT', $data, [
            'accept' => 'application/ld+json',
            'Content-Type' => 'application/ld+json'
        ]);

        $result = $response->toArray(false);

        $this->assertResponseStatusCodeSame(422);
        $this->assertArrayHasKey('detail', $result);
        $this->assertNotEmpty($result['detail']);
        $this->assertEquals($result['detail'], 'email: There is already an account with this email');
    }

    public function testPutSuccess()
    {
        $data = [
            "email" => "user1@example.com",
            "roles" => [
                "string1"
            ],
            "plainPassword" => "string",
            "isVerified" => true
        ];

        $response = $this->sendData('PUT', $data, [
            'accept' => 'application/ld+json',
            'Content-Type' => 'application/ld+json'
        ]);

        $result = $response->toArray(false);

        $this->assertResponseStatusCodeSame(200);
        $this->assertNotEquals(self::NEW_USER_EMAIL, $result['email']);
        $container = self::getContainer();
        $repository = $container->get(UserRepository::class);
        $inDb = $repository->findOneByEmail($result['email']);
        $this->assertNotEmpty($inDb);
    }

    public function testPatchSuccessful()
    {
        $data = [
            'email' => 'test-xx@user.com',
        ];

        $response = $this->sendData('PATCH', $data, [
            'accept' => 'application/ld+json',
            'Content-Type' => 'application/merge-patch+json'
        ]);

        $result = $response->toArray(false);

        $this->assertResponseStatusCodeSame(200);
        $this->assertNotEquals(self::NEW_USER_EMAIL, $result['email']);
        $container = self::getContainer();
        $repository = $container->get(UserRepository::class);
        $inDb = $repository->findOneByEmail($result['email']);
        $this->assertNotEmpty($inDb);
    }

    private function sendData(string $method, array $data, array $headers): ResponseInterface
    {
        $client = self::createClient();
        $user = $this->initUser($data);

        return $client->request($method, 'https://localhost/users/' . $user->getId(), [
            'headers' => $headers,
            'json' => $data,
        ]);
    }

    private function initUser(array $data): User
    {
        $container = self::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $user = new User();
        $user->setEmail(self::NEW_USER_EMAIL);
        $user->setStatus(User::STATUS_ACTIVE);
        $user->setPassword($container->get('security.user_password_hasher')->hashPassword($user, $data['plainPassword'] ?? '123123'));

        $entityManager->persist($user);
        $entityManager->flush();
        return $user;
    }
}
