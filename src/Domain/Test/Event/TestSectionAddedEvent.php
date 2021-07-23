<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Event;

use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use srag\CQRS\Event\AbstractDomainEvent;
use ILIAS\Data\UUID\Factory;

/**
 * Class TestSectionAddedEvent
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TestSectionAddedEvent extends AbstractDomainEvent
{
    protected ?Uuid $section_id;

    public function __construct(
        Uuid $aggregate_id,
        ilDateTime $occured_on,
        int $initiating_user_id,
        Uuid $section_id = null
        ) {
            $this->section_id = $section_id;
            parent::__construct($aggregate_id, $occured_on, $initiating_user_id);
    }

    public function getSectionId() : ?Uuid
    {
        return $this->section_id;
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::getEventBody()
     */
    public function getEventBody() : string
    {
        return $this->section_id->toString();
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
