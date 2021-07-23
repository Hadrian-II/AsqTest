<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Result\Model;

use srag\CQRS\Aggregate\AbstractAggregateRepository;
use srag\CQRS\Aggregate\AbstractAggregateRoot;
use srag\CQRS\Event\DomainEvents;
use srag\CQRS\Event\EventStore;
use srag\asq\Test\Domain\Result\Persistence\AssessmentResultEventStore;

/**
 * Class AssessmentResultRepository
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentResultRepository extends AbstractAggregateRepository
{
    /**
     * @var EventStore
     */
    private $event_store;

    /**
     * QuestionRepository constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->event_store = new AssessmentResultEventStore();
    }

    /**
     * @return EventStore
     */
    protected function getEventStore() : EventStore
    {
        return $this->event_store;
    }
    /**
     * @param DomainEvents $event_history
     *
     * @return AbstractAggregateRoot
     */
    protected function reconstituteAggregate(DomainEvents $event_history) : AbstractAggregateRoot
    {
        return AssessmentResult::reconstitute($event_history);
    }
}
