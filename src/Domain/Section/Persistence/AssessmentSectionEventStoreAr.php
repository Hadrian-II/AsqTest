<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Section\Persistence;

use srag\CQRS\Event\AbstractStoredEvent;

/**
 * Class AssessmentSectionEventStoreAr
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentSectionEventStoreAr extends AbstractStoredEvent
{
    const STORAGE_NAME = "asq_section_es";

    /**
     * @return string
     */
    public static function returnDbTableName()
    {
        return self::STORAGE_NAME;
    }
}
