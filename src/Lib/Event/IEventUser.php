<?php
declare(strict_types = 1);

namespace srag\asq\Test\Lib\Event;

/**
 * Interface IEventUser
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IEventUser
{
    function processEvent(Event $event) : void;
}