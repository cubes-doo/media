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
class Factory implements FactoryInterface
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
            // If class is FQN string and is not empty we will check if
            // class implements required ProviderInterface.
            // If case is false we will throw \Exception and break iteration.
            $resolvedClass = (new $resolver())->resolve($url);
            if (is_string($resolvedClass) && !empty($resolvedClass)) {
                if (!in_array(ProviderInterface::class,
                    array_keys(class_implements($resolvedClass))))
                {
                    throw new \Exception(
                        'Class: ' .$resolvedClass. ' is found but it does not implement ' . ProviderInterface::class);
                }

                // Everything is fine we can instantiate new Provider object,
                // pass required data to the same and break Resolver search iteration.
                return new $resolvedClass($url, $config);
                break;
            }
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
    public function registerResolver($resolverClass)
    {
        $this->resolvers[] = $resolverClass;
        return $this;
    }
}