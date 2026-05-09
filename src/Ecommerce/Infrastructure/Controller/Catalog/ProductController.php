<?php 

namespace App\Ecommerce\Infrastructure\Controller\Catalog;

use App\Ecommerce\Application\UseCase\Catalog\CreateProductUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Ecommerce\Application\DTO\Catalog\CreateProductDto;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'create_product', methods: ['POST'])]
    public function create(Request $request, CreateProductUseCase $useCase): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $dto = new CreateProductDto(
            name: $data['name'] ?? '',
            price: $data['price'] ?? 0,
            categoryId: $data['category_id'] ?? '',
            attributes: $data['attributes'] ?? []
        );
        $product = $useCase->execute($dto);
        return new JsonResponse($product, JsonResponse::HTTP_CREATED);
    }
}
