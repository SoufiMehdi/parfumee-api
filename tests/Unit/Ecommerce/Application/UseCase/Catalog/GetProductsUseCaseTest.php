<?php

namespace App\Tests\Unit\Ecommerce\Application\UseCase\Catalog;

use App\Ecommerce\Application\UseCase\Catalog\GetProductsUseCase;
use App\Ecommerce\Domain\Model\Catalog\Category;
use App\Ecommerce\Domain\Model\Catalog\Product;
use App\Ecommerce\Domain\Repository\Catalog\ProductRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetProductsUseCaseTest extends TestCase
{
    public function testExecuteReturnsListOfProducts(): void
    {
        // 1. On crée le mock du repository
        $productRepository = $this->createMock(ProductRepositoryInterface::class);

        // 2. On prépare de fausses données du Domaine
        $category = new Category('cat-1', 'Parfums', 'parfums');
        $product1 = new Product('p1', 'Oud Royal', 'oud-royal', 120.0, $category, []);
        $product2 = new Product('p2', 'Rose Night', 'rose-night', 85.0, $category, []);

        $productList = [$product1, $product2];

        // 3. On configure le mock pour retourner cette liste
        $productRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($productList);

        // 4. On instancie et on exécute
        $useCase = new GetProductsUseCase($productRepository);
        $result = $useCase->execute();

        // 5. Assertions
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertSame($productList, $result);
        $this->assertInstanceOf(Product::class, $result[0]);
    }
}