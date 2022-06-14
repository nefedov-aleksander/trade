<?php

namespace TradeTest\Generic;


use PHPUnit\Framework\TestCase;
use Trade\Api\Generic\Pair;

final class PairTest extends TestCase
{
    public function testPair()
    {
        $pair = new Pair('key', 'value');

        $this->assertEquals('key', $pair->key);
        $this->assertEquals('value', $pair->value);
    }
}