<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Result\Persistence;

use srag\CQRS\Event\EventStore;

/**
 * Class AssessmentResultEventStore
 *
 * @package Fluxlabs\Assessment\Test
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
