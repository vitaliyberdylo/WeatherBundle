<?php

namespace Vitber\WeatherBundle\CityProvider;

interface CityProviderInterface
{
    public function getCities(): array;
}
