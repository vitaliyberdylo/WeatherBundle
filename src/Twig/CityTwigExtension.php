<?php

namespace Vitber\WeatherBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Vitber\WeatherBundle\CityProvider\CityProviderInterface;

class CityTwigExtension extends AbstractExtension
{
    public function __construct(
        private CityProviderInterface $cityProvider
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_weather_cities', [$this, 'getCities']),
        ];
    }

    public function getCities(): array
    {
        return $this->cityProvider->getCities();
    }
}
