<?php

namespace Vitber\WeatherBundle\Service\WeatherProvider\OpenWeatherMap\Forecast\Request;

class ForecastRequest
{
    public const API_URL = 'https://api.openweathermap.org/data/2.5/forecast';

    /**
     * Limit number of timestamps in the API response
     * @see https://openweathermap.org/forecast5#limit
     */
    private const RESULT_LIMITATION = 1;
    private const METRIC_UNITS = 'metric';

    public function __construct(
        public readonly string $q,
        public readonly string $apiKey,
        public readonly string $units = self::METRIC_UNITS,
        public readonly int $cnt = self::RESULT_LIMITATION,
    ) {
    }
}
