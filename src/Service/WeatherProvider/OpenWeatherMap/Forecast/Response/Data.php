<?php

namespace Vitber\WeatherBundle\Service\WeatherProvider\OpenWeatherMap\Forecast\Response;

class Data
{
    /**
     * @param Main $main
     * @param Weather[] $weather
     */
    public function __construct(
        public readonly Main $main,
        public readonly array $weather,
    ) {
    }
}
