<?php

namespace Cubes\Media\Tests;

use Cubes\Media\AbstractMedia;

class AbstractMediaTest extends \PHPUnit_Framework_TestCase
{
    /* @var $media \Cubes\Media\AbstractMedia|\PHPUnit_Framework_MockObject_MockObject */
    protected $media;

    public function setUp()
    {
        $media = $this->getMockBuilder(AbstractMedia::class)
            ->getMockForAbstractClass();
        self::getMethod('setConfig')->invokeArgs($media, [[
            'youtube' => [
                'api_key' => 'AIzaSyCh8O9l9QJJ62D2RfKaUUHycAebXn8_-us'
            ]
        ]]);
        self::getProperty('data')->setValue($media, [
            'id' => '',
            'thumbnail' => '',
            'thumbnailSize' => '',
            'authorName' => '',
            'authorChannelUrl' => '',
            'title' => '',
            'description' => '',
            'iframe' => '',
            'iframeSize' => '',
            'tags'  => ''
        ]);

        $this->media = $media;
    }

    public function tearDown(){}

    public function testConfigIsSet()
    {
        $this->assertNotEmpty(
            self::getMethod('getConfig')->invokeArgs($this->media, [])
        );
    }

    public function testToJsonReturnsJson()
    {
        $this->assertTrue(self::getMethod('isJson')->invokeArgs($this->media, [
            $this->media->toJson()
        ]));
    }

    public function testToArrayReturnsArray()
    {
        $this->assertTrue(is_array(
            self::getMethod('toArray')->invokeArgs($this->media, [
                $this->media->toJson()
            ])
        ));
    }

    public function testJsonSerializableInterfaceMethodMakesSerializationInGivenFormat()
    {
        $result = json_encode($this->media);
        $this->assertArrayHasKey('data', json_decode($result, true));
    }

    public function testIsObjectCountable()
    {
        $result = count($this->media);
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