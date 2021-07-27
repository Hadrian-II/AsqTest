<?php
declare(strict_types=1);

namespace srag\asq\Statistics;

use Exception;
use srag\CQRS\Projection\ProjectionEventHandler;
use srag\CQRS\Projection\Persistence\ilDBPositionLedger;
use srag\CQRS\Projection\ValueObjects\ProjectorPosition;
use srag\asq\Test\Domain\Result\Event\ScoreSetEvent;
use srag\asq\Test\Domain\Result\Model\AssessmentResult;
use srag\asq\Test\Domain\Result\Model\AssessmentResultRepository;
use srag\asq\Test\Domain\Result\Persistence\AssessmentResultEventStore;

/**
 * Class StatisticsProjector
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class StatisticsProjector
{
    protected ilDBPositionLedger $position_ledger;

    protected  AssessmentResultEventStore$event_store;

    public function __construct()
    {
        $this->position_ledger = new ilDBPositionLedger();
        $this->event_store = new AssessmentResultEventStore();
    }

    public function project() : void
    {
        global $DIC;

        $position = $this->position_ledger->fetch($this) ?: ProjectorPosition::makeNewUnplayed($this);
        $event_handler = new ProjectionEventHandler();

        $DIC->database()->beginTransaction();
        try {
            foreach ($this->event_store->getEventStream($position->last_position)->getEvents() as $event) {
                $event_handler->handle($event, $this);
                $position = $position->played($event);
            }
        } catch (Exception $e) {
            $DIC->database()->rollback();
        }

        $DIC->database()->commit();

        $this->position_ledger->store($position);
    }

    protected function whenScoreSetEvent(ScoreSetEvent $event)
    {
        global $ASQDIC;

        /** @var $result AssessmentResult */
        $result = AssessmentResultRepository::getInstance()->getAggregateRootById($event->getAggregateId());

        $ASQDIC->asq()->statistics()->registerScore(
            $event->getQuestionId(),
            '',
            $result->getContext()->getAssessmentName(),
            $event->getInitiatingUserId(),
            $event->getScore(),
            $event->getOccurredOn()
        );
    }
}
