<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Modules;

use srag\asq\Application\Exception\AsqException;
use srag\asq\Test\Lib\Event\Event;
use srag\asq\Test\Lib\Event\IEventQueue;

/**
 * Abstract class AbstractTestModule
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
abstract class AbstractTestModule implements  ITestModule
{
    private IEventQueue $event_queue;

    public function __construct(IEventQueue $event_queue)
    {
        $this->event_queue = $event_queue;
    }

    /**
     * {@inheritDoc}
     * @see ITestModule::getConfigClass()
     */
    public function getConfigClass() : ?string
    {
        return null;
    }

    public function getCommands() : array
    {
        return [];
    }

    public function processEvent(object $event): void
    {

    }

    public function raiseEvent(Event $event) : void
    {
        $this->event_queue->raiseEvent($event);
    }

    private function checkAccess(string $command) : bool
    {
        return true;
    }

    public function executeCommand(string $command): string
    {
        if (!in_array($command, $this->getCommands())) {
            throw new AsqException(
                sprintf(
                    'module: "%s" cannot execute command: "%s"',
                    get_class($this),
                    $command
                )
            );
        }

        if (!$this->checkAccess($command)) {
            throw new AsqException(
                sprintf(
                    'user not allowed to execute command: "%s" on module: "%s"',
                    $command,
                    get_class($this)
                )
            );
        }

        return $this->{$command}();
    }
}