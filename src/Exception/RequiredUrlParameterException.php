<?php

namespace Cubes\Media\Exception;

/**
 * Class RequiredUrlParameterException
 *
 * @package Cubes\Media\Exception
 */
class RequiredUrlParameterException extends \Exception
{
    /**
     * \Exception not required params.
     *
     * @var int
     */
    protected $code = 0;
    protected $previous = null;
    protected $message = 'Parameter %parameter is missing for method %methodName.';

    /**
     * InvalidUrlParameterException constructor.
     *
     * @param  $parameter
     * @param  $methodName
     * @throws \Exception
     */
    public function __construct($parameter, $methodName)
    {
        throw new \Exception(
            sprintf($this->message, $parameter, $methodName),
            $this->code, $this->previous
        );
    }
}