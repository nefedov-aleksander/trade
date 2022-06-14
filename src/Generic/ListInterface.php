<?php

namespace Trade\Api\Generic;


interface ListInterface
{
    public function add($item): self;
    public function where(\Closure $compare) : self;
    public function select(\Closure $creator) : self;
    public function firstOrDefault($default = null);
    public function toArray(): array;
}