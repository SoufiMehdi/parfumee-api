<?php

namespace App\Ecommerce\Infrastructure\Controller\User;

use App\Ecommerce\Application\UseCase\User\RegisterUserUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Ecommerce\Application\Mapper\User\RegisterUserRequestMapper;

#[Route('/users')]
class UserController extends AbstractController
{
    #[Route('/list', name: 'get_users', methods: [Request::METHOD_GET])]
    public function getList(): JsonResponse
    {
        return new JsonResponse(['message' => 'List of users']);
    }

    #[Route('/create', name: 'create_user', methods: [Request::METHOD_POST])]
    public function create(
        Request $request, 
        RegisterUserUseCase $useCase
        ): JsonResponse
    {
        dump($request->toArray());
        $dto = RegisterUserRequestMapper::fromRequest($request);
        
        $useCase->execute($dto);
        return new JsonResponse(['message' => 'User created successfully'], JsonResponse::HTTP_CREATED);
    }
}