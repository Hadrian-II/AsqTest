<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Result\Persistence;

use srag\CQRS\Event\EventStore;

/**
 * Class AssessmentResultEventStore
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentResultEventStore extends EventStore
{
    protected function getEventArClass() : string
    {
        return AssessmentResultEventStoreAr::class;
    }
}
