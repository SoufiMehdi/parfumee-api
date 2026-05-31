<?php

namespace App\Ecommerce\Domain\Model\User;

use App\Ecommerce\Domain\ValueObject\User\Email;
use Symfony\Component\Uid\Uuid;

class User
{
    private array $roles;

    public function __construct(
        private readonly Uuid $id,
        private Email $email,
        private string $passwordHash,
        private string $firstName,
        private string $lastName,
        array $roles = ['ROLE_USER']
    ) {
        $this->roles = $roles;
    }

    // Getters
    public function getId(): Uuid { return $this->id; }
    public function getEmail(): Email { return $this->email; }
    public function getPasswordHash(): string { return $this->passwordHash; }
    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getRoles(): array { return $this->roles; }

    // Logique métier
    public function updateProfile(string $firstName, string $lastName): void
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function changePassword(string $newPasswordHash): void
    {
        // Ici, on pourrait ajouter des règles métier sur la complexité si besoin
        $this->passwordHash = $newPasswordHash;
    }
}