<?php

declare(strict_types=1);

namespace App\Tests\Unit\Ecommerce\Infrastructure\Persistence\Doctrine\User;

use App\Ecommerce\Domain\Model\User\User;
use App\Ecommerce\Domain\Repository\User\UserRepositoryInterface;
use App\Ecommerce\Infrastructure\Persistence\Doctrine\User\DoctrineUserRepository;
use App\Ecommerce\Infrastructure\Persistence\Entity\User\DoctrineUser;
use App\Ecommerce\Infrastructure\Persistence\Mapper\User\UserMapper;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Uid\Uuid;
class DoctrineUserRepositoryTest extends TestCase
{
    private EntityManagerInterface&MockObject $entityManager;
    private UserMapper&MockObject $mapper;
    private DoctrineUserRepository $repository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->mapper = $this->createMock(UserMapper::class);
        $this->repository = new DoctrineUserRepository($this->entityManager, $this->mapper);
    }

    public function testFindByIdReturnsUser(): void
    {
        $id = new Uuid(Uuid::v4()->toRfc4122());
        $user = $this->createMock(User::class);

        $this->entityManager
            ->expects($this->once())
            ->method('find')
            ->with(User::class, $id)
            ->willReturn($user);

        $result = $this->repository->findById($id);

        $this->assertSame($user, $result);
    }

    public function testSavePersistsAndFlushes(): void
    {
        $user = $this->createMock(User::class);
        $doctrineUser = $this->createMock(DoctrineUser::class);

        $this->mapper
            ->expects($this->once())
            ->method('toInfrastructure')
            ->with($user)
            ->willReturn($doctrineUser);

        $this->entityManager->expects($this->once())->method('persist')->with($doctrineUser);
        $this->entityManager->expects($this->once())->method('flush');

        $this->repository->save($user);
    }

    public function testDeleteRemovesAndFlushes(): void
    {
        $id = new Uuid(Uuid::v4()->toRfc4122());
        $user = $this->createMock(User::class);

        $this->entityManager
            ->expects($this->once())
            ->method('find')
            ->with(User::class, $id)
            ->willReturn($user);

        $this->entityManager->expects($this->once())->method('remove')->with($user);
        $this->entityManager->expects($this->once())->method('flush');

        $this->repository->delete($id->string);
    }

    public function testDeleteThrowsExceptionIfUserNotFound(): void
    {
        $id = new Uuid(Uuid::v4()->toRfc4122());

        $this->entityManager
            ->method('find')
            ->willReturn(null);

        $this->expectException(\TypeError::class); // Ou une exception métier personnalisée si vous en avez une
        
        $this->repository->delete($id);
    }
}
