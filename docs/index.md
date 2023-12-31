VitberWeatherBundle: Symfony integration with weather providers!
================================================================

This bundle allows you to get current weather for specific cities.

Installation
------------

Install the bundle with:

```shell
    $ composer require vitber/weather-bundle
```

Configuration
-------------

Because bundle was created for demo reasons there is not appropriate recipe and this bundle is not available on Packagist.

1. Paste this repositories section at the bottom your application's ``composer.json`` file.

```json
{
  ...
  "repositories": [
    {
      "type": "path",
      "url": "../WeatherBundle"
    }
  ]
}

```

2. Copy the config file ``config/packages/vitber_weather.yaml`` into your project manually:

``` yaml
    # config/packages/vitber_weather.yaml
    vitber_weather:
        weather_provider: '%env(VITBER_WEATHER_PROVIDER)%'
        cities: '%env(json:VITBER_CITIES)%'
        open_weather_map:
            api_url: '%env(OPEN_WEATHER_MAP_API_URL)%'
            api_key: '%env(OPEN_WEATHER_MAP_API_KEY)%'
            units: '%env(OPEN_WEATHER_MAP_UNITS)%'
```

3. Add ``VitberWeatherBundle`` to your application's ``config/bundles.php`` file.

```php
<?php

return [
    ...
    Vitber\WeatherBundle\VitberWeatherBundle::class => ['all' => true],
];
```

3. Run in terminal

```shell
    composer require "vitber/weather-bundle:*@dev"
```

4. Configure API key and available cities

By default this bundle gets data from OpenWeatherMap api.
For using API you should create own account on http://openweathermap.org website and configure bundle your api_key.
Check api for usage: http://openweathermap.org/api

``OPEN_WEATHER_MAP_API_KEY`` should be stored in file ``env.local`` in your project.
In addition to the key in ``env.local``, you also should to configure available cities ``VITBER_CITIES`` in format city
name, state code and country code divided by comma. Please refer to ISO 3166 for the state codes or country codes.

```text
VITBER_CITIES='["Berlin,DE","Halle (Saale),DE","Lübeck,DE","Wiesbaden,DE","Genoa,IT"]'

OPEN_WEATHER_MAP_API_KEY=yor_api_key
```

Usage
-----

For getting weather data by city name, inject ``WeatherProviderInterface $weatherProvider`` into your service
or controller.

``` php
    public function __construct(
        private WeatherProviderInterface $weatherProvider
    ) {
    }
```

Then you can get data using method ``getWeatherData``

``` php
    $data = $this->weatherProvider->getWeatherData('London, UK');
```
 
Add own weather provider
------------------------

Extend bundle functionality by adding a new provider is pretty easy. You should create in your own application service
which implement ``Vitber\WeatherBundle\WeatherProvider\WeatherProviderInterface``

```php
namespace App\Service;

use Vitber\WeatherBundle\WeatherProvider\NamedWeatherProviderInterface;
use Vitber\WeatherBundle\WeatherProvider\WeatherProviderResponse;

class CustomWeatherProvider implements NamedWeatherProviderInterface
{
    public function getName(): string
    {
        return 'custom';
    }

    public function getWeatherData(string $city): WeatherProviderResponse
    {
        return new WeatherProviderResponse(10, 'Sunny');
    }
}
```

Then tag this service as ``vitber.weather_provider``

```yaml
    App\Service\CustomWeatherProvider:
        tags:
            - { name: vitber.weather_provider }
```

That's all. Now you can switch ``vitber_weather.weather_provider`` parameter in your application's ``.env.local``

```text
VITBER_WEATHER_PROVIDER=custom
```


Modify provider response
------------------------

In case when you would like to modify provider response (add some custom logic or format some fields) you don't need
to create and register your own provider. To do this, you just need to create a listener in your application 

```php
namespace App\EventListener;

use Vitber\WeatherBundle\Event\WeatherProviderResponseEvent;
use Vitber\WeatherBundle\WeatherProvider\WeatherProviderResponse;

class WeatherProviderResponseListener
{
    public function onWeatherProviderResponse(WeatherProviderResponseEvent $event)
    {
        $response = $event->response;
        // Modify provider response for custom reason.
        $event->response = new WeatherProviderResponse(
            5 + $response->temperature,
            'Modified status. ' . $response->weatherStatus
        );
    }
}

```

and sign it up
for the ``weather.provider_response`` event.

```yaml
    App\EventListener\WeatherProviderResponseListener:
        tags:
            - { name: kernel.event_listener, event: weather.provider_response, method: onWeatherProviderResponse }
```
