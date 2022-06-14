<?php


namespace TradeTest\Generic;


use PHPUnit\Framework\TestCase;
use Trade\Api\Generic\SimpleList;

class ListTest extends TestCase
{
    public function testAddMixedValues()
    {
        $this->expectException(\InvalidArgumentException::class);

        $list = new SimpleList('integer', [1]);

        $list->add('t');
    }

    public function testInitMixedValues()
    {
        $this->expectException(\InvalidArgumentException::class);

        $list = new SimpleList('integer', [1, 's']);
    }

    public function testList()
    {
        $intList = new SimpleList('integer', [1, 2, 3]);
        $this->assertCount(3, $intList);

        $intList->add(4);
        $this->assertCount(4, $intList);

        $stringList = new SimpleList('string', ['a', 'b', 'c', 'd']);
        $this->assertCount(4, $stringList);
    }

    public function testLitsOfObjects()
    {
        $list = new SimpleList(\stdClass::class, [
            (object)['a' => 1], (object)['a' => 2]
        ]);
        $this->assertCount(2, $list);
    }

    public function testWhere()
    {
        $list = new SimpleList('integer', range(0, 10));

        $where1 = $list->where(fn($x) => $x > 6);

        $this->assertCount(4, $where1);

        $where2 = $list->where(fn($x) => $x > 3 && $x < 7);
        $this->assertCount(3, $where2);

        $where3 = $where1->where(fn($x) => $x < 7);
        $this->assertCount(0, $where3);

        $where4 = $where1->where(fn($x) => $x < 8);
        $this->assertCount(1, $where4);
    }

    public function testSelect()
    {
        $list = new SimpleList('integer', range(0, 10));
        $select1 = $list->select(fn($x) => 'a' . $x );
        $this->assertEquals('string', $select1->typeOf);

        $list = new SimpleList('string', ['1a']);
        $select1 = $list->select(fn($x) => (int) $x);
        $this->assertEquals('integer', $select1->typeOf);

        $empty = new SimpleList('integer');
        $select2 = $empty->select(fn($x) => 'a' . $x );
        $this->assertEquals('string', $select2->typeOf);

        $empty1 = new SimpleList('string');
        $select3 = $empty1->select(fn($x) => (int) $x );
        $this->assertEquals('integer', $select3->typeOf);


        $a = new \stdClass();
        $a->a = 1;
        $b = new \stdClass();
        $b->a = 2;

        $objects = new SimpleList(\stdClass::class, [$a, $b]);
        $select4 = $objects->select(fn($x) => $x->a);
        $this->assertEquals('integer', $select4->typeOf);


        $c = new \stdClass();
        $c->a = 'a';
        $d = new \stdClass();
        $d->a = 's';
        $objects1 = new SimpleList(\stdClass::class, [$c, $d]);
        $select5 = $objects1->select(fn($x) => $x->a);
        $this->assertEquals('string', $select5->typeOf);
    }

    /**
     * @dataProvider firstOrDefaultProvider
     */
    public function testFirstOrDefault($data, $excepted, $default)
    {
        $list = new SimpleList('integer', $data);
        $value = $list->firstOrDefault($default);
        $this->assertEquals($excepted, $value);
    }

    private function firstOrDefaultProvider()
    {
        return [
            [range(7, 10), 7, 12],
            [[], null, null],
            [[], 9, 9]
        ];
    }

    public function testFirstOrDefaultByObjects()
    {
        $a = new \stdClass();
        $a->a = 1;

        $b = new \stdClass();
        $b->a = 2;

        $list = new SimpleList(\stdClass::class, []);

        $value1 = $list->firstOrDefault();
        $this->assertEquals(null, $value1);

        $list->add($a);
        $value2 = $list->firstOrDefault();
        $this->assertEquals($a->a, $value2->a);
    }
}