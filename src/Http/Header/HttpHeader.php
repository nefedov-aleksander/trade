<?php


namespace Trade\Api\Http\Header;


class HttpHeader
{
    public readonly string $key;
    public readonly string $value;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}