<?php

namespace srag\asq\Test\Domain\Section\Event;

use ilDateTime;
use srag\CQRS\Event\AbstractDomainEvent;
use srag\asq\Test\Domain\Section\Model\AssessmentSectionData;

/**
 * Class AssessmentSectionDataSetEvent
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentSectionDataSetEvent extends AbstractDomainEvent
{
    /**
    * @var AssessmentSectionData
    */
    protected $section_data;

    /**
     * @param string $aggregate_id
     * @param ilDateTime $occured_on
     * @param int $initiating_user_id
     * @param AssessmentSectionData $data
     */
    public function __construct(
        string $aggregate_id,
        ilDateTime $occured_on,
        int $initiating_user_id,
        AssessmentSectionData $data = null
    ) {
        $this->section_data = $data;
        parent::__construct($aggregate_id, $occured_on, $initiating_user_id);
    }

    /**
     * @return AssessmentSectionData
     */
    public function getSectionData() : AssessmentSectionData
    {
        return $this->section_data;
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::getEventBody()
     */
    public function getEventBody() : string
    {
        return json_encode($this->section_data);
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::restoreEventBody()
     */
    protected function restoreEventBody(string $event_body) : void
    {
        $this->section_data = AssessmentSectionData::deserialize($event_body);
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
