<?php

namespace Cubes\Media\Providers;

use Cubes\Media\Exception\InvalidUrlException;
use Cubes\Media\Exception\ProviderClassNotFoundException;
use Cubes\Media\Url\IdentifierTrait;
use Cubes\Media\Url\ValidatorTrait;

/**
 * Class Resolver
 *
 * @package Cubes\Media\Providers
 */
class Resolver implements ResolverInterface
{
    use IdentifierTrait,
        ValidatorTrait;

    /**
     * Resolver resolve method used to resolve and find Provider class.
     *
     * @param $url
     *
     * @throws InvalidUrlException
     * @throws ProviderClassNotFoundException
     *
     * @return string
     */
    public function resolve($url, array $config)
    {
        if (!$this->isUrlValid($url)) {
            throw new InvalidUrlException('Provided url: ' .$url. ' is not valid.');
        }

        $class = $this->getClass($url);
        if (!class_exists($class)) {
            throw new ProviderClassNotFoundException('Provider class: ' .$class. ' not found.');
        }
        
        return new $class($url, $config);
    }

    /**
     * Method getClass used to get Vimeo or Youtube class from URL if identified.
     *
     * @param  $url
     * @return string
     */
    private function getClass($url)
    {
        return $this->getProvidersNamespace() . ucfirst($this->identify($url));
    }

    /**
     * Method getProvidersNamespace
     *
     * @return string
     */
    private function getProvidersNamespace()
    {
        return '\\' .__NAMESPACE__. '\\';
    }
}