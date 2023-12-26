<?php

namespace Vitber\WeatherBundle\Service\WeatherProvider\OpenWeatherMap\Forecast\Response;

class Main
{
    public function __construct(
        public readonly float $temp,
    ) {
    }
}
