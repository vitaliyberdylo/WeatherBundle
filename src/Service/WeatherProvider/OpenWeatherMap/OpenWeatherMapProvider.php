<?php

namespace Vitber\WeatherBundle\Service\WeatherProvider\OpenWeatherMap;

use Vitber\WeatherBundle\Service\WeatherProvider\OpenWeatherMap\Forecast\Request\ForecastRequest;
use Vitber\WeatherBundle\Service\WeatherProvider\OpenWeatherMap\Forecast\Response\Data;
use Vitber\WeatherBundle\Service\WeatherProvider\OpenWeatherMap\Forecast\Response\ForecastResponse;
use Vitber\WeatherBundle\Service\WeatherProvider\OpenWeatherMap\Forecast\Response\Weather;
use Vitber\WeatherBundle\Service\WeatherProvider\NamedWeatherProviderInterface;
use Vitber\WeatherBundle\Service\WeatherProvider\WeatherProviderResponse;
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
        private string $apiKey,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getWeatherData(string $city): WeatherProviderResponse
    {
        $request = new ForecastRequest(
            $city,
            $this->apiKey
        );

        $response = $this->client->request(
            'GET',
            ForecastRequest::API_URL,
            [
                'query' => (array) $request
            ]
        );

        $content = $response->getContent();

        $clientResponse = $this->getSerializer()->deserialize($content, ForecastResponse::class, 'json');

        $list = $clientResponse->list;
        /** @var Data $data */
        $data = reset($list);
        $weatherList = $data->weather;
        /** @var Weather $weather */
        $weather = reset($weatherList);

        return new WeatherProviderResponse(
            $data->main->temp,
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
