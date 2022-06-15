<?php


namespace TradeTest\Http;


use PHPUnit\Framework\TestCase;
use Trade\Api\Http\Response\Response;
use Trade\Api\Mappers\InfoModelMepper;
use Trade\Api\Mappers\OrderModelMapper;
use Trade\Api\Models\Info\PairModel;
use Trade\Api\Models\InfoModel;
use Trade\Api\Models\OrderModel;

final class ResponseTest extends TestCase
{
    public function testResponse()
    {
        $data = json_encode([
            "success" => true,
            "data" => []
        ]);
        $response = new Response($data);
        $this->assertTrue($response->isSuccess());

        $data = json_encode([
            "success" => false,
            "error" => [
                'code' => '422'
            ]
        ]);
        $response = new Response($data);
        $this->assertFalse($response->isSuccess());
        $this->assertEquals('422', $response->getError());
    }

    public function testMapInfoResponse()
    {
        $data = "{
          \"success\": true,
          \"limits\": {
            \"requests\": [
              {
                \"interval\": \"min\",
                \"interval_num\": 1,
                \"limit\": 600
              }
            ]
          },
          \"pairs\": {
            \"BTC_USD\": {
              \"price_prec\": 2,
              \"min_price\": \"4375.74\",
              \"max_price\": \"83139.00\",
              \"min_amount\": 0.0001,
              \"min_value\": 0.5,
              \"fee_maker_percent\": 0.01,
              \"fee_taker_percent\": 0.095
            },
            \"BTC_RUB\": {
              \"price_prec\": 2,
              \"min_price\": \"326269.32\",
              \"max_price\": \"6199117.08\",
              \"min_amount\": 0.0001,
              \"min_value\": 20,
              \"fee_maker_percent\": 0.01,
              \"fee_taker_percent\": 0.095
            },
            \"BTC_EUR\": {
              \"price_prec\": 2,
              \"min_price\": \"3798.60\",
              \"max_price\": \"72173.39\",
              \"min_amount\": 0.0001,
              \"min_value\": 0.5,
              \"fee_maker_percent\": 0.01,
              \"fee_taker_percent\": 0.095
            }
          }
        }";

        $response = new Response($data);
        $this->assertTrue($response->isSuccess());

        $model = $response->map(InfoModel::class, InfoModelMepper::mapInfoModel());

        $this->assertInstanceOf(InfoModel::class, $model);

        $this->assertEquals('min', $model->limit->requests->firstOrDefault()?->interval);
        $this->assertEquals(1, $model->limit->requests->firstOrDefault()?->intervalNum);
        $this->assertEquals(600, $model->limit->requests->firstOrDefault()?->limit);

        $this->assertCount(3, $model->pairs);
        $btcUsd = $model->pairs->firstOrDefault();
        $this->assertInstanceOf(PairModel::class, $btcUsd);
        $this->assertEquals("BTC_USD", $btcUsd->type);
        $this->assertEquals(2, $btcUsd->pricePrec);
        $this->assertEquals(4375.74, $btcUsd->minPrice);
        $this->assertEquals(83139.00, $btcUsd->maxPrice);
        $this->assertEquals(0.0001, $btcUsd->minAmount);
        $this->assertEquals(0.5, $btcUsd->minValue);
        $this->assertEquals(0.01, $btcUsd->feeMakerPercent);
        $this->assertEquals(0.095, $btcUsd->feeTakerPercent);
    }

    public function testMapOrdersResponse()
    {
        $data = "{
          \"success\": true,
          \"pairs\": {
            \"BTC_USD\": {
              \"ask\": \"43790.00\",
              \"bid\": \"43520.00\",
              \"asks\": [
                {
                  \"price\": \"43790.00\",
                  \"amount\": \"0.00031422\",
                  \"value\": \"13.76\"
                },
                {
                  \"price\": \"43800.00\",
                  \"amount\": \"0.00125530\",
                  \"value\": \"54.99\"
                }
              ],
              \"bids\": [
                {
                  \"price\": \"43520.00\",
                  \"amount\": \"0.00034788\",
                  \"value\": \"15.13\"
                },
                {
                  \"price\": \"43502.00\",
                  \"amount\": \"0.04065736\",
                  \"value\": \"1768.67\"
                }
              ]
            },
            \"BTC_RUB\": {
              \"ask\": \"3255999.99\",
              \"bid\": \"3238600.00\",
              \"asks\": [
                {
                  \"price\": \"3255999.99\",
                  \"amount\": \"0.00010000\",
                  \"value\": \"325.60\"
                }
              ],
              \"bids\": [
                {
                  \"price\": \"3238600.00\",
                  \"amount\": \"0.00022212\",
                  \"value\": \"719.35\"
                },
                {
                  \"price\": \"3230001.02\",
                  \"amount\": \"0.00083607\",
                  \"value\": \"2700.50\"
                }
              ]
            }
          }
        }";

        $response = new Response($data);
        $this->assertTrue($response->isSuccess());

        $list = $response->map(OrderModel::class, OrderModelMapper::mapOrders());

        $this->assertCount(2, $list);

        $this->assertEquals('BTC_USD', $list->firstOrDefault()?->type);
        $this->assertEquals(43790.00, $list->firstOrDefault()?->ask);
        $this->assertEquals(43520.00, $list->firstOrDefault()?->bid);

        $this->assertEquals(43790.00, $list->firstOrDefault()?->asks->firstOrDefault()?->price);
        $this->assertEquals(0.00031422, $list->firstOrDefault()?->asks->firstOrDefault()?->amount);
        $this->assertEquals(13.76, $list->firstOrDefault()?->asks->firstOrDefault()?->value);
    }
}