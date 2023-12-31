<?php

namespace Vitber\WeatherBundle\WeatherProvider;

interface WeatherProviderInterface
{
    public function getWeatherData(string $city): WeatherProviderResponse;
}
