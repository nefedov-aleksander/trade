<?php


namespace Trade\Api\Http;


use Trade\Api\Generic\ListInterface;
use Trade\Api\Generic\Pair;
use Trade\Api\Http\Auth\SignatureInterface;
use Trade\Api\Http\Header\HttpHeader;

interface FactoryInterface
{
    /**
     * @param array $params
     * @return ListInterface<Pair>
     */
    public function createParamsFromArray(array $params): ListInterface;

    /**
     * @param string $secret
     * @param string $method
     * @param ListInterface<Pair> $params
     * @return SignatureInterface
     */
    public function createSignature(string $secret, string $method, ListInterface $params): SignatureInterface;

    /**
     * @param array $params
     * @return ListInterface<HttpHeader>
     */
    public function createHeaders(array $headers, string $apiId, SignatureInterface $signature): ListInterface;


}