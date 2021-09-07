<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Test\Persistence;

use srag\CQRS\Event\EventStore;

/**
 * Class AssessmentTestEventStore
 *
 * @package Fluxlabs\Assessment\Test
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
