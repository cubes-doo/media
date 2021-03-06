<?php

namespace Cubes\Media;

use Cubes\Media\Exception\InvalidUrlException;
use Cubes\Media\Exception\ProviderClassNotFoundException;
use Cubes\Media\Providers\ProviderInterface;
use Cubes\Media\Providers\Resolver;
use Cubes\Media\Providers\ResolverInterface;

/**
 * Class Factory
 *
 * @package Cubes\Media
 */
class Factory implements Constants
{
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
     * @return ProviderInterface
     *
     * @throws InvalidUrlException
     * @throws ProviderClassNotFoundException
     * @throws \Exception
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