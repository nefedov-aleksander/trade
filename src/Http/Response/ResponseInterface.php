<?php


namespace Trade\Api\Http\Response;


interface ResponseInterface
{
    public function isSuccess(): bool;
    public function map(string $model, \Closure $map);
    public function getError(): string;
}