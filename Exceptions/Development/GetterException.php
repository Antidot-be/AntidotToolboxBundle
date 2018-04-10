<?php

namespace Antidot\ToolboxBundle\Exceptions\Development;

use Antidot\ToolboxBundle\Exceptions\DevelopmentException;
use Throwable;

class GetterException extends DevelopmentException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = sprintf(
            'There is not %s to treat. Maybe you forgot to call create() or a setter before?',
            $message
        );
        parent::__construct($message, $code, $previous);
    }
}
