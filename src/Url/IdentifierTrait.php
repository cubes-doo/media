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
 * Can be also used on in controllers, models...
 *
 * @package Cubes\Media\Url
 */
trait IdentifierTrait
{
    /**
     * Array of allowed URL types.
     * Youtube has two types of acknowledged shortened URLs.
     * Vimeo URLs are already pretty short, hence the  do not need shortening.
     * @var array
     */
    protected static $allowedUrlTypes = [
        'vimeo',
        'youtube',
        'youtu.be',
        'y2u.be'
    ];
    
    /**
     * Method used to identify if a given URL came from Youtube.
     * 
     * @param string $url
     * @return boolean
     */
    public function isYoutube($url) 
    {
        return $this->isUrlTypeOf(Factory::TYPE_YOUTUBE, $url);
    }
    
    /**
     * Method used to identify if a given URL came from Vimeo.
     * 
     * @param string $url
     * @return boolean
     */
    public function isVimeo($url) 
    {
        return $this->isUrlTypeOf(Factory::TYPE_VIMEO, $url);
    }
    
    /**
     * Method identify used to identify the type of URL (Youtube or Vimeo currently).
     *
     * @param  string $url
     *
     * @throws UnknownUrlException
     *
     * @return string
     */
    public function identify($url)
    {
        if ($this->strpos_array($url, Factory::YOUTUBE_IDENTIFIERS) !== false) {
            preg_match(Factory::YOUTUBE_RGX, $url, $matches);
            if (!empty($matches)) {
                return Factory::TYPE_YOUTUBE;
            }
        } elseif ($this->strpos_array($url, Factory::TYPE_VIMEO) !== false) {
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
        // Throw exception if type parameter is empty because we can't work without it.
        if (empty($type)) {
            throw new RequiredUrlParameterException('type', 'isUrlTypeOf');
        }

        // Throw exception it url parameter is empty or not passed because we can't work without it.
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

    /**
     *  Method used when there is more than one possibility to check against,
     *  i.e. when needles are in an array.
     *
     * @param  string  $haystack
     * @param  mixed   $needles
     * @return boolean
     */
    private function strposArray($haystack, $needles)
    {
        if ( is_array($needles) ) {
            foreach ($needles as $str) {
                if ( is_array($str) ) {
                    $pos = $this->strposArray($haystack, $str);
                } else {
                    $pos = strpos($haystack, $str);
                }
                if ($pos !== false) {
                    return $pos;
                }
            }
        } else {
            return strpos($haystack, $needles);
        }
    }
}