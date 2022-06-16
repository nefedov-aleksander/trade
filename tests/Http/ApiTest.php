<?php


namespace TradeTest\Http;


use PHPUnit\Framework\TestCase;
use Trade\Api\Api\OrderApiClient;
use Trade\Api\Config\SettingValue;
use Trade\Api\Http\Client\CurlHttpClient;
use Trade\Api\Http\Factory;
use Trade\Api\Http\Response\Response;

final class ApiTest extends TestCase
{
    public function testOrderApiClient()
    {
        $settings = new SettingValue([
            'host' => 'https://payeer.com/api/trade',
            'api-id' => 'fdgsdfgssdf',
            'secret' => 'secret'
        ]);

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

        $http = $this->createMock(CurlHttpClient::class);
        $http->method('send')->willReturn($responseMock);

        $client = new OrderApiClient($http, $settings, new Factory());

        $list = $client->getOrders('BTC_USDT');
        $this->assertEquals('BTC_USD', $list->firstOrDefault()?->type);
        $this->assertEquals(43790.00, $list->firstOrDefault()?->ask);
    }
}