<?php

namespace Spur1\FeatureBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Spur1\FeatureBundle\Service\FeatureService;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class FeatureDataCollector extends DataCollector
{
    private FeatureService $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
        $this->data = [];
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        // Collect all feature flags
        $features = $this->featureService->getAllFeatures();

        // Store enabled and disabled features separately
        $this->data['features'] = $features;
        $this->data['enabled_features'] = array_filter($features, fn($feature) => $feature->isEnabled());
        $this->data['disabled_features'] = array_filter($features, fn($feature) => !$feature->isEnabled());
    }

    public function getFeatures(): array
    {
        return $this->data['features'] ?? [];
    }

    public function getEnabledFeatures(): array
    {
        return $this->data['enabled_features'] ?? [];
    }

    public function getDisabledFeatures(): array
    {
        return $this->data['disabled_features'] ?? [];
    }

    public function reset(): void
    {
        $this->data = [];
    }

    public function getName(): string
    {
        return 'feature_collector';
    }
}
