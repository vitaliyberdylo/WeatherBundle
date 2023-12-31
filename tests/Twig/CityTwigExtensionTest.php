<?php

namespace Vitber\WeatherBundle\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;
use Vitber\WeatherBundle\CityProvider\CityProviderInterface;
use Vitber\WeatherBundle\Twig\CityTwigExtension;

class CityTwigExtensionTest extends TestCase
{
    private CityProviderInterface $cityProvider;
    private CityTwigExtension $twigExtension;

    public function setUp(): void
    {
        $this->cityProvider = $this->createMock(CityProviderInterface::class);

        $this->twigExtension = new CityTwigExtension($this->cityProvider);
    }

    public function testGetFunctions()
    {
        $functions = $this->twigExtension->getFunctions();
        self::assertCount(1, $functions);

        /** @var TwigFunction $function */
        $function = $functions[0];
        self::assertInstanceOf(TwigFunction::class, $function);
        self::assertEquals('get_weather_cities', $function->getName());
    }

    public function testGetCities()
    {
        $cities = ['London'];
        $this->cityProvider->expects($this->once())
            ->method('getCities')
            ->willReturn($cities);

        $this->assertEquals($cities, $this->twigExtension->getCities());
    }
}
