<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Model;

use srag\CQRS\Aggregate\AbstractAggregateRepository;
use srag\CQRS\Aggregate\AbstractAggregateRoot;
use srag\CQRS\Event\DomainEvents;
use srag\CQRS\Event\EventStore;
use srag\asq\Test\Domain\Test\Persistence\AssessmentTestEventStore;

/**
 * Class AssessmentTestRepository
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentTestRepository extends AbstractAggregateRepository
{
    private EventStore $event_store;

    public function __construct()
    {
        parent::__construct();
        $this->event_store = new AssessmentTestEventStore();
    }

    protected function getEventStore() : EventStore
    {
        return $this->event_store;
    }

    protected function reconstituteAggregate(DomainEvents $event_history) : AbstractAggregateRoot
    {
        return AssessmentTest::reconstitute($event_history);
    }
}
