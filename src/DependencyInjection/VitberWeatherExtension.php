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
            'Vitber\WeatherBundle\Service\WeatherProvider\OpenWeatherMap\OpenWeatherMapProvider'
        );
        $weatherProviderDefinition->replaceArgument(1, $config['open_weather_map']['api_key']);
    }
}
