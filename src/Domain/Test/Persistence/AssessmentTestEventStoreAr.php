<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Test\Persistence;

use Fluxlabs\CQRS\Event\AbstractStoredEvent;

/**
 * Class AssessmentTestEventStoreAr
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentTestEventStoreAr extends AbstractStoredEvent
{
    const STORAGE_NAME = "asq_test_es";

    public static function returnDbTableName() : string
    {
        return self::STORAGE_NAME;
    }
}
