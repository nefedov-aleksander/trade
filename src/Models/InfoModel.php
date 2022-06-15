<?php


namespace Trade\Api\Models;

use Trade\Api\Models\Info\LimitModel;
use Trade\Api\Generic\ListInterface;

class InfoModel
{
    public LimitModel $limit;

    /**
     * @var ListInterface<PairModel>
     */
    public ListInterface $pairs;
}