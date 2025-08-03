<?php

namespace App\Core\Entity;

use App\Core\Trait\DoctrineDescriptionTrait;
use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repository\SettingRepository")
 * @ORM\Table(name="core_setting")
 * @ORM\HasLifecycleCallbacks
 */
class Setting extends LifecycleEntity
{
    use DoctrineDescriptionTrait, DoctrineIdentifierTrait;

    /**
     * @ORM\Column (type="string", unique=true, nullable = true)
     * @Assert\NotBlank(message="SettingKey cannot be blank")
     */
    private $settingKey;

    /**
     * @ORM\Column (type="text", nullable = true)
     */
    private $settingValue;

    /**
     * @Assert\NotBlank()
     * @ORM\Column (type="text", nullable = true)
     */
    private $settingType;

    public function getSettingKey(): ?string
    {
        return $this->settingKey;
    }

    public function setSettingKey(string $settingKey): self
    {
        $this->settingKey = strtolower($settingKey);

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