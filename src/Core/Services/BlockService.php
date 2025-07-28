<?php

namespace App\Core\Services;

use App\Core\Repository\BlockRepository;
use Doctrine\ORM\EntityManagerInterface;

class BlockService
{
    private EntityManagerInterface $entityManager;
    private BlockRepository $blockRepository;
    public function __construct(EntityManagerInterface $entityManager, BlockRepository $blockRepository)
    {
        $this->blockRepository = $blockRepository;
        $this->entityManager = $entityManager;
    }

    public function findOneById(int $id)
    {
        return $this->blockRepository->find($id);
    }
}