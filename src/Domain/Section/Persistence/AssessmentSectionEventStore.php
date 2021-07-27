<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Section\Persistence;

use srag\CQRS\Event\EventStore;

/**
 * Class AssessmentSectionEventStore
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentSectionEventStore extends EventStore
{
    protected function getEventArClass() : string
    {
        return AssessmentSectionEventStoreAr::class;
    }
}
