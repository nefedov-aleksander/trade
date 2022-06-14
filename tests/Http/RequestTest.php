<?php

namespace TradeTest\Http;

use PHPUnit\Framework\TestCase;
use Trade\Api\Generic\ListInterface;
use Trade\Api\Generic\Pair;
use Trade\Api\Generic\SimpleList;
use Trade\Api\Http\Header\HeaderRepository;
use Trade\Api\Http\Header\HttpHeader;
use Trade\Api\Http\Request\HttpRequest;
use Trade\Api\Http\Request\HttpRequestType;

final class RequestTest extends TestCase
{
    private function requestProvider()
    {
        return [
            [
                HttpRequestType::Get,
                'test',
                new SimpleList(Pair::class),
                new SimpleList(HttpHeader::class)
            ],

            [
                HttpRequestType::Post,
                'a',
                new SimpleList(Pair::class),
                new SimpleList(HttpHeader::class)
            ],

            [
                HttpRequestType::Get,
                'p',
                new SimpleList(Pair::class,[
                    new Pair('a', 'b')
                ]),
                new SimpleList(HttpHeader::class, [
                    new HttpHeader('c', 'd')
                ])
            ],

            [
                HttpRequestType::Post,
                'p',
                new SimpleList(Pair::class,[
                    new Pair('a', 'b'),
                    new Pair('e', 1)
                ]),
                new SimpleList(HttpHeader::class, [
                    new HttpHeader('c', 'd'),
                    new HttpHeader('b', '123')
                ])
            ]
        ];
    }

    /**
     * @dataProvider requestProvider
     */
    public function testRequest($method, $uri, SimpleList $params, SimpleList $headers)
    {
        $request = new HttpRequest($method, $uri, $params, $headers);

        $this->assertEquals($method, $request->getMethod());
        $this->assertEquals($uri, $request->getUri());

        $this->assertInstanceOf(ListInterface::class, $request->getParams());
        $this->assertInstanceOf(ListInterface::class, $request->getHeaders());

        $this->assertCount($params->count(), $request->getParams());
        $this->assertCount($headers->count(), $request->getHeaders());
    }

    public function testHeaders()
    {
        $header = new HttpHeader('a', 'b');
        $this->assertEquals('a', $header->key);
        $this->assertEquals('b', $header->value);

        $fHeader = new HttpHeader('q', 'q');
        $request = new HttpRequest(
            HttpRequestType::Get,
            'test',
            null,
            new SimpleList(HttpHeader::class, [$fHeader])
        );

        $this->assertCount(1, $request->getHeaders());

        $fActual = $request->getHeaders()->where(fn($x) => $x->key == $fHeader->key)->firstOrDefault()?->value;
        $this->assertEquals($fHeader->value, $fActual);

        $sHeader = new HttpHeader('w', 'e');
        $request->getHeaders()->add($sHeader);

        $sActual = $request->getHeaders()->where(fn($x) => $x->key == $sHeader->key)->firstOrDefault()?->value;
        $this->assertCount(2, $request->getHeaders());
        $this->assertEquals($sHeader->value, $sActual);
    }

    public function testParams()
    {
        $fParam = new Pair('a', 'b');
        $request = new HttpRequest(
            HttpRequestType::Get,
            'test',
            new SimpleList(Pair::class, [$fParam])
        );

        $this->assertCount(1, $request->getParams());

        $fActual = $request->getParams()->where(fn($x) => $x->key == $fParam->key)->firstOrDefault()?->value;
        $this->assertEquals($fParam->value, $fActual);

        $sParam = new Pair('zz', 'yyy');
        $request->getParams()->add($sParam);

        $sActual = $request->getParams()->where(fn($x) => $x->key == $sParam->key)->firstOrDefault()?->value;
        $this->assertCount(2, $request->getParams());
        $this->assertEquals($sParam->value, $sActual);
    }
}