<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Section\Model;

use srag\CQRS\Aggregate\AbstractAggregateRepository;
use srag\CQRS\Aggregate\AbstractAggregateRoot;
use srag\CQRS\Event\DomainEvents;
use srag\CQRS\Event\EventStore;
use srag\asq\Test\Domain\Section\Persistence\AssessmentSectionEventStore;

/**
 * Class AssessmentSectionRepository
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentSectionRepository extends AbstractAggregateRepository
{
    private EventStore $event_store;

    public function __construct()
    {
        parent::__construct();
        $this->event_store = new AssessmentSectionEventStore();
    }

    protected function getEventStore() : EventStore
    {
        return $this->event_store;
    }

    protected function reconstituteAggregate(DomainEvents $event_history) : AbstractAggregateRoot
    {
        return AssessmentSection::reconstitute($event_history);
    }
}
