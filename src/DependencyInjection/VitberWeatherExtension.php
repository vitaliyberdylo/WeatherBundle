<?php

namespace Vitber\WeatherBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class VitberWeatherExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator([__DIR__ . '/../../config']));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $weatherProviderDefinition = $container->getDefinition(
            'Vitber\WeatherBundle\WeatherProvider\OpenWeatherMap\OpenWeatherMapProvider'
        );

        $weatherProviderDefinition->replaceArgument(2, $config['open_weather_map']['api_url']);
        $weatherProviderDefinition->replaceArgument(3, $config['open_weather_map']['api_key']);
        $weatherProviderDefinition->replaceArgument(4, $config['open_weather_map']['units']);

        $cityProviderDefinition = $container->getDefinition(
            'Vitber\WeatherBundle\CityProvider\CityProvider'
        );
        $cityProviderDefinition->addArgument($config['cities']);

        $container->setParameter('weather_provider', $config['weather_provider']);
        $container->setParameter('cities', $config['cities']);
    }
}
