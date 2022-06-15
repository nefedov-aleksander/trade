<?php


namespace Trade\Api\Config;


class SettingValue implements SettingValueInterface
{
    private array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function getOrThrow($key)
    {
        if(key_exists($key, $this->settings))
        {
            return $this->settings[$key];
        }
        throw new \InvalidArgumentException();
    }

    public function getOrDefault($key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }
}