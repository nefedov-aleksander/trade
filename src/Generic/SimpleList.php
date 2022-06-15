<?php


namespace Trade\Api\Generic;

class SimpleList implements ListInterface, \Countable
{
    public readonly string $typeOf;
    private array $items = [];

    public function __construct(string $typeOf, array $items = [])
    {
        $this->typeOf = $typeOf;
        foreach ($items as $item)
        {
            $this->add($item);
        }
    }

    public function add($item): ListInterface
    {
        if($this->getCanonicalType($item) != $this->typeOf)
        {
            throw new \InvalidArgumentException();
        }

        $this->items[] = $item;

        return $this;
    }

    public function where(\Closure $compare): ListInterface
    {
        $list = new self($this->typeOf);

        foreach ($this->items as $item)
        {
            if($compare($item))
            {
                $list->add($item);
            }
        }

        return $list;
    }

    public function select(\Closure $creator): ListInterface
    {
        $first = $this->firstOrDefault();
        if($first != null)
        {
            $typeOf = $this->getCanonicalType($creator($first));
        }
        else
        {
            if(in_array($this->typeOf, ['integer', 'string', 'float']))
            {
                $typeOf = gettype($creator(null));
            }
            else
            {
                $r = new ReflectionClass($this->typeOf);
                $typeOf = gettype($creator($r->newInstanceWithoutConstructor()));
            }

        }

        $list = new self($typeOf);
        foreach ($this->items as $item)
        {
            $list->add($creator($item));
        }

        return $list;
    }

    public function firstOrDefault($default = null)
    {
        return $this->count() > 0 ? $this->items[0] : $default;

    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function count() : int
    {
        return count($this->items);
    }

    protected function getCanonicalType($value)
    {
        if(gettype($value) == 'object')
        {
            return get_class($value);
        }

        return gettype($value);
    }
}