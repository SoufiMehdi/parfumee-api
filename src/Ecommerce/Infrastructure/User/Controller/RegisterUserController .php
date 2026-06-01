<?php

declare(strict_types=1);

namespace App\Ecommerce\Infrastructure\User\Controller;

use App\Ecommerce\Application\Mapper\User\RegisterUserRequestMapper;
use App\Ecommerce\Application\UseCase\User\RegisterUserUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class RegisterUserController extends AbstractController
{
    #[Route('/user/register', name: 'user_register', methods: ['POST'])]
    public function register(Request $request, RegisterUserUseCase $useCase): JsonResponse
    {
        // 1. Mapping
        $dto = RegisterUserRequestMapper::fromRequest($request);

        // 2. Exécution du Use Case
        $useCase->execute($dto);

        return new JsonResponse(['status' => 'User created'], 201);
    }
}