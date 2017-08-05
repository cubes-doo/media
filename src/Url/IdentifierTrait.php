<?php

namespace Cubes\Media\Url;

use Cubes\Media\Exception\NotAllowedUrlTypeException;
use Cubes\Media\Exception\RequiredUrlParameterException;
use Cubes\Media\Exception\UnknownUrlException;
use Cubes\Media\Factory;

/**
 * Class IdentifierTrait
 *
 * Identifier trait used in Factory and Providers.
 * Can be also used standalone in controllers, models...
 *
 * @method isVimeo(string $url)
 * @method isYoutube(string $url)
 * @method isUrlVimeo(string $url)
 * @method isUrlYoutube(string $url)
 * @method isYoutubeUrl(string $url)
 * @method isVimeoUrl(string $url)
 *
 * @package Cubes\Media\Url
 */
trait IdentifierTrait
{
    /**
     * Array of undefined but allowed methods to be called with help of magic method __call.
     *
     * @var array
     */
    protected static $undefinedAllowedMethods = [
        'isurltypeof',
        'isvimeo',
        'isyoutube',
        'isurlvimeo',
        'isurlyoutube',
        'isyoutubeurl',
        'isvimeourl',
    ];

    /**
     * Array of allowed url types.
     *
     * @var array
     */
    protected static $allowedUrlTypes = [
        'youtube', 'vimeo'
    ];

    /**
     * @param  $name
     * @param  $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        // If trait is used in parent class that already implements
        // magic method __call we will call parent's magic method.
        if (is_callable('parent::__call')) {
            return parent::__call($name, $args);
        }

        // If undefined method is called we will try to identify if
        // some of property assigned method is called and we will call
        // isUrlTypeOf with passed arguments.
        $name = strtolower($name);
        if (in_array($name, self::$undefinedAllowedMethods)) {
            if (strpos($name, Factory::TYPE_YOUTUBE) !== false) {
                return $this->isUrlTypeOf(Factory::TYPE_YOUTUBE, $args[0]);
            } elseif (strpos($name, Factory::TYPE_VIMEO) !== false) {
                return $this->isUrlTypeOf(Factory::TYPE_VIMEO, $args[0]);
            }
        }
    }

    /**
     * Method identify used to identify what type of url is (Youtube, Vimeo).
     *
     * @param  string $url
     *
     * @throws UnknownUrlException
     *
     * @return string
     */
    public function identify($url)
    {
        if (strpos($url, Factory::TYPE_YOUTUBE) !== false) {
            preg_match(Factory::YOUTUBE_RGX, $url, $matches);
            if (!empty($matches)) {
                return Factory::TYPE_YOUTUBE;
            }
        } elseif (strpos($url, Factory::TYPE_VIMEO) !== false) {
            preg_match(Factory::VIMEO_RGX, $url, $matches);
            if (!empty($matches)) {
                return Factory::TYPE_VIMEO;
            }
        }

        // Not identify anything throw Exception.
        throw new UnknownUrlException(
            'Provided url address: ' .$url. ' is neither type of youtube nor a type of vimeo.'
        );
    }

    /**
     * Method isUrlTypeOf used to identify if url is type of Vimeo or Youtube.
     *
     * @param  $type
     * @param  $url
     *
     * @throws RequiredUrlParameterException
     * @throws NotAllowedUrlTypeException
     *
     * @return boolean
     */
    private function isUrlTypeOf($type, $url)
    {
        // Throw exception if type parameter is empty because we can't work without the same.
        if (empty($type)) {
            throw new RequiredUrlParameterException('type', 'isUrlTypeOf');
        }

        // Throw exception it url parameter is empty or not passed because we can't work without the same.
        if (empty($url)) {
            throw new RequiredUrlParameterException('url', 'isUrlTypeOf');
        }

        // Cast url to string and lowercase it.
        // Also checking if provided type is in array of allowed types.
        $type = strtolower((string) $type);
        if (!in_array($type, self::$allowedUrlTypes)) {
            throw new NotAllowedUrlTypeException(
                'Provided type: ' .$type. ' is not in list of allowed url types. ' .
                'Allowed url types are: ' . implode(' and ', self::$allowedUrlTypes)
            );
        }

        // Check if passed type is same to what identify method returns.
        if ($type == $this->identify($url)) {
            return true;
        }

        // Return false otherwise.
        return false;
    }
}