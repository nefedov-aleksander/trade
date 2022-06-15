<?php


namespace Trade\Api\Config;


interface SettingValueInterface
{
    public function getOrThrow($key);
    public function getOrDefault($key, $default = null);
}