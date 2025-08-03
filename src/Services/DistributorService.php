<?php

namespace App\Services;

use App\Core\DataType\ArchivedDataType;
use App\Entity\Distributor;
use App\Repository\DistributorRepository;
use Doctrine\ORM\EntityManagerInterface;

class DistributorService
{
    private EntityManagerInterface $entityManager;
    private DistributorRepository $distributorRepository;
    public function __construct(EntityManagerInterface $entityManager, DistributorRepository $distributorRepository)
    {
        $this->entityManager = $entityManager;
        $this->distributorRepository = $distributorRepository;
    }

    public function findOneById(int $id): ?Distributor
    {
        return $this->distributorRepository->find($id);
    }

    public function findAll(): ?array
    {
        return $this->distributorRepository->findBy([
            'isArchived' => ArchivedDataType::UN_ARCHIVED
        ]);
    }
}