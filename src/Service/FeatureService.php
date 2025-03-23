<?php

namespace Spur1\FeatureBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Dotenv\Dotenv;
use Spur1\FeatureBundle\Model\Feature;
use Symfony\Component\HttpKernel\KernelInterface;
use Spur1\FeatureBundle\Exception\FeatureNotFoundException;
use Spur1\FeatureBundle\Exception\MissingFeatureFileException;

class FeatureService
{
    /** @var Feature[] */
    private array $features = [];

    public function __construct(private KernelInterface $kernel, private LoggerInterface $logger, private bool $throwExceptions)
    {
        $projectDir = $kernel->getProjectDir();
        $featuresFile = $projectDir . '/.env.features';

        if (file_exists($featuresFile)) {
            //$dotenv = new Dotenv();

            $envData = file($featuresFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($envData as $line) {
                if (strpos($line, '=') !== false) {
                    [$key, $value] = explode('=', $line, 2);
                    $enabled = filter_var(trim($value), FILTER_VALIDATE_BOOLEAN);
                    $this->features[$key] = new Feature($key, $enabled);
                }
            }
        } else {
            if ($this->throwExceptions) {
                throw new MissingFeatureFileException($featuresFile);
            }

            $this->logger->warning("Feature file '{$featuresFile}' not found.");
        }
    }

    public function getFeature(string $featureName): Feature
    {
        if (!isset($this->features[$featureName])) {
            if ($this->throwExceptions) {
                throw new FeatureNotFoundException($featureName);
            }

            $this->logger->warning("Feature '{$featureName}' not found. Defaulting to disabled.");
            return new Feature($featureName, false);
        }

        return $this->features[$featureName];
    }

    public function isEnabled(string $featureName): bool
    {
        return $this->getFeature($featureName)->isEnabled();
    }

    /**
     * @return Feature[]
     */
    public function getAllFeatures(): array
    {
        return array_values($this->features);
    }
}
