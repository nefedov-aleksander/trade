<?php


namespace Trade\Api\Api\Order;


class CreateOrderRequest
{
    public readonly string $pair;
    public readonly string $type;
    public readonly string $action;
    public readonly string $amount;
    public readonly float $price;

    public function __construct(
        string $pair,
        string $type,
        string $action,
        string $amount,
        float $price
    )
    {
        $this->pair = $pair;
        $this->type = $type;
        $this->action = $action;
        $this->amount = $amount;
        $this->price = $price;
    }

    public function toArray()
    {
        return [
            'pair' => $this->pair,
            'type' => $this->type,
            'action' => $this->action,
            'amount' => $this->amount,
            'price' => $this->price,
        ];
    }
}