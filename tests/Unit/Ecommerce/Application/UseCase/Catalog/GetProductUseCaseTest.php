<?php

namespace App\Tests\Unit\Ecommerce\Application\UseCase\Catalog;

use App\Ecommerce\Application\UseCase\Catalog\GetProductUseCase;
use App\Ecommerce\Domain\Model\Catalog\Category;
use App\Ecommerce\Domain\Model\Catalog\Product;
use App\Ecommerce\Domain\Repository\Catalog\ProductRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetProductUseCaseTest extends TestCase
{
    private $productRepository;
    private $useCase;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->useCase = new GetProductUseCase($this->productRepository);
    }

    public function testExecuteReturnsProductWhenFound(): void
    {
        // 1. Arrange
        $productId = 'uuid-123';
        $category = new Category('cat-1', 'Parfums', 'parfums');
        $expectedProduct = new Product(
            $productId,
            'Mon Parfum',
            'mon-parfum',
            95.0,
            $category,
            ['size' => '100ml']
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($expectedProduct);

        // 2. Act
        $result = $this->useCase->execute($productId);

        // 3. Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('Mon Parfum', $result->getName());
        $this->assertSame($expectedProduct, $result);
    }

    public function testExecuteReturnsNullWhenProductNotFound(): void
    {
        // 1. Arrange
        $productId = 'non-existent-id';

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn(null);

        // 2. Act
        $result = $this->useCase->execute($productId);

        // 3. Assert
        $this->assertNull($result);
    }
}