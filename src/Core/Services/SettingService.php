<?php

namespace App\Core\Services;


use App\Core\Entity\Setting;
use App\Core\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;


class SettingService
{
    private EntityManagerInterface $em;
    private SettingRepository $settingRepository;

    public function __construct(
        EntityManagerInterface $em,
        SettingRepository $settingRepository
    ) {
        $this->em = $em;
        $this->settingRepository = $settingRepository;
    }

    public function findOneById(int $id): ?Setting
    {
        return $this->settingRepository->find($id);
    }
}
