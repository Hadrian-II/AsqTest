<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Result\Event;

use ilDateTime;
use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Event\AbstractDomainEvent;

/**
 * Class AssessmentResultSubmittedEvent
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentResultSubmittedEvent extends AbstractDomainEvent
{
    public function __construct(Uuid $aggregate_id, ilDateTime $occurred_on, int $initiating_user_id)
    {
        parent::__construct($aggregate_id, $occurred_on, $initiating_user_id);
    }

    public function getEventBody() : string
    {
        //No Event body
        return '';
    }

    protected function restoreEventBody(string $event_body) : void
    {
        //No Event body
    }

    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
