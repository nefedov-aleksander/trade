<?php


namespace TradeTest\Http;


use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Trade\Api\Client\Order\CreateOrderRequest;
use Trade\Api\Client\OrderApiClient;
use Trade\Api\Config\SettingValue;
use Trade\Api\Http\Client\CurlHttpClient;
use Trade\Api\Http\Factory;
use Trade\Api\Http\Response\Response;

final class OrderTest extends TestCase
{
    private SettingValue $settings;
    private MockObject $http;

    protected function setUp(): void
    {
        $this->settings = new SettingValue([
            'host' => 'https://payeer.com/Api/trade',
            'api-id' => 'fdgsdfgssdf',
            'secret' => 'secret'
        ]);

        $this->http = $this->createMock(CurlHttpClient::class);
    }

    public function testGetOrders()
    {
        $responseMock = new Response("{
          \"success\": true,
          \"pairs\": {
            \"BTC_USD\": {
              \"ask\": \"43790.00\",
              \"bid\": \"43520.00\",
              \"asks\": [],
              \"bids\": []
            }
          }
        }");

        $this->http->method('send')->willReturn($responseMock);

        $client = new OrderApiClient($this->http, $this->settings, new Factory());

        $list = $client->getOrders('BTC_USDT');
        $this->assertEquals('BTC_USD', $list->firstOrDefault()?->type);
        $this->assertEquals(43790.00, $list->firstOrDefault()?->ask);
    }

    public function testCreateOrder()
    {
        $responseMock = new Response("{
          \"success\": true,
          \"order_id\": 37054386,
          \"params\": {
            \"pair\": \"TRX_USD\",
            \"type\": \"limit\",
            \"action\": \"buy\",
            \"amount\": \"10.000000\",
            \"price\": \"0.08000\",
            \"value\": \"0.80\"
          }
        }");

        $this->http->method('send')->willReturn($responseMock);

        $client = new OrderApiClient($this->http, $this->settings, new Factory());

        $order = $client->createOrder(new CreateOrderRequest('TRX_USD', 'limit', 'buy', 10, 0.08));

        $this->assertEquals(37054386, $order->id);
    }

    public function testGetOrderStatus()
    {
        $responseMock = new Response("{
          \"success\": true,
          \"order\": {
            \"id\": \"37054293\",
            \"date\": 1644488809,
            \"pair\": \"TRX_USD\",
            \"action\": \"buy\",
            \"type\": \"limit\",
            \"status\": \"success\",
            \"amount\": \"10.000000\",
            \"price\": \"0.08000\",
            \"value\": \"0.80\",
            \"amount_processed\": \"10.000000\",
            \"amount_remaining\": \"0.000000\",
            \"value_processed\": \"0.72\",
            \"value_remaining\": \"0.08\",
            \"avg_price\": \"0.07200\",
            \"trades\": {
              \"14190472\": {
                \"id\": \"14190472\",
                \"date\": 1644488809,
                \"status\": \"success\",
                \"price\": \"0.07150\",
                \"amount\": \"0.054165\",
                \"value\": \"0.01\",
                \"is_maker\": false,
                \"is_taker\": true,
                \"t_transaction_id\": \"1598693542\"
              }
            }
          }
        }");

        $this->http->method('send')->willReturn($responseMock);

        $client = new OrderApiClient($this->http, $this->settings, new Factory());

        $order = $client->getOrderStatus(37054293);

        $this->assertEquals(37054293, $order->id);
        $this->assertCount(1, $order->trades);
    }
}