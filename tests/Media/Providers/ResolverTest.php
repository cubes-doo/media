<?php

namespace Cubes\Media\Providers;

use Cubes\Media\Exception\InvalidUrlException;
use Cubes\Media\Exception\UnknownUrlException;

class ResolverTest extends \PHPUnit_Framework_TestCase
{
    protected $url;

    public function setUp()
    {
        $this->url = 'https://www.youtube.com/watch?v=uncVbYGOJ8c';
    }

    public function tearDown(){}

    public function testResolverResolveThrowsInvalidUrlException()
    {
        $this->expectException(InvalidUrlException::class);
        $resolver = new Resolver();
        $resolver->resolve('test' . $this->url);
    }

    public function testResolverResolveThrowsProviderClassNotFoundException()
    {
        // TODO
    }

    public function testResolverResolveReturnsClassName()
    {
        $this->assertTrue(
            class_exists((new Resolver())->resolve($this->url))
        );
    }

    public function testResolverGetClassReturnsClassName()
    {
        $reflectedResolver = new \ReflectionClass(Resolver::class);
        $getClassMethod = $reflectedResolver->getMethod('getClass');
        $getClassMethod->setAccessible(true);
        $this->assertTrue(
            class_exists($getClassMethod->invokeArgs((new Resolver()), [
                $this->url
            ]))
        );
    }

    public function testResolverGetClassThrowsUnknownUrlException()
    {
        $this->expectException(UnknownUrlException::class);
        $reflectedResolver = new \ReflectionClass(Resolver::class);
        $getClassMethod = $reflectedResolver->getMethod('getClass');
        $getClassMethod->setAccessible(true);
        $this->assertTrue(
            class_exists($getClassMethod->invokeArgs((new Resolver()), [
                'bad-url' . $this->url
            ]))
        );
    }

    public function testResolverGetProvidersNameSpaceReturnsString()
    {
        $reflectedResolver = new \ReflectionClass(Resolver::class);
        $getProvidersNamespaceMethod = $reflectedResolver->getMethod('getProvidersNamespace');
        $getProvidersNamespaceMethod->setAccessible(true);
        $this->assertTrue(is_string(
           $getProvidersNamespaceMethod->invokeArgs((new Resolver()), [])
        ));
    }
}