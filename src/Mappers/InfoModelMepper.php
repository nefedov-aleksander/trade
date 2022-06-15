<?php


namespace Trade\Api\Mappers;


use Trade\Api\Generic\SimpleList;
use Trade\Api\Models\Info\LimitModel;
use Trade\Api\Models\Info\PairModel;
use Trade\Api\Models\Info\RequestModel;
use Trade\Api\Models\InfoModel;

final class InfoModelMepper
{
    public static function mapInfoModel(): \Closure
    {
        return function (InfoModel $model, array $data): InfoModel
        {
            $model->limit = new LimitModel();
            $model->limit->requests = new SimpleList(RequestModel::class);

            foreach ($data['limits']['requests'] as $item)
            {
                $model->limit->requests->add(self::mapRequestModel($item));
            }

            $model->pairs = new SimpleList(PairModel::class);
            foreach ($data['pairs'] as $key => $item)
            {
                $model->pairs->add(self::mapPairModel($key, $item));
            }

            return $model;
        };
    }

    private static function mapRequestModel(array $data): RequestModel
    {
        $request = new RequestModel();
        $request->interval = (string) $data['interval'];
        $request->intervalNum = (int) $data['interval_num'];
        $request->limit = (int) $data['limit'];
        return $request;
    }

    private static function mapPairModel(string $type, array $data): PairModel
    {
        $pair = new PairModel();
        $pair->type = $type;
        $pair->pricePrec = (int) $data['price_prec'];
        $pair->minPrice = (float) $data['min_price'];
        $pair->maxPrice = (float) $data['max_price'];
        $pair->minAmount = (float) $data['min_amount'];
        $pair->minValue = (float) $data['min_value'];
        $pair->feeMakerPercent = (float) $data['fee_maker_percent'];
        $pair->feeTakerPercent = (float) $data['fee_taker_percent'];
        return $pair;
    }
}