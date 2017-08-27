<?php

namespace Cubes\Media;

use Cubes\Media\Serializers\Arrayable;
use Cubes\Media\Serializers\Jsonable;
use Cubes\Media\Providers\ProviderInterface;

/**
 * Class AbstractMedia
 *
 * Base class extended by all providers.
 *
 * @package Cubes\Media
 */
abstract class AbstractMedia implements \JsonSerializable, \ArrayAccess, \Countable,
    Arrayable,
    Jsonable,
    ProviderInterface
{
    /**
     * @var array $media
     *
     */
    protected $data = [
        'id' => '',
        'thumbnail' => '',
        'thumbnailSize' => '',
        'authorName' => '',
        'authorChannelUrl' => '',
        'title' => '',
        'description' => '',
        'iframe' => '',
        'iframeSize' => '',
        'tags'  => ''
    ];

    /**
     * Provider service to be injected on init.
     *
     * @var array
     */
    protected $service;

    /**
     * Array of provider API access parameters.
     *
     * @var \Cubes\Media\Config $config
     */
    protected $config = [];

    /**
     * Init function.
     *
     * @return mixed
     */
    abstract protected function init();

    /**
     * Method used to bind config parameters to config property.
     *
     * @param  array $parameters
     * @return $this
     */
    protected function setConfig(array $parameters)
    {
        $this->config = new Config($parameters);
        return $this;
    }

    /**
     * Returns array of config parameters.
     *
     * @return \Cubes\Media\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Sets iframe size so you can then call getIframe with dynamic width and height.
     *
     * @param  array|string $size
     * @return string
     */
    public function setIframeSize($size)
    {
        return 'Not implemented yet.';
    }

    /**
     * Returns author name.
     *
     * @return string
     */
    public function getAuthorName()
    {
        return $this->data['authorName'];
    }

    /**
     * Returns author channel url.
     *
     * @return string
     */
    public function getAuthorChannelUrl()
    {
        return $this->data['authorChannelUrl'];
    }

    /**
     * Returns thumbnail url.
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->data['thumbnail'];
    }

    /**
     * Returns size of thumbnail from data.
     *
     * @return string
     */
    public function getThumbnailSize()
    {
        return $this->data['thumbnailSize'];
    }

    /**
     * Returns title of video.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->data['title'];
    }

    /**
     * Returns description of video.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->data['description'];
    }
    /**
     * Returns iframe html data.
     *
     * @return string
     */
    public function getIframe()
    {
        return $this->data['iframe'];
    }

    /**
     * Returns array of video tags.
     *
     * @return mixed
     */
    public function getTags()
    {
        return $this->data['tags'];
    }

    /**
     * Method getVideoId used to fetch video id from parsed url.
     *
     * @return mixed
     */
    public function getVideoId()
    {
        return $this->video_id;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->data, JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param  string $jsonData
     * @return mixed
     */
    public function toArray($jsonData = null)
    {
        if (!empty($jsonData)) {

            // If provided data is json return array.
            if ($this->isJson($jsonData)) {
                return json_decode($jsonData, true);
            }

            // If provided data is array return the same.
            if (is_array($jsonData)) {
                return $jsonData;
            }
        }

        // Inspect data and return array format.
        return ($this->isJson($this->data)) ?
            json_decode($this->data, true)
            : $this->data;
    }

    /**
     * JsonSerializable implemented method.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'data' => $this->data
        ];
    }

    /**
     * Method isJson checks if given string is type of Json.
     *
     * @param  $string
     * @return boolean
     */
    protected function isJson($string)
    {
        return !empty($string)
            && is_string($string)
            && is_array(json_decode($string, true))
            && json_last_error() == 0;
    }

    /**
     * Count method implemented from \Countable() interface.
     *
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @param  mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * @param  string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (isset($this->data[$offset])) {
            return $this->__get($offset);
        }
    }

    /**
     * @param  mixed $offset
     * @param  mixed $value
     *
     * @throws \Exception
     */
    public function offsetSet($offset, $value)
    {
        if (empty($value)) {
            throw new \Exception(
                'You must provide valid value for class attribute: ' . $offset . ' current value is empty.'
            );
        }

        $this->__set($offset, $value);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if (isset($this->data[$offset])) {
            unset($this->data[$offset]);
        }
    }

    /**
     * Magic method __set().
     *
     * @param  $name
     * @param  $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        return $this->data[$name] = $value;
    }

    /**
     * Magic method __get().
     *
     * @param  $name
     * @return mixed
     */
    public function __get($name)
    {
        $name = ucwords($name);
        if (in_array(lcfirst($name), array_keys($this->data))) {
            $method = 'get'.$name;
            if (method_exists($this, $method)) {
                return $this->$method();
            }
        }
    }

    /**
     * Magic method __unset().
     *
     * @param  $name
     * @return mixed
     */
    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    /**
     * Magic method __isset().
     *
     * @param  $name
     * @return mixed
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * Magic method __toString().
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->data, JSON_UNESCAPED_SLASHES);
    }
}