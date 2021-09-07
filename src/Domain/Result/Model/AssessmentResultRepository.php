<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Result\Model;

use srag\CQRS\Aggregate\AbstractAggregateRepository;
use srag\CQRS\Aggregate\AbstractAggregateRoot;
use srag\CQRS\Event\DomainEvents;
use srag\CQRS\Event\EventStore;
use Fluxlabs\Assessment\Test\Domain\Result\Persistence\AssessmentResultEventStore;

/**
 * Class AssessmentResultRepository
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentResultRepository extends AbstractAggregateRepository
{
    private EventStore $event_store;

    public function __construct()
    {
        parent::__construct();
        $this->event_store = new AssessmentResultEventStore();
    }

    protected function getEventStore() : EventStore
    {
        return $this->event_store;
    }

    protected function reconstituteAggregate(DomainEvents $event_history) : AbstractAggregateRoot
    {
        return AssessmentResult::reconstitute($event_history);
    }
}
