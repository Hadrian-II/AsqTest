<?php

namespace srag\asq\Test\Domain\Result\Persistence;

use srag\CQRS\Event\AbstractStoredEvent;

/**
 * Class AssessmentResultEventStoreAr
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentResultEventStoreAr extends AbstractStoredEvent {
    const STORAGE_NAME = "asq_result_event_store";
    
    /**
     * @return string
     */
    public static function returnDbTableName() {
        return self::STORAGE_NAME;
    }
}