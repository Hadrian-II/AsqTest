<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Result\Event;

use srag\CQRS\Event\AbstractDomainEvent;

/**
 * Class ScoringFinishedEvent
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class ScoringFinishedEvent extends AbstractDomainEvent
{
    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::getEventBody()
     */
    public function getEventBody() : string
    {
        //No Event body
        return '';
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::restoreEventBody()
     */
    protected function restoreEventBody(string $event_body) : void
    {
        //No Event body
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
