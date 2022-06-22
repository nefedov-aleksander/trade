<?php


namespace Trade\Api\Client;


use Trade\Api\Config\SettingValueInterface;
use Trade\Api\Http\Client\HttpClientInterface;
use Trade\Api\Http\FactoryInterface;
use Trade\Api\Http\Request\HttpRequest;
use Trade\Api\Http\Request\HttpRequestType;
use Trade\Api\Mappers\InfoModelMapper;
use Trade\Api\Mappers\InfoModelMepper;
use Trade\Api\Models\InfoModel;

class InfoApiClient
{
    private HttpClientInterface $http;
    private SettingValueInterface $settingValue;
    private FactoryInterface $factory;

    public function __construct(
        HttpClientInterface $http,
        SettingValueInterface $settingValue,
        FactoryInterface $factory)
    {
        $this->http = $http;
        $this->settingValue = $settingValue;
        $this->factory = $factory;
    }

    public function getInfo(): InfoModel
    {
        $method = 'info';

        $params = $this->factory->createParamsFromArray([]);

        $signature = $this->factory->createSignature($this->settingValue->getOrThrow('secret'), $method, $params);

        $headers = $this->factory->createHeaders([
            'Content-Type' => 'application/json'
        ], $this->settingValue->getOrThrow('api-id'), $signature);

        $request = new HttpRequest(
            HttpRequestType::Get,
            "{$this->settingValue->getOrThrow('host')}/{$method}",
            $params,
            $headers
        );

        $response = $this->http->send($request);

        if(!$response->isSuccess())
        {
            throw new \Exception($response->getError());
        }

        return $response->map(InfoModel::class, InfoModelMepper::mapInfoModel());
    }
}