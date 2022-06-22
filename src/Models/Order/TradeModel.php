<?php


namespace Trade\Api\Models\Order;


class TradeModel
{
    public int $id;
    public int $date;
    public string $status;
    public float $price;
    public float $amount;
    public float $value;
    public bool $isMaker;
    public bool $isTaker;
    public int $transactionId;
}