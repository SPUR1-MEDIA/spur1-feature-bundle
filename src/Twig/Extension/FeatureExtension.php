<?php

namespace Spur1\FeatureBundle\Twig\Extension;

use Spur1\FeatureBundle\Twig\Runtime\FeatureExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class FeatureExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('feature', [FeatureExtensionRuntime::class, 'getFeature']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('feature', [FeatureExtensionRuntime::class, 'getFeature']),
            new TwigFunction('features', [FeatureExtensionRuntime::class, 'getAllFeatures']),
        ];
    }
}