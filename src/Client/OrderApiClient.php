<?php


namespace Trade\Api\Client;


use Trade\Api\Client\Order\CreateOrderRequest;
use Trade\Api\Config\SettingValueInterface;
use Trade\Api\Generic\ListInterface;
use Trade\Api\Http\Auth\SignatureInterface;
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

        $headers = $this->headers($this->signature($method, $params));


        $request = new HttpRequest(
            HttpRequestType::Post,
            $this->uri($method),
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

        $headers = $this->headers($this->signature($method, $params));

        $request = new HttpRequest(
            HttpRequestType::Post,
            $this->uri($method),
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

        $headers = $this->headers($this->signature($method, $params));

        $request = new HttpRequest(
            HttpRequestType::Post,
            $this->uri($method),
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

    private function signature(string $method, ListInterface $params): SignatureInterface
    {
        return $this->factory->createSignature($this->settingValue->getOrThrow('secret'), $method, $params);;
    }

    private function headers(SignatureInterface $signature): ListInterface
    {
        return $this->factory->createHeaders([
            'Content-Type' => 'application/json'
        ], $this->settingValue->getOrThrow('api-id'), $signature);
    }

    private function uri(string $method): string
    {
        return "{$this->settingValue->getOrThrow('host')}/{$method}";
    }
}