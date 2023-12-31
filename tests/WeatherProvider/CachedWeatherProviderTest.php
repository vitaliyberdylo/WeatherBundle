<?php

namespace Vitber\WeatherBundle\Tests\WeatherProvider;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Vitber\WeatherBundle\WeatherProvider\CachedWeatherProvider;
use Vitber\WeatherBundle\WeatherProvider\WeatherProviderInterface;
use Vitber\WeatherBundle\WeatherProvider\WeatherProviderResponse;

class CachedWeatherProviderTest extends TestCase
{
    private WeatherProviderInterface $weatherProvider;
    private CacheInterface $cache;
    private CachedWeatherProvider $cachedWeatherProvider;
    public function setUp(): void
    {
        $this->weatherProvider = $this->createMock(WeatherProviderInterface::class);
        $this->cache = new ArrayAdapter();

        $this->cachedWeatherProvider = new CachedWeatherProvider(
            $this->weatherProvider,
            $this->cache
        );
    }

    public function testGetData(): void
    {
        $city = 'London';
        $response = new WeatherProviderResponse(25, 'Sunny');

        $this->weatherProvider->expects($this->once())
            ->method('getWeatherData')
            ->with('London')
            ->willReturn($response);

        $this->assertEquals(
            $response,
            $this->cachedWeatherProvider->getWeatherData($city)
        );

        // Check that second method call not call API
        $cachedData = $this->cache->getItem('vitber_weather_' . md5($city));
        $this->assertNotEmpty($cachedData);
        $this->assertEquals($response, $cachedData->get());

        $this->weatherProvider->expects($this->never())
            ->method('getWeatherData');

        $this->assertEquals(
            $response,
            $this->cachedWeatherProvider->getWeatherData($city)
        );
    }
}
