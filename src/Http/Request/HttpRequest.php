<?php


namespace Trade\Api\Http\Request;


use Trade\Api\Generic\ListInterface;
use Trade\Api\Generic\Pair;
use Trade\Api\Generic\SimpleList;
use Trade\Api\Http\Header\HttpHeader;

class HttpRequest
{
    private HttpRequestType $method;
    private string $uri;
    private ListInterface $params;
    private ListInterface $headers;

    public function __construct(
        HttpRequestType $method,
        string $uri,
        ListInterface $params = null,
        ListInterface $headers = null)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->params = $params ?? new SimpleList(Pair::class);
        $this->headers = $headers ?? new SimpleList(HttpHeader::class);
    }

    public function getMethod() : HttpRequestType
    {
        return $this->method;
    }

    public function getUri() : string
    {
        return $this->uri;
    }

    public function getParams() : ListInterface
    {
        return $this->params;
    }

    public function getHeaders() : ListInterface
    {
        return $this->headers;
    }
}