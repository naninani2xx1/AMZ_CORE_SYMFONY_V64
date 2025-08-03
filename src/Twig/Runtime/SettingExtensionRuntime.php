<?php

namespace App\Twig\Runtime;

use App\Core\Entity\Setting;
use App\Core\Services\SettingService;
use Twig\Extension\RuntimeExtensionInterface;

class SettingExtensionRuntime implements RuntimeExtensionInterface
{
    private SettingService $settingService;
    public function __construct(SettingService $settingService)
    {
       $this->settingService = $settingService;
    }

    public function getSettingByKey($key) : ?Setting
    {
        return $this->settingService->findOneByKey($key) ?? new Setting();
    }
}
