<?php


namespace TradeTest\Api;


use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Trade\Api\Api\InfoApiClient;
use Trade\Api\Config\SettingValue;
use Trade\Api\Http\Client\CurlHttpClient;
use Trade\Api\Http\Factory;
use Trade\Api\Http\Response\Response;

class InfoTest extends TestCase
{
    private SettingValue $settings;
    private MockObject $http;

    protected function setUp(): void
    {
        $this->settings = new SettingValue([
            'host' => 'https://payeer.com/api/trade',
            'api-id' => 'fdgsdfgssdf',
            'secret' => 'secret'
        ]);

        $this->http = $this->createMock(CurlHttpClient::class);
    }

    public function testGetInfo()
    {
        $responseMock = new Response("{
          \"success\": true,
          \"limits\": {
            \"requests\": [
              {
                \"interval\": \"min\",
                \"interval_num\": 1,
                \"limit\": 600
              }
            ]
          },
          \"pairs\": {
            \"BTC_USD\": {
              \"price_prec\": 2,
              \"min_price\": \"4375.74\",
              \"max_price\": \"83139.00\",
              \"min_amount\": 0.0001,
              \"min_value\": 0.5,
              \"fee_maker_percent\": 0.01,
              \"fee_taker_percent\": 0.095
            }
          }
        }");

        $this->http->method('send')->willReturn($responseMock);
        $client = new InfoApiClient($this->http, $this->settings, new Factory());

        $info = $client->getInfo();
        $this->assertCount(1, $info->limit->requests);
        $this->assertCount(1, $info->pairs);
    }
}