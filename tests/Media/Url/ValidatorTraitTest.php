<?php

namespace Cubes\Media\Tests;

use Cubes\Media\Url\ValidatorTrait;

class ValidatorTraitTest extends \PHPUnit_Framework_TestCase
{
    protected $url;

    public function setUp()
    {
        $this->url = 'http://php.net/';
    }

    public function tearDown(){}

    public function testIfUrlIsValidMethodReturnsTrue()
    {
        $this
            ->getMockBuilder(ValidatorTrait::class)
            ->setMethods(['isUrlValid'])
            ->getMockForTrait()
            ->method('isUrlValid')
            ->with($this->url)
            ->willReturn(true)
        ;
    }

    public function testIfUrlIsValidMethodReturnsFalse()
    {
        $this
            ->getMockBuilder(ValidatorTrait::class)
            ->setMethods(['isUrlValid'])
            ->getMockForTrait()
            ->method('isUrlValid')
            ->with('tt' . $this->url)
            ->willReturn(false)
        ;
    }
}