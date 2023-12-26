<?php

namespace Vitber\WeatherBundle\Service\WeatherProvider\OpenWeatherMap\Forecast\Response;

class ForecastResponse
{
    /**
     * @param string $cod
     * @param Data[] $list
     */
    public function __construct(
        public readonly string $cod,
        public readonly array $list,
    ) {
    }
}
