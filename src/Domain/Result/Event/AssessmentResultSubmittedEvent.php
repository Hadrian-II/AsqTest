<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Result\Event;

use DateTimeImmutable;
use ILIAS\Data\UUID\Uuid;
use Fluxlabs\CQRS\Event\AbstractDomainEvent;

/**
 * Class AssessmentResultSubmittedEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentResultSubmittedEvent extends AbstractDomainEvent
{
    public function __construct(Uuid $aggregate_id, DateTimeImmutable $occurred_on)
    {
        parent::__construct($aggregate_id, $occurred_on);
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
