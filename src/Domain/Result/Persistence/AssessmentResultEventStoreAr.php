<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Result\Persistence;

use srag\CQRS\Event\AbstractStoredEvent;

/**
 * Class AssessmentResultEventStoreAr
 *
 * @package srag\asq\Test
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
