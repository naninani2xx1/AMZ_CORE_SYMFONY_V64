<?php

namespace App\Core\Entity;

use App\Core\Trait\DoctrineDescriptionTrait;
use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingRepository")
 * @ORM\Table(name="core_setting")
 * @ORM\HasLifecycleCallbacks
 */
class Setting extends LifecycleEntity
{
    use DoctrineDescriptionTrait, DoctrineIdentifierTrait;

    /**
     * @ORM\Column (type="string", unique=true, nullable = true)
     */
    private $settingKey;

    /**
     * @ORM\Column (type="text", nullable = true)
     */
    private $settingValue;

    /**
     * @ORM\Column (type="text", nullable = true)
     */
    private $settingType;

    public function getSettingKey(): ?string
    {
        return $this->settingKey;
    }

    public function setSettingKey(string $settingKey): self
    {
        $this->settingKey = $settingKey;

        return $this;
    }


    public function getSettingValue()
    {
      return $this->settingValue;
    }

    public function setSettingValue($value): static
    {
        $this->settingValue = $value;
        return $this;
    }

    public function getSettingType(): ?string
    {
        return $this->settingType;
    }

    public function setSettingType(string $settingType): self
    {
        $this->settingType = $settingType;

        return $this;
    }
}