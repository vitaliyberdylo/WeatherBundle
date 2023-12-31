<?php

namespace Vitber\WeatherBundle\WeatherProvider\OpenWeatherMap\Weather\Response;

class WeatherResponse
{
    /**
     * @param int $cod
     * @param Weather[] $weather
     * @param Main $main
     */
    public function __construct(
        public readonly int $cod,
        public readonly array $weather,
        public readonly Main $main,
    ) {
    }
}
