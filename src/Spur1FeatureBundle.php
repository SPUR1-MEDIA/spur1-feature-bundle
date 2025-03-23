<?php 

namespace Spur1\FeatureBundle;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Spur1\FeatureBundle\DependencyInjection\Spur1FeatureExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class Spur1FeatureBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new Spur1FeatureExtension();
    }
}