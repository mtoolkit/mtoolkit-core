<?php

namespace mtoolkit\core\exception;

class MOutOfBoundsException extends \OutOfBoundsException
{
    /**
     * MOutOfBoundsException constructor.
     * @param int $min
     * @param int $max
     * @param int $required
     */
    public function __construct($min, $max, $required)
    {
        parent::__construct(printf("The required item as position %s is not between %s and %s", $required, $min, $max));
    }
}