<?php


namespace Trade\Api\Models\Info;


class PairModel
{
    public string $type;
    public int $pricePrec;
    public float $minPrice;
    public float $maxPrice;
    public float $minAmount;
    public float $minValue;
    public float $feeMakerPercent;
    public float $feeTakerPercent;
}