<?php


namespace Trade\Api\Http\Response;


class Response implements ResponseInterface
{
    private array $data;

    public function __construct(string $data)
    {
        $this->data = json_decode($data, true);
    }

    public function isSuccess(): bool
    {
        return $this->data['success'];
    }

    public function map(string $model,\Closure $map)
    {
        return $map(new $model(), $this->data);
    }

    public function getError(): string
    {
        return $this->data['error']['code'];
    }
}