<?php

namespace App\Tests\Unit\Ecommerce\Application\UseCase\Catalog;

use App\Ecommerce\Application\DTO\Catalog\UpdateProductDto; // Assure-toi du nom du DTO
use App\Ecommerce\Application\UseCase\Catalog\UpdateProductUseCase;
use App\Ecommerce\Domain\Model\Catalog\Product;
use App\Ecommerce\Domain\Model\Catalog\Category;
use App\Ecommerce\Domain\Repository\Catalog\ProductRepositoryInterface;
use App\Ecommerce\Domain\Repository\Catalog\CategoryRepositoryInterface;
use PHPUnit\Framework\TestCase;

class UpdateProductUseCaseTest extends TestCase
{
    public function testExecuteUpdatesProductSuccessfully(): void
    {
        // 1. On crée les doubles (Mocks)
        $productRepo = $this->createMock(ProductRepositoryInterface::class);
        $categoryRepo = $this->createMock(CategoryRepositoryInterface::class);

        // 2. On prépare les données de test
        $category = new Category('cat-1', 'Bougies', 'bougies');
        $product = new Product('prod-1', 'Ancien Nom', 'ancien-nom', 10.0, $category, []);

        $dto = new UpdateProductDto(
            name: 'Nouveau Nom',
            price: 15.0,
            categoryId: 'cat-1',
            attributes: ['color' => 'red']
        );

        // 3. On configure les attentes
        $productRepo->expects($this->once())->method('findById')->willReturn($product);
        $categoryRepo->expects($this->once())->method('findById')->willReturn($category);
        
        // C'EST ICI LE TEST : On vérifie que save() est appelé avec le produit modifié
        $productRepo->expects($this->once())->method('save')->with($this->callback(function (Product $p) {
            return $p->getName() === 'Nouveau Nom' && $p->getPrice() === 15.0;
        }));

        // 4. On exécute
        $useCase = new UpdateProductUseCase($productRepo, $categoryRepo);
        $result = $useCase->execute($dto, 'prod-1');

        // 5. Assertions finales
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('Nouveau Nom', $result->getName());
    }
}