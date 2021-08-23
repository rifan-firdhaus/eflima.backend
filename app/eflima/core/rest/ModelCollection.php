<?php namespace eflima\core\rest;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use ArrayAccess;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class ModelCollection implements ArrayAccess
{
    /** @var array */
    public $models = [];

    /** @var int */
    public $count = 0;

    /**
     * ModelCollection constructor.
     *
     * @param array $models
     */
    public function __construct($models)
    {
        $this->models = $models;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return isset($this->models[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->models[$offset];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        if (empty($offset)) {
            $offset = $this->count++;
        }

        $this->models[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        unset($this->models[$offset]);
    }
}
