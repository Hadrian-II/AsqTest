<?php

namespace srag\asq\Test\Domain\Section\Event;

use ilDateTime;
use srag\CQRS\Event\AbstractDomainEvent;
use srag\asq\Test\Domain\Section\Model\SectionPart;

/**
 * Class AssessmentSectionItemRemovedEvent
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentSectionItemRemovedEvent extends AbstractDomainEvent {
    /**
     * @var SectionPart
     */
    protected $item;

    /**
     * @param string $aggregate_id
     * @param ilDateTime $occured_on
     * @param int $initiating_user_id
     * @param SectionPart $item
     */
    public function __construct(
        string $aggregate_id,
        ilDateTime $occured_on,
        int $initiating_user_id,
        SectionPart $item = null)
    {
        $this->item = $item;
        parent::__construct($aggregate_id, $occured_on, $initiating_user_id);
    }

    /**
     * @return string
     */
    public function getItem() : SectionPart {
        return $this->item;
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::getEventBody()
     */
    public function getEventBody(): string
    {
        return json_encode($this->item);
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::restoreEventBody()
     */
    protected function restoreEventBody(string $event_body): void
    {
        $this->item = SectionPart::deserialize($event_body);
    }

    /**
     * @return int
     */
    public static function getEventVersion(): int
    {
        // initial version 1
        return 1;
    }
}