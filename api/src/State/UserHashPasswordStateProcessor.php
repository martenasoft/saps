<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Autowire(service: 'api_platform.state_processor')]
class UserHashPasswordStateProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface          $processor,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    /**
     * @inheritdoc
     * @return T2
     */
    public function process(mixed $user, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$user->getPlainPassword()) {
            return $this->processor->process($user, $operation, $uriVariables, $context);
        }


        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();

        return $this->processor->process($user, $operation, $uriVariables, $context);
    }
}
