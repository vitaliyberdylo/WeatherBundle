<?php

namespace Vitber\WeatherBundle\WeatherProvider\OpenWeatherMap\Weather\Response;

class Weather
{
    public function __construct(
        public readonly string $main,
    ) {
    }
}
