<?php

namespace Vitber\WeatherBundle\Service\WeatherProvider;

use Vitber\WeatherBundle\Service\CityProvider\CityProviderInterface;

class WeatherProvider implements WeatherProviderInterface
{
    /**
     * @param CityProviderInterface $cityProvider
     * @param iterable|NamedWeatherProviderInterface[] $weatherProviders
     * @param string $activeProviderName
     */
    public function __construct(
        private CityProviderInterface $cityProvider,
        private iterable $weatherProviders,
        private string $activeProviderName,
    ) {
    }

    public function getWeatherData(string $city): WeatherProviderResponse
    {
        if (!in_array($city, $this->cityProvider->getCities())) {
            throw new \RuntimeException(
                sprintf('City "%s" is not available.', $city)
            );
        }

        foreach ($this->weatherProviders as $weatherProvider) {
            if ($weatherProvider->getName() === $this->activeProviderName) {
                return $weatherProvider->getWeatherData($city);
            }
        }

        throw new \LogicException(
            sprintf('Weather provider with name "%s" not found.', $this->activeProviderName)
        );
    }
}
