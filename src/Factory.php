<?php

namespace Cubes\Media;

use Cubes\Media\Exception\InvalidUrlException;
use Cubes\Media\Exception\ProviderClassNotFoundException;
use Cubes\Media\Providers\ProviderInterface;
use Cubes\Media\Providers\Resolver;

/**
 * Class Factory
 *
 * @package Cubes\Media
 */
class Factory
{
    /**
     * Constant YOUTUBE_IDENTIFIERS used as identifier for code logic.
     */
    const YOUTUBE_IDENTIFIERS = ['youtube', 'youtu.be', 'y2u.be'];
    /**
     * Constant TYPE_YOUTUBE used as identifier for code logic.
     */
    const TYPE_YOUTUBE = 'youtube';

    /**
     * Constant TYPE_VIMEO used as identifier for code logic.
     */
    const TYPE_VIMEO   = 'vimeo';

    /**
     * Constant YOUTUBE_RGX used as youtube allowed regex pattern for youtube url.
     */
    const YOUTUBE_RGX  = '~^(?:https?://)?(?:www[.])?(?:youtube[.]com/watch[?]v=|youtu[.]be/)([^&]{11})~x';

    /**
     * Constant VIMEO_RGX used as vimeo allowed regex pattern for youtube url.
     */
    const VIMEO_RGX    = '/https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/'.
                         ']*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/';

    /**
     * Constant URL_RGX used as vimeo allowed regex pattern for youtube url.
     */
    const URL_RGX      = '((https?|ftp)\:\/\/)?([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?'.
                         '([a-z0-9-.]*)\.([a-z]{2,3})(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$'.
                         '_.-][a-z0-9;:@&%=+\/\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?';
    
    /**
     * Resolver property
     *
     * @var null
     */
    protected $resolvers = [
        Resolver::class
    ];
    
    /**
     * @var null
     */
    protected static $instance = null;

    /**
     * Media __constructor locked following Singleton pattern.
     */
    protected function __construct()
    {}

    /**
     * Media magic method __clone locked following Singleton pattern.
     */
    protected function __clone()
    {}

    /**
     * Method getInstance used for retrieving single object instance.
     *
     * @return \Cubes\Media\Factory
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * @param  string $url
     * @param  array  $config
     *
     * @throws InvalidUrlException
     * @throws ProviderClassNotFoundException
     * @throws \Exception
     *
     * @return ProviderInterface
     */
    public function create($url, array $config)
    {
        // Iterate through all resolvers in reverse order
        // and try to resolve Provider class.
        foreach ($this->getResolvers() as $resolver) {

            $resolver = new $resolver();

            $resolvedClass = $resolver->resolve($url, $config);
            return $resolvedClass;
        }
    }

    /**
     * Method getResolvers returns array of registered resolvers.
     *
     * @return null
     */
    public function getResolvers()
    {
        return array_reverse($this->resolvers);
    }

    /**
     * Method registerResolver used to register new resolver.
     *
     * @param $resolverClass
     * @return \Cubes\Media\Factory
     */
    public function registerResolver(ResolverInterface $resolverClass)
    {
        $this->resolvers[] = $resolverClass;
        return $this;
    }

    /**
     * Method setResolver used to empty resolvers property and set passed resolver as the only one.
     *
     * @param $resolverClass
     * @return \Cubes\Media\Factory
     */
    public function setResolver(ResolverInterface $resolverClass)
    {
        unset($this->resolvers);
        $this->resolvers[] = $resolverClass;
        return $this;
    }
}