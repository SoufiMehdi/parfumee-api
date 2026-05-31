<?php

declare(strict_types=1);

namespace App\Ecommerce\Application\UseCase\User;

use App\Ecommerce\Domain\Model\User\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Ecommerce\Domain\Service\User\PasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

readonly class RegisterUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher
    ) {}

    public function execute(string $email, string $plainPassword): void
    {
        // 1. Logique métier : vérifier si l'utilisateur existe déjà
        // (A ajouter dans le repository si besoin)

        // 2. Création de l'entité
        $hashedPassword = $this->passwordHasher->hash($plainPassword);
        $user = new User(
            Uuid::v4()->toRfc4122(),
            $email,
            $hashedPassword
        );

        // 3. Persistance
        $this->userRepository->save($user);
    }
}