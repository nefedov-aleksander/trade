<?php


namespace Trade\Api\Mappers;


use Trade\Api\Generic\ListInterface;
use Trade\Api\Generic\SimpleList;
use Trade\Api\Models\BalanceModel;

class AccountMapper
{
    public static function mapBalances(): \Closure
    {
        /**
         * @return ListInterface<BalanceModel>
         */
        return function (BalanceModel $model, array $data): ListInterface {
            $list = new SimpleList($model::class);
            foreach ($data['balances'] as $type => $item) {
                $balance = clone $model;
                $balance->type = $type;
                $balance->total = $item['total'];
                $balance->available = $item['available'];
                $balance->hold = $item['hold'];

                $list->add($balance);
            }
            return $list;
        };
    }
}