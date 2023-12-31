<?php

namespace Vitber\WeatherBundle\WeatherProvider\OpenWeatherMap\Weather\Response;

class Main
{
    public function __construct(
        public readonly float $temp,
    ) {

    }
}
