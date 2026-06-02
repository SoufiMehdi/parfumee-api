<?php 

namespace App\Ecommerce\Infrastructure\Service\User;

use App\Ecommerce\Domain\Service\User\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class PasswordHasher implements PasswordHasherInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function hash(string $plainPassword): string
    {
        $user = new class implements PasswordAuthenticatedUserInterface {
            public function getPassword(): ?string
            {
                return null;
            }
        };

        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }
}