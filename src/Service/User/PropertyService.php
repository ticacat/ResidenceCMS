<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Repository\UserPropertyRepository;
use App\Transformer\RequestToArrayTransformer;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class PropertyService
{
    public function __construct(
        private UserPropertyRepository $repository,
        private RequestToArrayTransformer $transformer,
        private TokenStorageInterface $tokenStorage
    ) {
    }

    public function getUserProperties(Request $request): PaginationInterface
    {
        $searchParams = $this->transformer->transform($request);
        $searchParams['user'] = $this->tokenStorage->getToken()->getUser()->getId();

        return $this->repository->findByUser($searchParams);
    }
}
