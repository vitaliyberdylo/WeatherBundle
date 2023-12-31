<?php

namespace Vitber\WeatherBundle\WeatherProvider;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Vitber\WeatherBundle\CityProvider\CityProviderInterface;
use Vitber\WeatherBundle\Event\CityProviderEvent;
use Vitber\WeatherBundle\Event\WeatherProviderResponseEvent;

class WeatherProvider implements WeatherProviderInterface
{
    /**
     * @param CityProviderInterface $cityProvider
     * @param iterable|NamedWeatherProviderInterface[] $weatherProviders
     * @param EventDispatcherInterface $eventDispatcher
     * @param string $activeProviderName
     */
    public function __construct(
        private CityProviderInterface $cityProvider,
        private iterable $weatherProviders,
        private EventDispatcherInterface $eventDispatcher,
        private string $activeProviderName,
    ) {
    }

    public function getWeatherData(string $city): WeatherProviderResponse
    {
        if (!in_array($city, $this->cityProvider->getCities())) {
            throw new NotFoundHttpException(
                sprintf('City "%s" is not available.', $city)
            );
        }

        foreach ($this->weatherProviders as $weatherProvider) {
            if ($weatherProvider->getName() === $this->activeProviderName) {
                $weatherData = $weatherProvider->getWeatherData($city);

                $event = new WeatherProviderResponseEvent($weatherData);
                $this->eventDispatcher->dispatch($event, WeatherProviderResponseEvent::NAME);

                return $event->response;
            }
        }

        throw new \LogicException(
            sprintf('Weather provider with name "%s" not found.', $this->activeProviderName)
        );
    }
}
