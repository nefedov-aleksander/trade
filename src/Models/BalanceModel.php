<?php


namespace Trade\Api\Models;


class BalanceModel
{
    public string $type;
    public float $total;
    public float $available;
    public float $hold;
}