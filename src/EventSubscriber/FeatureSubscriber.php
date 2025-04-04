<?php

namespace Spur1\FeatureBundle\EventSubscriber;

use ReflectionMethod;
use Spur1\FeatureBundle\Attribute\Feature;
use Spur1\FeatureBundle\Service\FeatureService;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class FeatureSubscriber implements EventSubscriberInterface
{
    public function __construct(private FeatureService $featureService)
    {

    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        
        if (is_array($controller)) {
            [$controllerInstance, $method] = $controller;
            $reflectionMethod = new ReflectionMethod($controllerInstance, $method);

            foreach ($reflectionMethod->getAttributes(Feature::class) as $attribute) {
                $featureName = $attribute->newInstance()->featureName;
                $feature = $this->featureService->getFeature($featureName);

                if (!$feature || !$feature->isEnabled()) {
                    throw new AccessDeniedHttpException("Feature '{$featureName}' is disabled.");
                }
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
