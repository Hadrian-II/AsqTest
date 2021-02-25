<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Persistence;

use srag\CQRS\Event\EventStore;

/**
 * Class AssessmentTestEventStore
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentTestEventStore extends EventStore
{
    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\EventStore::getEventArClass()
     */
    protected function getEventArClass() : string
    {
        return AssessmentTestEventStoreAr::class;
    }
}
