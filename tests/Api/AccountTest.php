<?php


namespace TradeTest\Api;


use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Trade\Api\Client\AccountApiClient;
use Trade\Api\Config\SettingValue;
use Trade\Api\Http\Client\CurlHttpClient;
use Trade\Api\Http\Factory;
use Trade\Api\Http\Response\Response;

class AccountTest extends TestCase
{
    private SettingValue $settings;
    private MockObject $http;

    protected function setUp(): void
    {
        $this->settings = new SettingValue([
            'host' => 'https://payeer.com/api/trade',
            'api-id' => 'bd443f00-092c-4436-92a4-a704ef679e24',
            'secret' => 'api_secret_key'
        ]);

        $this->http = $this->createMock(CurlHttpClient::class);
    }

    public function testAccount()
    {
        $responseMock = new Response("{
          \"success\": true,
          \"balances\": {
            \"USD\": {
              \"total\": 0.92,
              \"available\": 0.92,
              \"hold\": 0
            },
            \"RUB\": {
              \"total\": 1598.99,
              \"available\": 1548.99,
              \"hold\": 50
            },
            \"EUR\": {
              \"total\": 2.97,
              \"available\": 0,
              \"hold\": 2.97
            }
          }
        }");

        $this->http->method('send')->willReturn($responseMock);
        $client = new AccountApiClient($this->http, $this->settings, new Factory());

        $this->assertCount(3, $client->getBalance());
    }
}