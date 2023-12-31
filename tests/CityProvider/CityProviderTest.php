<?php

namespace Vitber\WeatherBundle\Tests\CityProvider;

use PHPUnit\Framework\TestCase;
use Vitber\WeatherBundle\CityProvider\CityProvider;

class CityProviderTest extends TestCase
{
    /**
     * @dataProvider getCitiesDataProvider
     * @param array $cities
     * @return void
     */
    public function testGetCities(array $cities)
    {
        $cityProvider = new CityProvider($cities);
        $this->assertEquals($cities, $cityProvider->getCities());
    }

    public function getCitiesDataProvider(): array
    {
        return [
            'empty' => [
                'cities' => [],
            ],
            'not_empty' => [
                'cities' => [
                    'London',
                    'New York',
                    'Halle (Saale),DE',
                    'Lübeck,DE'
                ]
            ]
        ];
    }
}
