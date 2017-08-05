<?php

namespace Cubes\Media\Tests;


use Cubes\Media\Exception\UnknownUrlException;
use Cubes\Media\Tests\Stubs\Url\IdentifierStub;
use Cubes\Media\Url\IdentifierTrait;

class IdentifierTraitTest extends \PHPUnit_Framework_TestCase
{
    public function setUp(){}
    public function tearDown(){}

    public function testMagicMethod__CallCallsParentMethodIfExist()
    {
        $identifierMock = new IdentifierStub();

        $this->assertEquals(
            IdentifierStub::PARENT_CALL_IS_TRIGGERED,
            $identifierMock->undefinedMethod()
        );
    }

    public function testIsUrlTypeOfYoutubeReturnsTrue()
    {
        /** @var $identifierMock \Cubes\Media\Url\IdentifierTrait */
        $identifierMock = $this
            ->getMockBuilder(IdentifierTrait::class)
            ->getMockForTrait()
        ;

        $result = $identifierMock->isYoutube('https://www.youtube.com/watch?v=uncVbYGOJ8c');
        $this->assertTrue($result);
    }

    public function testIdentifyMethodThrowsException()
    {
        $this->expectException(UnknownUrlException::class);

        /** @var $identifierMock \Cubes\Media\Url\IdentifierTrait */
        $identifierMock = $this
            ->getMockBuilder(IdentifierTrait::class)
            ->getMockForTrait()
        ;

        $result = $identifierMock->isYoutube('https://www.yodutube.com/watch?v=uncVbYGOJ8c');
        $this->assertTrue($result);
    }

    public function testIsUrlTypeOfVimeoReturnsTrue()
    {
        /** @var $identifierMock \Cubes\Media\Url\IdentifierTrait */
        $identifierMock = $this
            ->getMockBuilder(IdentifierTrait::class)
            ->getMockForTrait()
        ;

        $result = $identifierMock->isVimeo('https://vimeo.com/226379658');
        $this->assertTrue($result);
    }
}