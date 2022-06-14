<?php


namespace Trade\Api\Generic;


class Pair
{
    public readonly string $key;
    public readonly string $value;

    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}