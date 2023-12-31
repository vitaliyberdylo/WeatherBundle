<?php

namespace Vitber\WeatherBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Vitber\WeatherBundle\WeatherProvider\WeatherProvider;

class WeatherProviderCompilerPath implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has(WeatherProvider::class)) {
            return;
        }

        $definition = $container->findDefinition(WeatherProvider::class);

        $taggedServices = $container->findTaggedServiceIds('vitber.weather_provider');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addProvider', [new Reference($id)]);
        }
    }
}
