<?php

declare (strict_types=1);

namespace EasyTree\Adapter\Handler;

use EasyTree\Adapter\AbstractAdapter;

class ArrayAdapter extends AbstractAdapter
{
    private $source;

    public function __construct(array $source)
    {
        $this->source = $source;
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->source);
//        return isset($this->source[$offset]);
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return $this->source[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->source[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->source[$offset]);
    }

    public function toArray(): array
    {
        return $this->source;
    }
}