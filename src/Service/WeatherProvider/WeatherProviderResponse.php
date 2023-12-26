<?php

namespace Vitber\WeatherBundle\Service\WeatherProvider;

class WeatherProviderResponse
{
    public function __construct(
        public readonly float $temperature,
        public readonly string $weatherStatus,
    ) {
    }
}
