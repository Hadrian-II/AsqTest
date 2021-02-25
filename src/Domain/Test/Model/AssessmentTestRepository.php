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
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentTestRepository extends AbstractAggregateRepository
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
        $this->event_store = new AssessmentTestEventStore();
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
        return AssessmentTest::reconstitute($event_history);
    }
}
