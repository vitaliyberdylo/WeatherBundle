<?php

namespace Vitber\WeatherBundle\CityProvider;

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
