<?php

namespace App\Tests\Unit\Ecommerce\Application\UseCase\Catalog;

use App\Ecommerce\Application\DTO\Catalog\CreateProductDto;
use App\Ecommerce\Application\UseCase\Catalog\CreateProductUseCase;
use App\Ecommerce\Domain\Exception\Catalog\CategorieNotFoundException;
use App\Ecommerce\Domain\Model\Catalog\Category;
use App\Ecommerce\Domain\Model\Catalog\Product;
use App\Ecommerce\Domain\Repository\Catalog\CategoryRepositoryInterface;
use App\Ecommerce\Domain\Repository\Catalog\ProductRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CreateProductUseCaseTest extends TestCase
{
    private $productRepository;
    private $categoryRepository;
    private $useCase;

    protected function setUp(): void
    {
        // On crée des doubles des interfaces
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);

        // On instancie le UseCase avec les mocks
        $this->useCase = new CreateProductUseCase(
            $this->productRepository,
            $this->categoryRepository
        );
    }

    public function testExecuteSuccess(): void
    {
        // 1. Préparation des données (Arange)
        $categoryId = Uuid::v4()->toRfc4122();
        $dto = new CreateProductDto(
            name: 'Bougie Parfumée',
            price: 25.50,
            categoryId: $categoryId,
            attributes: ['fragrance' => 'Vanille']
        );

        $category = new Category($categoryId, 'Bougies', 'bougies');

        // On configure le mock de la catégorie pour qu'il retourne notre objet
        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($category);

        // On vérifie que save() est bien appelé une fois avec un objet Product
        $this->productRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Product::class));

        // 2. Exécution (Act)
        $result = $this->useCase->execute($dto);

        // 3. Vérifications (Assert)
        $this->assertIsString($result);
        $this->assertTrue(Uuid::isValid($result));
    }

    public function testExecuteThrowsExceptionWhenCategoryNotFound(): void
    {
        // 1. Préparation
        $dto = new CreateProductDto(
            name: 'Produit Sans Catégorie',
            price: 10.0,
            categoryId: Uuid::v4()->toRfc4122(),
            attributes: []
        );

        // Le repository retourne null
        $this->categoryRepository
            ->method('findById')
            ->willReturn(null);

        // On s'attend à ce que save() ne soit JAMAIS appelé
        $this->productRepository
            ->expects($this->never())
            ->method('save');

        // 2 & 3. Vérification de l'exception
        $this->expectException(CategorieNotFoundException::class);
        
        $this->useCase->execute($dto);
    }
}