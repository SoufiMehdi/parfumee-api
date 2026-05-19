<?php

namespace App\Tests\Unit\Ecommerce\Infrastructure\Persistence\Doctrine\Catalog;

use App\Ecommerce\Domain\Model\Catalog\Product;
use App\Ecommerce\Domain\Model\Catalog\Category;
use App\Ecommerce\Infrastructure\Persistence\Doctrine\Catalog\DoctrineProductRepository;
use App\Ecommerce\Infrastructure\Persistence\Entity\Catalog\DoctrineCategory;
use App\Ecommerce\Infrastructure\Persistence\Entity\Catalog\DoctrineProduct;
use App\Ecommerce\Infrastructure\Persistence\Mapper\Catalog\ProductMapper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class DoctrineProductRepositoryTest extends TestCase
{
    private EntityManagerInterface|MockObject $entityManager;
    private ProductMapper|MockObject $mapper;
    private DoctrineProductRepository $repository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->mapper = $this->createMock(ProductMapper::class);
        $this->repository = new DoctrineProductRepository($this->entityManager, $this->mapper);
    }

    public function testSaveNewProduct(): void
    {
        $product = $this->createMock(Product::class);
        $category = $this->createMock(Category::class);
        $doctrineCategory = new DoctrineCategory(
            'cat-1',
            'Category 1'
        );
        $doctrineProduct = new DoctrineProduct(
            'prod-1',
            'Product 1',
            'Description of Product 1',
            100.0,
            $doctrineCategory
        );

        $product->method('getId')->willReturn('prod-1');
        $product->method('getCategory')->willReturn($category);
        $category->method('getId')->willReturn('cat-1');

        $this->entityManager->expects($this->once())
            ->method('find')
            ->with(DoctrineProduct::class, 'prod-1')
            ->willReturn(null);

        $this->entityManager->expects($this->once())
            ->method('getReference')
            ->with(DoctrineCategory::class, 'cat-1')
            ->willReturn($doctrineCategory);

        $this->mapper->expects($this->once())
            ->method('toInfrastructure')
            ->with($product, $doctrineCategory)
            ->willReturn($doctrineProduct);

        $this->entityManager->expects($this->exactly(2))
            ->method('persist')
            ->with($doctrineProduct);
        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->repository->save($product);
    }

    public function testFindByIdReturnsProduct(): void
    {
        $productId = 'prod-1';
        $doctrineProduct = new DoctrineProduct();
        $product = $this->createMock(Product::class);
        $repository = $this->createMock(EntityRepository::class);

        $this->entityManager->method('getRepository')
            ->with(DoctrineProduct::class)
            ->willReturn($repository);

        $repository->expects($this->once())
            ->method('find')
            ->with($productId)
            ->willReturn($doctrineProduct);

        $this->mapper->expects($this->once())
            ->method('toDomain')
            ->with($doctrineProduct)
            ->willReturn($product);

        $result = $this->repository->findById($productId);

        $this->assertSame($product, $result);
    }
}
