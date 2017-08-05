<?php

namespace Cubes\Media\Serializers;

/**
 * Interface Arrayable
 *
 * @package Cubes\Media\Serializers
 */
interface Arrayable
{
    /**
     * @return mixed
     */
    public function toArray();
}