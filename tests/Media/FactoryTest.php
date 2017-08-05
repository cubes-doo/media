<?php

namespace Cubes\Media\Tests;

use Cubes\Media\Factory;
use Cubes\Media\FactoryInterface;
use Cubes\Media\Providers\ProviderInterface;
use Cubes\Media\Tests\Stubs\ResolverStub;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Cubes\Media\Factory
     */
    private $factory;

    /**
     * TODO: Change name of property because is ugly and impractical for parameters accessing.
     *
     * @var array
     */
    private $collection = [];

    public function setUp()
    {
        $this->factory = Factory::getInstance();
        $this->collection['url'] = 'https://www.youtube.com/watch?v=uncVbYGOJ8c';
        $this->collection['config'] = [
            'api_key' => 'AIzaSyCh8O9l9QJJ62D2RfKaUUHycAebXn8_-us'
        ];
    }

    public function testFactoryImplementsFactoryInterface()
    {
        $this->assertArrayHasKey(FactoryInterface::class, class_implements(Factory::class));
    }

    public function testFactoryCreateReturnsInstanceOfProviderInterface()
    {
        $this->assertInstanceOf(ProviderInterface::class,
            $this->factory->create(
                $this->collection['url'], $this->collection['config']
            )
        );
    }

    public function testFactoryCreateThrowsExceptionWhenResolverNotImplementedRequiredMethod()
    {
        $this->expectException(\Exception::class);
        $this->factory->registerResolver(ResolverStub::class)->create(
            $this->collection['url'], $this->collection['config']
        );
    }

    public function testResolversNotEmpty()
    {
        $this->assertNotEmpty($this->factory->getResolvers());
    }

    public function testResolverIsRegistered()
    {
        $factory = $this->factory->registerResolver(ResolverStub::class);
        $this->assertTrue(in_array(ResolverStub::class, $factory->getResolvers()));
    }

    public function testResolverIsRegisteredAsTheOnlyOne()
    {
        $factory = $this->factory->setResolver(ResolverStub::class);
        $this->assertTrue(
            in_array(ResolverStub::class, $factory->getResolvers()) &&
            count($factory->getResolvers()) === 1
        );
    }
}
