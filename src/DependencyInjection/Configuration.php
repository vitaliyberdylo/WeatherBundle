<?php

namespace Vitber\WeatherBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('vitber_weather');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('weather_provider')->defaultValue('open_weather_map')->end()
                ->variableNode('cities')->end()
                ->arrayNode('open_weather_map')
                    ->children()
                        ->scalarNode('api_url')->defaultValue('https://api.openweathermap.org/data/2.5/')->end()
                        ->scalarNode('api_key')->isRequired()->end()
                        ->scalarNode('units')->defaultValue('metric')->end()
                    ->end()
                ->end() // open_weather_map
            ->end();

        return $treeBuilder;
    }
}
