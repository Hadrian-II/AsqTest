<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Instance\Model;

use Fluxlabs\Assessment\Test\Domain\Instance\Persistence\AssessmentInstanceEventStore;
use Fluxlabs\CQRS\Aggregate\AbstractAggregateRepository;
use Fluxlabs\CQRS\Aggregate\AbstractAggregateRoot;
use Fluxlabs\CQRS\Event\DomainEvents;
use Fluxlabs\CQRS\Event\EventStore;
use Fluxlabs\Assessment\Test\Domain\Result\Persistence\AssessmentResultEventStore;

/**
 * Class AssessmentInstanceRepository
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentInstanceRepository extends AbstractAggregateRepository
{
    private EventStore $event_store;

    public function __construct()
    {
        parent::__construct();
        $this->event_store = new AssessmentInstanceEventStore();
    }

    protected function getEventStore() : EventStore
    {
        return $this->event_store;
    }

    protected function reconstituteAggregate(DomainEvents $event_history) : AbstractAggregateRoot
    {
        return AssessmentInstance::reconstitute($event_history);
    }
}
