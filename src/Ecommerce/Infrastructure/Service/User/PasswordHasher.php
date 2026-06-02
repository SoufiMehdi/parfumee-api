<?php 

namespace App\Ecommerce\Infrastructure\Service\User;

use App\Ecommerce\Domain\Service\User\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasher implements PasswordHasherInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function hash(string $plainPassword): string
    {
        // Ici, vous pouvez créer un utilisateur fictif pour utiliser le hasher de Symfony
        $user = new class {
            public function getPassword(): ?string
            {
                return null;
            }
        };

        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }
}