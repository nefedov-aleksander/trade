<?php


namespace TradeTest\Http;


use PHPUnit\Framework\TestCase;
use Trade\Api\Http\Factory;

final class FactoryTest extends TestCase
{
    /**
     * @dataProvider factoryProvider
     */
    public function testFactory($method, $data)
    {
        $factory = new Factory();

        $params = $factory->createParamsFromArray($data);

        $this->assertCount(3, $params);
        $this->assertCount(1, $params->where(fn($x) => $x->key == 'ts'));

        $data = array_merge($data, [
            'ts' => $params->where(fn($x) => $x->key == 'ts')->firstOrDefault()->value
        ]);


        $signature = $factory->createSignature('secret', $method, $params);

        $excepted = hash_hmac('sha256', $method.json_encode($data), 'secret');
        $this->assertEquals($excepted, $signature->getHash());

        $headers = $factory->createHeaders([
            'header' => 'value'
        ], 'api', $signature);

        $this->assertCount(3, $headers);
        $this->assertEquals('value', $headers->where(fn($x) => $x->key == 'header')->firstOrDefault()?->value);
        $this->assertEquals('api', $headers->where(fn($x) => $x->key == 'API-ID')->firstOrDefault()?->value);
        $this->assertEquals($signature->getHash(), $headers->where(fn($x) => $x->key == 'API-SIGN')->firstOrDefault()?->value);
    }

    private function factoryProvider()
    {
        return [
            ['test', ['a' => '1', 'b' => 'g']]
        ];
    }
}