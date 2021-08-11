<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Modules;

use srag\asq\Application\Exception\AsqException;
use srag\asq\Test\Domain\Test\ITestAccess;
use srag\asq\Test\Domain\Test\Objects\ITestObject;
use srag\asq\Test\Domain\Test\Objects\ObjectConfiguration;
use srag\asq\Test\Lib\Event\Event;
use srag\asq\Test\Lib\Event\IEventQueue;

/**
 * Abstract class AbstractTestModule
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
abstract class AbstractTestModule implements  ITestModule
{
    private IEventQueue $event_queue;

    protected ITestAccess $access;

    public function __construct(IEventQueue $event_queue, ITestAccess $access)
    {
        $this->event_queue = $event_queue;
        $this->access = $access;
    }

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
        // process no events by default
    }

    public function raiseEvent(Event $event) : void
    {
        $this->event_queue->raiseEvent($event);
    }

    private function checkAccess(string $command) : bool
    {
        return true;
    }

    public function executeCommand(string $command): void
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

        $this->{$command}();
    }

    public function createObject(ObjectConfiguration $config) : ITestObject
    {
        throw new AsqException(
            sprintf(
                'module of type "%s" cannot create objects',
                get_class($this)
            )
        );
    }
}