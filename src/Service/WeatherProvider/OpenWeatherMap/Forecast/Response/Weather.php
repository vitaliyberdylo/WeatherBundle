<?php

namespace Vitber\WeatherBundle\Service\WeatherProvider\OpenWeatherMap\Forecast\Response;

class Weather
{
    public function __construct(
        public readonly string $main,
    ) {
    }
}
