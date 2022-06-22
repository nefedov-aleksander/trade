<?php


namespace Trade\Api\Mappers;


use Trade\Api\Generic\ListInterface;
use Trade\Api\Generic\SimpleList;
use Trade\Api\Models\CreatedOrderModel;
use Trade\Api\Models\MyOrderModel;
use Trade\Api\Models\Order\OrderItemModel;
use Trade\Api\Models\Order\TradeModel;
use Trade\Api\Models\OrderModel;
use Trade\Api\Models\OrderStatusModel;

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

    public static function mapOrderStatus(): \Closure
    {
        return function (OrderStatusModel $model, array $data): OrderStatusModel {

            $model->id = $data['order']['id'];
            $model->date = $data['order']['date'];
            $model->pair = $data['order']['pair'];
            $model->action = $data['order']['action'];
            $model->type = $data['order']['type'];
            $model->status = $data['order']['status'];
            $model->amount = $data['order']['amount'];
            $model->price = $data['order']['price'];
            $model->value = $data['order']['value'];
            $model->amountProcessed = $data['order']['amount_processed'];
            $model->amountRemaining = $data['order']['amount_remaining'];
            $model->valueProcessed = $data['order']['value_processed'];
            $model->valueRemaining = $data['order']['value_remaining'];
            $model->avgPrice = $data['order']['avg_price'];
            $model->trades = new SimpleList(TradeModel::class);

            foreach ($data['order']['trades'] as $trade)
            {
                $model->trades->add(self::mapOrderTradeModel($trade));
            }

            return $model;
        };
    }

    private static function mapOrderTradeModel($trade)
    {
        $model = new TradeModel();
        $model->id = $trade['id'];
        $model->date = $trade['date'];
        $model->status = $trade['status'];
        $model->price = $trade['price'];
        $model->amount = $trade['amount'];
        $model->value = $trade['value'];
        $model->isMaker = $trade['is_maker'];
        $model->isTaker = $trade['is_taker'];
        $model->transactionId = $trade['t_transaction_id'];
        return$model;
    }

    public static function mapMyOrders(): \Closure
    {
        /**
         * @return ListInterface<MyOrderModel>
         */
        return function (MyOrderModel $model, array $data): ListInterface {
            $list = new SimpleList($model::class);
            foreach ($data['items'] as $item) {
                $order = clone $model;
                $order->id = $item['id'];
                $order->date = $item['date'];
                $order->pair = $item['pair'];
                $order->action = $item['action'];
                $order->type = $item['type'];
                $order->amount = $item['amount'];
                $order->price = $item['price'];
                $order->value = $item['value'];
                $order->amountProcessed = $item['amount_processed'];
                $order->amountRemaining = $item['amount_remaining'];
                $order->valueProcessed = $item['value_processed'];
                $order->valueRemaining = $item['value_remaining'];

                $list->add($order);
            }
            return $list;
        };
    }

}