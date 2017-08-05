<?php

namespace Cubes\Media\Tests;

use Cubes\Media\AbstractMedia;

class AbstractMediaTest extends \PHPUnit_Framework_TestCase
{
    public function setUp(){}
    public function tearDown(){}

    public function testConfigIsSet()
    {
        /* @var $media \Cubes\Media\AbstractMedia|\PHPUnit_Framework_MockObject_MockObject */
        $media = $this
            ->getMockBuilder(AbstractMedia::class)
            ->getMockForAbstractClass();

        self::getMethod('setConfig')->invokeArgs($media, [['api_key' => 'random']]);
        $this->assertNotEmpty(
            self::getMethod('getConfig')->invokeArgs($media, [])
        );
    }

    public function testToJsonReturnsJson()
    {
        /* @var $media \Cubes\Media\AbstractMedia|\PHPUnit_Framework_MockObject_MockObject */
        $media = $this
            ->getMockBuilder(AbstractMedia::class)
            ->getMockForAbstractClass();

        $this->assertTrue(self::getMethod('isJson')->invokeArgs($media, [
            $media->toJson()
        ]));
    }

    public function testToArrayReturnsArray()
    {
        /* @var $media \Cubes\Media\AbstractMedia|\PHPUnit_Framework_MockObject_MockObject */
        $media = $this
            ->getMockBuilder(AbstractMedia::class)
            ->getMockForAbstractClass();

        $this->assertTrue(is_array(
            self::getMethod('toArray')->invokeArgs($media, [
                $media->toJson()
            ])
        ));
    }

    public function testJsonSerializableInterfaceMethodMakesSerializationInGivenFormat()
    {
        /* @var $media \Cubes\Media\AbstractMedia|\PHPUnit_Framework_MockObject_MockObject */
        $media = $this
            ->getMockBuilder(AbstractMedia::class)
            ->getMockForAbstractClass();

        $result = json_encode($media);
        $this->assertArrayHasKey('data', json_decode($result, true));
    }

    public function testIsObjectCountable()
    {
        /* @var $media \Cubes\Media\AbstractMedia|\PHPUnit_Framework_MockObject_MockObject */
        $media = $this
            ->getMockBuilder(AbstractMedia::class)
            ->getMockForAbstractClass();

        $result = count($media);
        $this->assertTrue($result > 1);
    }

    protected static function getMethod($name)
    {
        $class = new \ReflectionClass(AbstractMedia::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected static function getProperty($name)
    {
        $class = new \ReflectionClass(AbstractMedia::class);
        $property = $class->getProperty($name);
        $property->setAccessible(true);
        return $property;
    }
}