<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Persistence;

use srag\CQRS\Event\AbstractStoredEvent;

/**
 * Class AssessmentTestEventStoreAr
 *
 * @package srag\asq\Test
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
