<?php

namespace Spur1\FeatureBundle\ExpressionLanguage;

use Spur1\FeatureBundle\Service\FeatureService;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

final class FeatureFlagExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function __construct(private FeatureService $featureService)
    {
        
    }

    public function getFunctions(): array
    {
        return [
            new ExpressionFunction(
                'feature',
                fn ($str) => sprintf('$this->isFeatureEnabled(%s)', $str),
                fn ($arguments, $str) => $this->featureService->getFeature($str),
            ),
        ];
    }
}
