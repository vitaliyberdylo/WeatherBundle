<?php

namespace Vitber\WeatherBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Vitber\WeatherBundle\WeatherProvider\WeatherProviderResponse;

class WeatherProviderResponseEvent extends Event
{
    public const NAME = 'weather.provider_response';

    public function __construct(
        public WeatherProviderResponse $response
    ) {
    }
}
