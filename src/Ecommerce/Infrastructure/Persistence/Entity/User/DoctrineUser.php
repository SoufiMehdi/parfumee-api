<?php

declare(strict_types=1);

namespace App\Ecommerce\Infrastructure\Persistence\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class DoctrineUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private string $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    public function __construct(string $id, string $email, string $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): string { return $this->id; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): void { $this->password = $password; }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER'; // Garantir au moins un rôle
        return array_unique($roles);
    }

    public function setRoles(array $roles): void { $this->roles = $roles; }

    /**
     * Requis par UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * Requis par UserInterface
     */
    public function eraseCredentials(): void
    {
        // Si tu stockes des données temporaires en clair (ex: $plainPassword), efface-les ici
    }
}