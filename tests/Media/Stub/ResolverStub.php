<?php

namespace Cubes\Media\Tests\Stub;

class ResolverStub
{
    public function resolve($url)
    {
        return ProviderStub::class;
    }
}