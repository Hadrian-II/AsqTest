<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Section\Model;

use Fluxlabs\CQRS\Aggregate\AbstractAggregateRepository;
use Fluxlabs\CQRS\Aggregate\AbstractAggregateRoot;
use Fluxlabs\CQRS\Event\DomainEvents;
use Fluxlabs\CQRS\Event\EventStore;
use Fluxlabs\Assessment\Test\Domain\Section\Persistence\AssessmentSectionEventStore;

/**
 * Class AssessmentSectionRepository
 *
 * @package Fluxlabs\Assessment\Test
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
