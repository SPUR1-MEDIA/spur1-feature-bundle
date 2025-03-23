<?php

namespace Spur1\FeatureBundle\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class FeatureAttribute
{
    public string $featureName;

    public function __construct(string $featureName)
    {
        $this->featureName = $featureName;
    }
}
