<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Persistence;

use srag\CQRS\Event\EventStore;

/**
 * Class AssessmentTestEventStore
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentTestEventStore extends EventStore
{
    protected function getEventArClass() : string
    {
        return AssessmentTestEventStoreAr::class;
    }
}
