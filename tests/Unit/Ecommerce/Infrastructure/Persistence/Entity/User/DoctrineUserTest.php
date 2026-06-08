<?php

declare(strict_types=1);

namespace App\Tests\Unit\Ecommerce\Infrastructure\Persistence\Entity\User;

use App\Ecommerce\Infrastructure\Persistence\Entity\User\DoctrineUser;
use PHPUnit\Framework\TestCase;

class DoctrineUserTest extends TestCase
{
    private const ID = '550e8400-e29b-41d4-a716-446655440000';
    private const EMAIL = 'test@example.com';
    private const PASSWORD = 'hashed_password';

    private DoctrineUser $user;

    protected function setUp(): void
    {
        $this->user = new DoctrineUser(self::ID, self::EMAIL, self::PASSWORD);
    }

    public function testGettersReturnCorrectValues(): void
    {
        $this->assertEquals(self::ID, $this->user->getId());
        $this->assertEquals(self::EMAIL, $this->user->getEmail());
        $this->assertEquals(self::PASSWORD, $this->user->getPassword());
        $this->assertEquals(self::EMAIL, $this->user->getUserIdentifier());
    }

    public function testSettersUpdateValues(): void
    {
        $newEmail = 'new@example.com';
        $newPassword = 'new_password';

        $this->user->setEmail($newEmail);
        $this->user->setPassword($newPassword);

        $this->assertEquals($newEmail, $this->user->getEmail());
        $this->assertEquals($newPassword, $this->user->getPassword());
    }

    public function testGetRolesAlwaysIncludesRoleUser(): void
    {
        $this->assertContains('ROLE_USER', $this->user->getRoles());

        $this->user->setRoles(['ROLE_ADMIN']);
        $roles = $this->user->getRoles();

        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_USER', $roles);
    }

    public function testGetRolesHandlesDuplicates(): void
    {
        $this->user->setRoles(['ROLE_USER', 'ROLE_USER']);
        $roles = $this->user->getRoles();

        $this->assertCount(1, $roles);
        $this->assertEquals(['ROLE_USER'], $roles);
    }

    public function testEraseCredentialsDoesNotThrowException(): void
    {
        $this->user->eraseCredentials();
        $this->assertTrue(true);
    }
}