<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Section\Persistence;

use srag\CQRS\Event\AbstractStoredEvent;

/**
 * Class AssessmentSectionEventStoreAr
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentSectionEventStoreAr extends AbstractStoredEvent
{
    const STORAGE_NAME = "asq_section_es";

    public static function returnDbTableName() : string
    {
        return self::STORAGE_NAME;
    }
}
