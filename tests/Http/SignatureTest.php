<?php
namespace TradeTest\Http;

use PHPUnit\Framework\TestCase;
use Trade\Api\Http\Auth\SignatureSha256;
use Trade\Api\Http\Signature;

final class SignatureTest extends TestCase
{
    private function requestProvider()
    {
        return [
            [
                'secret',
                'test',
                ['q' => 1],
                '4ad199f681db605ac1114a7d7176fb2990553cfd865c5a54382628c8109d78c7'
            ],

            [
                'poiuyjn',
                'load',
                ['q' => 1, 'w' => 2, 'x' => 'yyy'],
                hash_hmac('sha256', 'load'.json_encode(['q' => 1, 'w' => 2, 'x' => 'yyy']), 'poiuyjn')
            ]
        ];
    }

    /**
     * @dataProvider requestProvider
     */
    public function testSignature($secret, $method, $data, $excepted)
    {
        $sign = new SignatureSha256($secret, $method, $data);

        $this->assertEquals($excepted, $sign->getHash());
    }
}