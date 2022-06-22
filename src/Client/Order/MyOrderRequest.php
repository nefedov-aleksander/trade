<?php


namespace Trade\Api\Client\Order;


class MyOrderRequest
{
    public string|null $pair;
    public string|null $action;

    public function __construct(string|null $pair = null, string|null $action = null)
    {
        $this->pair = $pair;
        $this->action = $action;
    }

    public function toArray()
    {
        $params = [];
        if($this->pair != null)
        {
            $params['pair'] = $this->pair;
        }

        if($this->action != null)
        {
            $params['action'] = $this->action;
        }

        return $params;
    }
}