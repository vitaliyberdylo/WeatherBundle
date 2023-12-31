<?php

namespace Vitber\WeatherBundle\Tests\WeatherProvider;

use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Vitber\WeatherBundle\CityProvider\CityProviderInterface;
use Vitber\WeatherBundle\Event\WeatherProviderResponseEvent;
use Vitber\WeatherBundle\WeatherProvider\NamedWeatherProviderInterface;
use Vitber\WeatherBundle\WeatherProvider\WeatherProvider;
use Vitber\WeatherBundle\WeatherProvider\WeatherProviderResponse;

class WeatherProviderTest extends TestCase
{
    private const ACTIVE_PROVIDER_NAME = 'active_provider';

    private CityProviderInterface $cityProvider;
    private array $weatherProviders;
    private NamedWeatherProviderInterface $namedWeatherProvider;
    private string $activeWeatherProvider;
    private EventDispatcherInterface $eventDispatcher;
    private WeatherProvider $weatherProvider;

    public function setUp(): void
    {
        $this->cityProvider = $this->createMock(CityProviderInterface::class);
        $this->namedWeatherProvider = $this->createMock(NamedWeatherProviderInterface::class);
        $this->weatherProviders = [$this->namedWeatherProvider];
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->activeWeatherProvider = self::ACTIVE_PROVIDER_NAME;

        $this->weatherProvider = new WeatherProvider(
            $this->cityProvider,
            $this->weatherProviders,
            $this->eventDispatcher,
            $this->activeWeatherProvider,
        );
    }

    public function testGetWeatherData()
    {
        $cityName = 'London';
        $weatherData = new WeatherProviderResponse(20, 'Rain');

        $this->cityProvider->expects($this->once())
            ->method('getCities')
            ->willReturn([$cityName]);

        $this->namedWeatherProvider->expects($this->once())
            ->method('getName')
            ->willReturn(self::ACTIVE_PROVIDER_NAME);

        $this->namedWeatherProvider->expects($this->once())
            ->method('getWeatherData')
            ->with($cityName)
            ->willReturn($weatherData);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with(new WeatherProviderResponseEvent($weatherData), WeatherProviderResponseEvent::NAME);

        $this->assertEquals(
            $weatherData,
            $this->weatherProvider->getWeatherData($cityName)
        );
    }

    public function testGetWeatherDataCityException()
    {
        $this->cityProvider->expects($this->once())
            ->method('getCities')
            ->willReturn(['Tokio']);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('City "London" is not available.');

        $this->namedWeatherProvider->expects($this->never())
            ->method('getWeatherData');

        $this->weatherProvider->getWeatherData('London');
    }

    public function testGetWeatherDataProviderException()
    {
        $cityName = 'London';

        $this->cityProvider->expects($this->once())
            ->method('getCities')
            ->willReturn([$cityName]);

        $this->namedWeatherProvider->expects($this->once())
            ->method('getName')
            ->willReturn('another_provider_name');

        $this->namedWeatherProvider->expects($this->never())
            ->method('getWeatherData');

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Weather provider with name "active_provider" not found.');

        $this->weatherProvider->getWeatherData($cityName);
    }
}
