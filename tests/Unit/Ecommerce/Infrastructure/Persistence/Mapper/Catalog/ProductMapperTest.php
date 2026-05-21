<?php

namespace App\Tests\Unit\Ecommerce\Infrastructure\Persistence\Mapper\Catalog;

use App\Ecommerce\Application\Mapper\AttributesMapper;
use App\Ecommerce\Domain\Model\Catalog\Category;
use App\Ecommerce\Domain\Model\Catalog\Product;
use App\Ecommerce\Infrastructure\Persistence\Entity\Catalog\DoctrineCategory;
use App\Ecommerce\Infrastructure\Persistence\Entity\Catalog\DoctrinePicture;
use App\Ecommerce\Infrastructure\Persistence\Entity\Catalog\DoctrineProduct;
use App\Ecommerce\Infrastructure\Persistence\Mapper\Catalog\CategoryMapper;
use App\Ecommerce\Infrastructure\Persistence\Mapper\Catalog\PictureMapper;
use App\Ecommerce\Infrastructure\Persistence\Mapper\Catalog\ProductMapper;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductMapperTest extends TestCase
{
    private CategoryMapper|MockObject $categoryMapper;
    private AttributesMapper|MockObject $attributesMapper;
    private PictureMapper|MockObject $pictureMapper;
    private ProductMapper $mapper;

    protected function setUp(): void
    {
        $this->categoryMapper = $this->createMock(CategoryMapper::class);
        $this->attributesMapper = $this->createMock(AttributesMapper::class);
        $this->pictureMapper = $this->createMock(PictureMapper::class);

        $this->mapper = new ProductMapper(
            $this->categoryMapper,
            $this->attributesMapper,
            $this->pictureMapper
        );
    }

    public function testToInfrastructure(): void
    {
        $product = $this->createMock(Product::class);
        $doctrineCategory = $this->createMock(DoctrineCategory::class);
        
        $product->method('getId')->willReturn('id-1');
        $product->method('getName')->willReturn('Product Name');
        $product->method('getPrice')->willReturn(100.50);
        $product->method('getAttributes')->willReturn(['color' => 'red']);

        $this->attributesMapper->expects($this->once())
            ->method('toDto')
            ->with(['color' => 'red'])
            ->willReturn(['color' => 'red']);

        $result = $this->mapper->toInfrastructure($product, $doctrineCategory);

        $this->assertInstanceOf(DoctrineProduct::class, $result);
        $this->assertSame('id-1', $result->getId());
        $this->assertSame('Product Name', $result->getName());
        $this->assertSame(100.50, $result->getPrice());
        $this->assertSame($doctrineCategory, $result->getCategory());
    }

    public function testMapDomainToEntity(): void
    {
        $product = $this->createMock(Product::class);
        $doctrineProduct = $this->createMock(DoctrineProduct::class);
        $doctrineCategory = $this->createMock(DoctrineCategory::class);

        $product->method('getName')->willReturn('Updated Name');
        $product->method('getPrice')->willReturn(200.00);
        $product->method('getAttributes')->willReturn(['size' => 'L']);

        $this->attributesMapper->expects($this->once())
            ->method('toDto')
            ->with(['size' => 'L'])
            ->willReturn(['size' => 'L']);

        $doctrineProduct->expects($this->once())->method('setName')->with('Updated Name');
        $doctrineProduct->expects($this->once())->method('setPrice')->with(200.00);
        $doctrineProduct->expects($this->once())->method('setCategory')->with($doctrineCategory);
        $doctrineProduct->expects($this->once())->method('setAttributes')->with(['size' => 'L']);

        $this->mapper->mapDomainToEntity($product, $doctrineProduct, $doctrineCategory);
    }

    public function testToDomain(): void
    {
        $doctrineProduct = $this->createMock(DoctrineProduct::class);
        $doctrineCategory = $this->createMock(DoctrineCategory::class);
        $doctrinePicture = $this->createMock(DoctrinePicture::class);
        $category = $this->createMock(Category::class);

        $doctrineProduct->method('getId')->willReturn('id-1');
        $doctrineProduct->method('getName')->willReturn('Name');
        $doctrineProduct->method('getPrice')->willReturn(10.0);
        $doctrineProduct->method('getCategory')->willReturn($doctrineCategory);
        $doctrineProduct->method('getAttributes')->willReturn([]);
        $doctrineProduct->method('getPictures')->willReturn(new ArrayCollection([$doctrinePicture]));

        $this->categoryMapper->expects($this->once())->method('toDomain')->willReturn($category);
        $this->attributesMapper->expects($this->once())->method('fromArray')->willReturn([]);
        $this->pictureMapper->expects($this->once())->method('toDomain')->with($doctrinePicture);

        $result = $this->mapper->toDomain($doctrineProduct);

        $this->assertInstanceOf(Product::class, $result);
    }
}