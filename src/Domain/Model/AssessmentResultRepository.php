<?php

namespace srag\asq\Test\DomainModel;

use srag\CQRS\Aggregate\AbstractEventSourcedAggregateRepository;
use srag\CQRS\Aggregate\AggregateRoot;
use srag\CQRS\Event\DomainEvents;
use srag\CQRS\Event\EventStore;
use srag\asq\Test\Persistence\AssessmentResultEventStore;

/**
 * Class AssessmentResultRepository
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentResultRepository extends AbstractEventSourcedAggregateRepository {
    /**
     * @var EventStore
     */
    private $event_store;
    
    /**
     * QuestionRepository constructor.
     */
    protected function __construct() {
        parent::__construct();
        $this->event_store = new AssessmentResultEventStore();
    }
    
    public function getResultByName(string $name, int $user_id) : AssessmentResult {
        return null;
    }
    
    /**
     * @return EventStore
     */
    protected function getEventStore(): EventStore {
        return $this->event_store;
    }
    /**
     * @param DomainEvents $event_history
     *
     * @return AggregateRoot
     */
    protected function reconstituteAggregate(DomainEvents $event_history): AggregateRoot {
        return AssessmentResult::reconstitute($event_history);
    }
}