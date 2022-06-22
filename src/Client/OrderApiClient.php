<?php


namespace Trade\Api\Client;


use Trade\Api\Client\Order\CreateOrderRequest;
use Trade\Api\Config\SettingValueInterface;
use Trade\Api\Generic\ListInterface;
use Trade\Api\Http\Client\HttpClientInterface;
use Trade\Api\Http\FactoryInterface;
use Trade\Api\Http\Request\HttpRequest;
use Trade\Api\Http\Request\HttpRequestType;
use Trade\Api\Mappers\OrderModelMapper;
use Trade\Api\Models\CreatedOrderModel;
use Trade\Api\Models\OrderModel;
use Trade\Api\Models\OrderStatusModel;

class OrderApiClient
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

    /**
     * @return ListInterface<OrderModel>
     */
    public function getOrders($pairs) : ListInterface
    {
        $method = 'orders';

        $params = $this->factory->createParamsFromArray([
            'pair' => $pairs
        ]);

        $signature = $this->factory->createSignature($this->settingValue->getOrThrow('secret'), $method, $params);

        $headers = $this->factory->createHeaders([
            'Content-Type' => 'application/json'
        ], $this->settingValue->getOrThrow('api-id'), $signature);


        $request = new HttpRequest(
            HttpRequestType::Post,
            "{$this->settingValue->getOrThrow('host')}/{$method}",
            $params,
            $headers
        );

        $response = $this->http->send($request);

        if(!$response->isSuccess())
        {
            throw new \Exception($response->getError());
        }

        return $response->map(OrderModel::class, OrderModelMapper::mapOrders());
    }

    public function createOrder(CreateOrderRequest $request): CreatedOrderModel
    {
        $method = 'order_create';

        $params = $this->factory->createParamsFromArray($request->toArray());

        $signature = $this->factory->createSignature($this->settingValue->getOrThrow('secret'), $method, $params);

        $headers = $this->factory->createHeaders([
            'Content-Type' => 'application/json'
        ], $this->settingValue->getOrThrow('api-id'), $signature);

        $request = new HttpRequest(
            HttpRequestType::Post,
            "{$this->settingValue->getOrThrow('host')}/{$method}",
            $params,
            $headers
        );

        $response = $this->http->send($request);

        if(!$response->isSuccess())
        {
            throw new \Exception($response->getError());
        }

        return $response->map(CreatedOrderModel::class, OrderModelMapper::mapCreateOrder());
    }

    public function getOrderStatus(int $orderId): OrderStatusModel
    {
        $method = 'order_status';

        $params = $this->factory->createParamsFromArray([
            'order_id' => $orderId
        ]);

        $signature = $this->factory->createSignature($this->settingValue->getOrThrow('secret'), $method, $params);

        $headers = $this->factory->createHeaders([
            'Content-Type' => 'application/json'
        ], $this->settingValue->getOrThrow('api-id'), $signature);

        $request = new HttpRequest(
            HttpRequestType::Post,
            "{$this->settingValue->getOrThrow('host')}/{$method}",
            $params,
            $headers
        );

        $response = $this->http->send($request);

        if(!$response->isSuccess())
        {
            throw new \Exception($response->getError());
        }

        return $response->map(OrderStatusModel::class, OrderModelMapper::mapOrderStatus());
    }
}