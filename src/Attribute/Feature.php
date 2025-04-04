<?php

namespace Spur1\FeatureBundle\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class Feature
{
    public string $featureName;

    public function __construct(string $featureName)
    {
        $this->featureName = $featureName;
    }
}
