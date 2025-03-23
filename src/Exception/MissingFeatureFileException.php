<?php

namespace Spur1\FeatureBundle\Exception;

use Exception;

class MissingFeatureFileException extends Exception
{
    public function __construct(string $featuresFile)
    {
        parent::__construct("Feature file '{$featuresFile}' not found.");
    }
}
