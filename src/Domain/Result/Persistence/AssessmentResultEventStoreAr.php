<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Result\Persistence;

use Fluxlabs\CQRS\Event\AbstractStoredEvent;

/**
 * Class AssessmentResultEventStoreAr
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentResultEventStoreAr extends AbstractStoredEvent
{
    const STORAGE_NAME = "asq_result_event_store";

    public static function returnDbTableName() : string
    {
        return self::STORAGE_NAME;
    }
}
