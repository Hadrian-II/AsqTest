<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Instance\Persistence;

use Fluxlabs\CQRS\Event\AbstractStoredEvent;

/**
 * Class AssessmentInstanceEventStoreAr
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentInstanceEventStoreAr extends AbstractStoredEvent
{
    const STORAGE_NAME = "asq_instance_event_store";

    public static function returnDbTableName() : string
    {
        return self::STORAGE_NAME;
    }
}
