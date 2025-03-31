This is a highly modified fork of: https://github.com/novaway/NovawayFeatureFlagBundle to fit our needs.

# SPUR1-FEATURE-BUNDLE

This bundle provides very simple feature flag functionality for our symfony applications.

## Installation

to install you need to add the following to the end of your composer.json file

``` json

"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/spur1-media/spur1-feature-bundle",
        }
    ]

```

after that run the composer require command.

``` composer
composer require spur1-media/spur1-feature-bundle
```

## Configuration

### .env.features

This bundle uses a .env.features file inside of your projects root directory to define feature flags.

``` dotenv
FEAUTRE_NAME=true #false does work too (to disable a feature)
```

### Attributes

You can use Attributes to check if a feature is enabled.

``` php

#[Feature('FEATURE_NAME')] // this attribute will default to "false" if the feature does not exist
class MyClass
{
    public function myMethod()
    {
        // this method will only be executed if the feature is enabled
    }
}
```

---

### FeatureService

The FeatureService provides 3 methods to check features.

``` php

// returns the value of a feature
$featureService->getFeature('FEATURE_NAME'); // this method will throw a FeatureNotFoundException if the feature does not exist

// returns true if the feature is enabled
$featureService->isFeatureEnabled('FEATURE_NAME'); // this method will default to "false" if the feature does not exist

// returns an array of all features and their values
$featureService->getAllFeatures(); // this method will return false for features that do not exist
```

---

### Twig Extension

The Twig Extension has one filter and two functions.

``` twig

{# Twig Filter: returns the value of a feature #}
{{ 'FEATURE_NAME'|feature }} {# this filter will return false if the feature does not exist #}

{# returns true if the feature is enabled #}
{{ feature('FEATURE_NAME') }} {# this filter will default to "false" if the feature does not exist #}

{# returns an array of all features and their values #}
{{ features() }} {# this filter will return false for features that do not exist #}
```

---

### Symfony Web Profiler

The Symfony Web Profiler has a new tab called "Features" that shows all features and their values.