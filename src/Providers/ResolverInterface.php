<?php

namespace Cubes\Media\Providers;

/**
 * Interface ProviderInterface
 *
 * @method resolve
 *
 * @package Cubes\Media\Providers
 */
interface ResolverInterface
{
    /*
     * @return string
     */
    public function resolve($url, array $config);
}