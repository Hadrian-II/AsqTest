<?php

namespace srag\asq\Test\Domain\Result\Persistence;

use srag\CQRS\Event\EventStore;

/**
 * Class AssessmentResultEventStore
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentResultEventStore extends EventStore {
    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\EventStore::getEventArClass()
     */
    protected function getEventArClass(): string
    {
        return AssessmentResultEventStoreAr::class;
    }
}