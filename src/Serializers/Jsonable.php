<?php

namespace Cubes\Media\Serializers;

/**
 * Interface Jsonable
 *
 * @package Cubes\Media\Serializers
 */
interface Jsonable
{
    /**
     * @return mixed
     */
    public function toJson();
}