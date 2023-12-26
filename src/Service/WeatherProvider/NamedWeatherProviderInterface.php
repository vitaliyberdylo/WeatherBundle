<?php

namespace Vitber\WeatherBundle\Service\WeatherProvider;

interface NamedWeatherProviderInterface extends WeatherProviderInterface
{
    public function getName(): string;
}
