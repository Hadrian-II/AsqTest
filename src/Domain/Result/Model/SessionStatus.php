<?php

namespace srag\asq\Test\Domain\Result\Model;

use ReflectionClass;

/**
 * Class SessionStatus
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class SessionStatus
{
    const FINAL = "final";
    const INITIAL = "initial";
    const PENDING_RESPONSE_PROCESSING = "pendingResponseProcessing";
    const PENDING_SUBMISSION = "pendingSubmission";
    
    /**
     * @param string $value
     * @return bool
     */
    public static function isValid(string $value) : bool
    {
        $class = new ReflectionClass(__CLASS__);
        return in_array($value, $class->getConstants());
    }
}
