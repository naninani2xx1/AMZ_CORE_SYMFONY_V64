<?php

namespace App\Core\Trait;

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

trait EnvironmentTrait
{
    public function isDevMode(): bool
    {
        if(!isset($this->kernel))
            throw new ServiceNotFoundException('kernel is not set');
        return $this->kernel->getEnvironment() === 'dev';
    }

    public function isProdMode(): bool
    {
        if(!isset($this->kernel))
            throw new ServiceNotFoundException('kernel is not set');
        return $this->kernel->getEnvironment() === 'prod';
    }

    public function getProjectDir(): string
    {
        return $this->kernel->getProjectDir();
    }
}