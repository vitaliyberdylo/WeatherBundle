<?php

namespace Vitber\WeatherBundle\WeatherProvider\OpenWeatherMap;

use Psr\Log\LoggerInterface;
use Vitber\WeatherBundle\WeatherProvider\NamedWeatherProviderInterface;
use Vitber\WeatherBundle\WeatherProvider\OpenWeatherMap\Weather\Request\WeatherRequest;
use Vitber\WeatherBundle\WeatherProvider\OpenWeatherMap\Weather\Response\Weather;
use Vitber\WeatherBundle\WeatherProvider\OpenWeatherMap\Weather\Response\WeatherResponse;
use Vitber\WeatherBundle\WeatherProvider\WeatherProviderResponse;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenWeatherMapProvider implements NamedWeatherProviderInterface
{
    private const NAME = 'open_weather_map';

    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger,
        private string $apiUrl,
        private string $apiKey,
        private string $units,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getWeatherData(string $city): WeatherProviderResponse
    {
        $request = new WeatherRequest(
            $city,
            $this->apiKey,
            $this->units,
        );

        $response = $this->client->request(
            'GET',
            $this->apiUrl . WeatherRequest::URL,
            [
                'query' => (array) $request
            ]
        );

        $content = $response->getContent();
        $this->logger->info('Open Weather Map API response', ['response' => $content]);

        $clientResponse = $this->getSerializer()->deserialize($content, WeatherResponse::class, 'json');

        $weatherList = $clientResponse->weather;
        /** @var Weather $weather */
        $weather = reset($weatherList);

        return new WeatherProviderResponse(
            $clientResponse->main->temp,
            $weather->main
        );
    }

    private function getSerializer(): Serializer
    {
        return new Serializer(
            [new ArrayDenormalizer(), new ObjectNormalizer(
                null,
                new CamelCaseToSnakeCaseNameConverter(),
                null,
                new PhpDocExtractor()
            )],
            [new JsonEncoder()]
        );
    }
}
