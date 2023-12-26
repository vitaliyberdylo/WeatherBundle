<?php

namespace Vitber\WeatherBundle\Service\CityProvider;

interface CityProviderInterface
{
    public function getCities(): array;
}
