<?php

namespace App\Tests\Traits;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

trait UserTrait
{
    private function initUser(array $data): User
    {
        $container = self::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $user = new User();
        $user->setEmail(self::NEW_USER_EMAIL);
        $user->setStatus(User::STATUS_ACTIVE);
        if (!empty($data['roles'])) {
            $user->setRoles($data['roles']);
        }
        $user->setIsVerified(true);
        $user->setPassword($container->get('security.user_password_hasher')->hashPassword($user, $data['plainPassword'] ?? '123123'));

        $entityManager->persist($user);
        $entityManager->flush();
        return $user;
    }

    private function authentication(string $email, string $password, Client $client): array
    {
        $response = $client->request('POST', $_SERVER['HTTP_HOST'] . '/api/authentication', [
            'headers' => [
                'accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'email' => $email,
                'password' => $password
            ],
        ]);

        return $response->toArray(false);
    }
}
