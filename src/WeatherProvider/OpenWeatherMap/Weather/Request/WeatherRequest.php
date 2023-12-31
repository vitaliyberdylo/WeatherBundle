<?php

namespace Vitber\WeatherBundle\WeatherProvider\OpenWeatherMap\Weather\Request;

class WeatherRequest
{
    public const URL = 'weather';

    private const METRIC_UNITS = 'metric';

    public function __construct(
        public readonly string $q,
        public readonly string $apiKey,
        public readonly string $units = self::METRIC_UNITS,
    ) {
    }
}
