<?php
declare(strict_types = 1);

namespace srag\asq\Test\Lib\Event;

/**
 * Interface IEventQueue
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IEventQueue
{
    function addUser(IEventUser $user) : void;

    function raiseEvent(Event $event) : void;
}