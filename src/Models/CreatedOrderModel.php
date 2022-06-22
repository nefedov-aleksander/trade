<?php


namespace Trade\Api\Models;


class CreatedOrderModel
{
    public int $id;
    public string $pair;
    public string $type;
    public string $action;
    public float $amount;
    public float $price;
    public float $value;
}