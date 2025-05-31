<?php

namespace Spur1\FeatureBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Dotenv\Dotenv;
use Spur1\FeatureBundle\Model\FeatureModel;
use Symfony\Component\HttpKernel\KernelInterface;
use Spur1\FeatureBundle\Exception\FeatureNotFoundException;
use Spur1\FeatureBundle\Exception\MissingFeatureFileException;

class FeatureService
{
    /** @var FeatureModel[] */
    private array $features = [];

    public function __construct(private KernelInterface $kernel, private LoggerInterface $logger, private bool $throwExceptions)
    {
        $projectDir = $kernel->getProjectDir();
        $featuresFile = $projectDir . '/.env.features';

        if (file_exists($featuresFile)) {
            $envData = file($featuresFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($envData as $line) {
                if (strpos($line, '=') !== false) {
                    [$key, $value] = explode('=', $line, 2);
                    $enabled = filter_var(trim($value), FILTER_VALIDATE_BOOLEAN);
                    $this->features[$key] = new FeatureModel($key, $enabled);
                }
            }
        } else {
            if ($this->throwExceptions) {
                throw new MissingFeatureFileException($featuresFile);
            }

            $this->logger->warning("Feature file '{$featuresFile}' not found.");
        }
    }

    public function getFeature(string $featureName): FeatureModel
    {
        if (!isset($this->features[$featureName])) {
            if ($this->throwExceptions) {
                throw new FeatureNotFoundException($featureName);
            }

            $this->logger->warning("Feature '{$featureName}' not found. Defaulting to disabled.");

            $feature = new FeatureModel($featureName, false);
            $this->features[$featureName] = $feature;
            $this->appendFeatureToFile($featureName);
            
            return $feature;
        }

        $this->appendFeatureToFile($featureName);
        return $this->features[$featureName];
    }

    public function isEnabled(string $featureName): bool
    {
        return $this->getFeature($featureName)->isEnabled();
    }

    /**
     * @return FeatureModel[]
     */
    public function getAllFeatures(): array
    {
        foreach($this->features as $feature) {
            $this->appendFeatureToFile($feature->getName());
        }

        return array_values($this->features);
    }

    private function appendFeatureToFile(string $featureName): void
    {
        $projectDir = $this->kernel->getProjectDir();
        $featuresFile = $projectDir . '/.env.features';

        // Nur wenn Datei existiert (ansonsten sollte Exception bereits geworfen sein)
        if (!file_exists($featuresFile)) {
            $this->logger->error("Cannot append feature '{$featureName}' because file '{$featuresFile}' does not exist.");
            return;
        }

        // Prüfen, ob Feature bereits in Datei (Edge Case bei Race Conditions)
        $currentLines = file($featuresFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($currentLines as $line) {
            if (str_starts_with($line, $featureName . '=')) {
                return;
            }
        }

        file_put_contents($featuresFile, PHP_EOL . $featureName . '=FALSE', FILE_APPEND);
    }
}
