<?php

namespace Spur1\FeatureBundle\Exception;

use Exception;

class FeatureNotFoundException extends Exception
{
    public function __construct(string $featureName)
    {
        parent::__construct("Feature '{$featureName}' not found.");
    }
}
