<?php

namespace Spur1\FeatureBundle\Model;

class FeatureModel
{
    private string $name;
    private bool $enabled;

    public function __construct(string $name, bool $enabled)
    {
        $this->name = $name;
        $this->enabled = $enabled;
    }

    public function getName(): string
    {
        return $this->name;
    }
    
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
