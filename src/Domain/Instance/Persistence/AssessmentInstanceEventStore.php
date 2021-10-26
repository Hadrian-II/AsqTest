<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Instance\Persistence;

use Fluxlabs\CQRS\Event\EventStore;

/**
 * Class AssessmentInstanceEventStore
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentInstanceEventStore extends EventStore
{
    protected function getEventArClass() : string
    {
        return AssessmentInstanceEventStoreAr::class;
    }
}
