<?php

namespace Vitber\WeatherBundle\Service\CityProvider;

class CityProvider implements CityProviderInterface
{
    public function __construct(
        private array $cities,
    ) {
    }

    public function getCities(): array
    {
        return $this->cities;
    }
}
