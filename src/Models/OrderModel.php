<?php


namespace Trade\Api\Models;


use Trade\Api\Generic\ListInterface;

class OrderModel
{
    public string $type;
    public float $ask;
    public float $bid;
    /**
     * @var ListInterfac<OrderItemModel>
     */
    public ListInterface $asks;
    /**
     * @var ListInterfac<OrderItemModel>
     */
    public ListInterface $bids;
}