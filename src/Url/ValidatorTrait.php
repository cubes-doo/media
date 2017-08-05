<?php

namespace Cubes\Media\Url;

use Cubes\Media\Factory;

/**
 * Class ValidatorTrait
 *
 * @package Cubes\Media\Url
 */
trait ValidatorTrait
{

    /**
     * Method isUrlValid checks if provided url is valid address.
     *
     * @param  $url
     * @return bool
     */
    public function isUrlValid($url)
    {
        $url   = (string) $url;
        $regex = Factory::URL_RGX;

        if (preg_match("/^$regex$/i", $url)) {
            return true;
        }

        return false;
    }

}