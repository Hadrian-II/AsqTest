<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Result\Model;

use ReflectionClass;

/**
 * Class SessionStatus
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SessionStatus
{
    const FINAL = "final";
    const INITIAL = "initial";
    const PENDING_RESPONSE_PROCESSING = "pendingResponseProcessing";
    const PENDING_SUBMISSION = "pendingSubmission";

    public static function isValid(string $value) : bool
    {
        $class = new ReflectionClass(__CLASS__);
        return in_array($value, $class->getConstants());
    }
}
