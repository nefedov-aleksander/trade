<?php


namespace TradeTest\Http;


use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Trade\Api\Api\OrderApiClient;
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
}