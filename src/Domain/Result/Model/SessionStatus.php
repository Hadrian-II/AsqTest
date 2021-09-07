<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Result\Model;

use ReflectionClass;

/**
 * Class SessionStatus
 *
 * @package Fluxlabs\Assessment\Test
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
