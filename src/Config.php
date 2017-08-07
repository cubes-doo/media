<?php

namespace Cubes\Media;

/**
 * Class Config
 *
 * @package Cubes\Media
 */
class Config implements \ArrayAccess, \Countable
{
    /**
     * Array of config parameters.
     *
     * @var array $parameters
     */
    protected $parameters;

    /**
     * Number of elements in configuration data
     *
     * @var integer
     */
    protected $count;

    /**
     * Config constructor.
     *
     * @param array $parameters
     */
    public function __construct($parameters = [])
    {
        $this->parameters = [];
        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                $this->parameters[$key] = new self($value);
            } else {
                $this->parameters[$key] = $value;
            }
        }
        $this->count = count($this->parameters);
    }

    /**
     * @param  $name
     * @return mixed
     */
    public function get($name)
    {
        $result = null;
        if (array_key_exists($name, $this->parameters)) {
            $result = $this->parameters[$name];
        }
        return $result;
    }

    /**
     * @param  $name
     * @param  $value
     * @return mixed
     */
    public function set($name, $value)
    {
        if (is_array($name)) {
            $this->parameters[$name] = new self($name);
        } else {
            $this->parameters[$name] = $value;
        }
        $this->count = count($this->parameters);
    }

    /**
     * Return an associative array of the stored data.
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];
        $parameters = $this->parameters;
        foreach ($parameters as $key => $value) {
            if ($value instanceof Config) {
                $array[$key] = $value->toArray();
            } else {
                $array[$key] = $value;
            }
        }
        return $array;
    }

    /**
     * Defined by Countable interface
     *
     * @return int
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetExists($offset)
    {
        if (!empty($this->get($offset))) {
            return true;
        }
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return mixed
     */
    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetUnset($offset)
    {
        unset($this->parameters[$offset]);
    }

    /**
     * @param  $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }
}