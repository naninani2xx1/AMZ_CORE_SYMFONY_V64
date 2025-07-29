<?php

namespace App\Core\Services;

use App\Core\Entity\Qa;
use App\Core\Repository\QaRepository;

class QaService
{
    private QaRepository $repository;
    public function __construct(QaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findOneById(int $id): ?Qa
    {
        return $this->repository->find($id);
    }
}