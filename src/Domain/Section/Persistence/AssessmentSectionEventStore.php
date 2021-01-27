<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Section\Persistence;

use srag\CQRS\Event\EventStore;

/**
 * Class AssessmentSectionEventStore
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentSectionEventStore extends EventStore
{
    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\EventStore::getEventArClass()
     */
    protected function getEventArClass() : string
    {
        return AssessmentSectionEventStoreAr::class;
    }
}
