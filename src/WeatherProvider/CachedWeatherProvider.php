<?php

namespace Vitber\WeatherBundle\WeatherProvider;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CachedWeatherProvider implements WeatherProviderInterface
{
    private const CASH_PREFIX = 'vitber_weather_';
    private const EXPIRATION_TIME = 60;

    public function __construct(
        private WeatherProviderInterface $weatherProvider,
        private CacheInterface $cache,
    ) {
    }

    public function getWeatherData(string $city): WeatherProviderResponse
    {
        return $this->cache->get(
            $this->getCashKey($city),
            function (ItemInterface $item) use ($city): WeatherProviderResponse {
                $item->expiresAfter(self::EXPIRATION_TIME);
                return $this->weatherProvider->getWeatherData($city);
            }
        );
    }

    private function getCashKey(string $city): string
    {
            return self::CASH_PREFIX . md5($city);
    }
}
