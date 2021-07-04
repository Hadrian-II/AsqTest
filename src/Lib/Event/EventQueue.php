<?php
declare(strict_types = 1);

namespace srag\asq\Test\Lib\Event;

/**
 * Class EventQueue
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class EventQueue implements IEventQueue
{
    /**
     * @var IEventUser[] $users
     */
    private array $users;

    /**
     * @var Event[] $events
     */
    private array $events;

    private bool $lock;

    public function __construct() {
        $this->users = [];
        $this->events = [];
    }

    public function addUser(IEventUser $user): void
    {
        $this->users[] = $user;
    }

    public function raiseEvent(Event $event): void
    {
        $this->events[] = $event;

        if ($this->lock === false)
        {
            $this->processNextEvent();;
        }
    }

    private function processNextEvent() : void
    {
        while ($event = array_shift($this->events)) {
            foreach ($this->users as $user) {
                $user->processEvent($event);
            }
        }
    }
}