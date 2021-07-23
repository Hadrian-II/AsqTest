<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Section\Event;

use ilDateTime;
use srag\CQRS\Event\AbstractDomainEvent;
use srag\asq\Test\Domain\Section\Model\SectionPart;
use ILIAS\Data\UUID\Uuid;

/**
 * Class AssessmentSectionItemRemovedEvent
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentSectionItemRemovedEvent extends AbstractDomainEvent
{
    /**
     * @var SectionPart
     */
    protected $item;

    /**
     * @param Uuid $aggregate_id
     * @param ilDateTime $occured_on
     * @param int $initiating_user_id
     * @param SectionPart $item
     */
    public function __construct(
        Uuid $aggregate_id,
        ilDateTime $occured_on,
        int $initiating_user_id,
        SectionPart $item = null
    ) {
        $this->item = $item;
        parent::__construct($aggregate_id, $occured_on, $initiating_user_id);
    }

    /**
     * @return string
     */
    public function getItem() : SectionPart
    {
        return $this->item;
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::getEventBody()
     */
    public function getEventBody() : string
    {
        return json_encode($this->item);
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::restoreEventBody()
     */
    protected function restoreEventBody(string $event_body) : void
    {
        $this->item = SectionPart::deserialize($event_body);
    }

    /**
     * @return int
     */
    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
