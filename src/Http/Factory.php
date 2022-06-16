<?php


namespace Trade\Api\Http;


use Trade\Api\Generic\ListInterface;
use Trade\Api\Generic\Pair;
use Trade\Api\Generic\SimpleList;
use Trade\Api\Http\Auth\SignatureInterface;
use Trade\Api\Http\Auth\SignatureSha256;
use Trade\Api\Http\Header\HttpHeader;

class Factory implements FactoryInterface
{

    public function createParamsFromArray(array $params): ListInterface
    {
        $list = new SimpleList(Pair::class);

        foreach ($params as $key => $value)
        {
            $list->add(new Pair($key, $value));
        }
        $list->add(new Pair('ts', round(microtime(true) * 1000)));

        return $list;
    }

    public function createSignature(string $secret, string $method, ListInterface $params): SignatureInterface
    {
        $fields = $params->select(fn($x) => [$x->key => $x->value])->toArray();

        return new SignatureSha256($secret, $method, array_merge(...$fields));
    }

    public function createHeaders(array $headers, string $apiId, SignatureInterface $signature): ListInterface
    {
        $list = new SimpleList(HttpHeader::class);
        foreach ($headers as $key => $value)
        {
            $list->add(new HttpHeader($key, $value));
        }
        $list->add(new HttpHeader('API-ID', $apiId));
        $list->add(new HttpHeader('API-SIGN', $signature->getHash()));

        return $list;
    }
}