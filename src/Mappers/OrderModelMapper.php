<?php


namespace Trade\Api\Mappers;


use Trade\Api\Generic\ListInterface;
use Trade\Api\Generic\SimpleList;
use Trade\Api\Models\CreatedOrderModel;
use Trade\Api\Models\Order\OrderItemModel;
use Trade\Api\Models\OrderModel;

final class OrderModelMapper
{
    public static function mapOrders(): \Closure
    {
        /**
         * @return ListInterface<OrderModel>
         */
        return function (OrderModel $model, array $data): ListInterface {
            $list = new SimpleList($model::class);
            foreach ($data['pairs'] as $key => $value) {
                $order = clone $model;
                $order->type = $key;
                $order->ask = (float)$value['ask'];
                $order->bid = (float)$value['bid'];
                $order->asks = new SimpleList(OrderItemModel::class);
                foreach ($value['asks'] as $ask)
                {
                    $order->asks->add(self::mapOrderItemModel($ask));
                }
                $order->bids = new SimpleList(OrderItemModel::class);
                foreach ($value['bids'] as $bid)
                {
                    $order->bids->add(self::mapOrderItemModel($bid));
                }
                $list->add($order);
            }
            return $list;
        };
    }

    private static function mapOrderItemModel($item): OrderItemModel
    {
        $orderItem = new OrderItemModel();
        $orderItem->price = (float) $item['price'];
        $orderItem->amount = (float) $item['amount'];
        $orderItem->value = (float) $item['value'];
        return $orderItem;
    }

    public static function mapCreateOrder(): \Closure
    {
        return function (CreatedOrderModel $model, array $data): CreatedOrderModel {
            $model->id = $data['order_id'];
            $model->pair = $data['params']['pair'];
            $model->type = $data['params']['type'];
            $model->action = $data['params']['action'];
            $model->amount = $data['params']['amount'];
            $model->price = $data['params']['price'];
            $model->value = $data['params']['value'];
            return $model;
        };
    }

}