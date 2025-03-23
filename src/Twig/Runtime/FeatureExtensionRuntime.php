<?php

namespace Spur1\FeatureBundle\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;
use Spur1\FeatureBundle\Service\FeatureService;

class FeatureExtensionRuntime implements RuntimeExtensionInterface
{
    private FeatureService $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
    }

    public function getFeature(string $featureName): ?bool
    {
        return $this->featureService->isEnabled($featureName);
    }

    public function getAllFeatures(): array        
    {
        return $this->featureService->getAllFeatures();
    }
}
