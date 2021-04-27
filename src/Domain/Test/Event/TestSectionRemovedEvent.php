<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Event;

use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use srag\CQRS\Event\AbstractDomainEvent;
use ILIAS\Data\UUID\Factory;

/**
 * Class TestSectionRemovedEvent
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class TestSectionRemovedEvent extends AbstractDomainEvent
{
    /**
     * @var Uuid
     */
    protected $section_id;

    /**
     * @param Uuid $aggregate_id
     * @param ilDateTime $occured_on
     * @param int $initiating_user_id
     * @param Uuid $data
     */
    public function __construct(
        Uuid $aggregate_id,
        ilDateTime $occured_on,
        int $initiating_user_id,
        Uuid $section_id = null
        ) {
            $this->section_id = $section_id;
            parent::__construct($aggregate_id, $occured_on, $initiating_user_id);
    }

    /**
     * @return Uuid
     */
    public function getSectionId() : Uuid
    {
        return $this->section_id;
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::getEventBody()
     */
    public function getEventBody() : string
    {
        return json_encode($this->section_id->toString());
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::restoreEventBody()
     */
    protected function restoreEventBody(string $event_body) : void
    {
        $factory = new Factory();
        $this->section_id = $factory->fromString($event_body);
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
