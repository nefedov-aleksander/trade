<?php


namespace Trade\Api\Models\Info;


use Trade\Api\Generic\ListInterface;

class LimitModel
{
    public ListInterface $requests;
    public ListInterface $weights;
    public ListInterface $orders;
}