<?php

namespace Cubes\Media\Tests\Stubs\Url;

use Cubes\Media\Url\IdentifierTrait;

class IdentifierStub
{
    use IdentifierTrait;

    const PARENT_CALL_IS_TRIGGERED = 'parent __call is triggered';

    public function __call($name, $arguments)
    {
        return self::PARENT_CALL_IS_TRIGGERED;
    }
}