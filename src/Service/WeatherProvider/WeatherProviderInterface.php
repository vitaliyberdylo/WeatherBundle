<?php

namespace Vitber\WeatherBundle\Service\WeatherProvider;

interface WeatherProviderInterface
{
    public function getWeatherData(string $city): WeatherProviderResponse;
}
