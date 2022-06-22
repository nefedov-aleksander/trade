<?php


namespace Trade\Api\Models;


use Trade\Api\Generic\ListInterface;

class OrderStatusModel
{
    public int $id;
    public int $date;
    public string $pair;
    public string $action;
    public string $type;
    public string $status;
    public float $amount;
    public float $price;
    public float $value;
    public float $amountProcessed;
    public float $amountRemaining;
    public float $valueProcessed;
    public float $valueRemaining;
    public float $avgPrice;

    /**
     * @var ListInterface<TradeModel>
     */
    public ListInterface $trades;
}