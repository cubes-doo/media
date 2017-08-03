<?php

namespace Cubes\Media\Tests;

use Cubes\Media\Factory;
use Cubes\Media\FactoryInterface;
use Cubes\Media\Providers\ProviderInterface;
use Cubes\Media\Tests\Stub\ResolverStub;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Cubes\Media\Factory
     */
    private $factory;

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

    public function testFactoryCreateThrowsExceptionWhenResolverNotImplementedRequiredInterface()
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
        $resolverClass = 'Random\\Class\\Name';
        $factory = $this->factory->registerResolver($resolverClass);

        $this->assertTrue(in_array($resolverClass, $factory->getResolvers()));
    }
}
