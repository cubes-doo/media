<?php

namespace Cubes\Media\Providers;

/**
 * Interface ProviderInterface
 *
 * @package Cubes\Media\Providers
 */
interface ResolverInterface
{
    /**
     * @return string
     */
    public function resolve($url, array $config);
}