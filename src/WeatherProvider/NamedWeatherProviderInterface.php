<?php

namespace Vitber\WeatherBundle\WeatherProvider;

interface NamedWeatherProviderInterface extends WeatherProviderInterface
{
    public function getName(): string;
}
