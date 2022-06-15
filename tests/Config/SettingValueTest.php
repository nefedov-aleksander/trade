<?php


namespace TradeTest\Config;


use PHPUnit\Framework\TestCase;
use Trade\Api\Config\SettingValue;

final class SettingValueTest extends TestCase
{
    public function testSettingValueGetOrDefault()
    {
        $settingValue = new SettingValue([
            'a' => 'b',
            'c' => 1
        ]);

        $this->assertEquals('b', $settingValue->getOrDefault('a'));
        $this->assertEquals(1, $settingValue->getOrDefault('c', 4));
        $this->assertEquals(null, $settingValue->getOrDefault('e'));
        $this->assertEquals('q', $settingValue->getOrDefault('r', 'q'));

        $this->assertEquals('b', $settingValue->getOrThrow('a'));
    }

    public function testSettingValueGetOrThrow()
    {
        $this->expectException(\InvalidArgumentException::class);

        $settingValue = new SettingValue([]);
        $settingValue->getOrThrow('a');
    }
}