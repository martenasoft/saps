<?php

namespace App\Tests\Authentication;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Traits\UserTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class AuthenticationTest extends ApiTestCase
{
    use
        RefreshDatabaseTrait,
        UserTrait
        ;
    private const NEW_USER_EMAIL = 'new@user.com';
    public function testSuccess(): void
    {
        $client = self::createClient();
        $data = [
            "email" => "user@example.com",
            "roles" => [
                "ROLE_ADMIN"
            ],
            "plainPassword" => "123123",
            "isVerified" => true
        ];

        $this->initUser($data);

        $result = $this->authentication(self::NEW_USER_EMAIL, $data['plainPassword'], $client);
        $this->assertArrayHasKey('token', $result);
        $this->assertArrayHasKey('refresh_token', $result);
        $this->assertNotEmpty($result['token']);
        $this->assertNotEmpty($result['refresh_token']);
    }
}
