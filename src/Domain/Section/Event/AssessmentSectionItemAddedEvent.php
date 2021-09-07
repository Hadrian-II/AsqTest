<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Section\Event;

use ilDateTime;
use srag\CQRS\Event\AbstractDomainEvent;
use Fluxlabs\Assessment\Test\Domain\Section\Model\SectionPart;
use ILIAS\Data\UUID\Uuid;

/**
 * Class AssessmentSectionItemAddedEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentSectionItemAddedEvent extends AbstractDomainEvent
{
    protected ?SectionPart $item;

    public function __construct(
        Uuid $aggregate_id,
        ilDateTime $occured_on,
        int $initiating_user_id,
        SectionPart $item = null
    ) {
        $this->item = $item;
        parent::__construct($aggregate_id, $occured_on, $initiating_user_id);
    }

    public function getItem() : SectionPart
    {
        return $this->item;
    }

    public function getEventBody() : string
    {
        return json_encode($this->item);
    }

    protected function restoreEventBody(string $event_body) : void
    {
        $this->item = SectionPart::deserialize($event_body);
    }

    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
