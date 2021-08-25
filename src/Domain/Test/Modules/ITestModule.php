<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Modules;

use srag\asq\Test\Domain\Test\Objects\ISelectionObject;
use srag\asq\Test\Domain\Test\Objects\ITestObject;
use srag\asq\Test\Domain\Test\Objects\ObjectConfiguration;
use srag\asq\Test\Lib\Event\Event;
use srag\asq\Test\Lib\Event\IEventUser;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Interface TestModule
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface ITestModule extends IEventUser
{
    const TYPE_AVAILABILITY = 'availability';
    const TYPE_PLAYER = 'player';
    const TYPE_QUESTION_SOURCE = 'source';
    const TYPE_QUESTION_SELECTION = 'selection';
    const TYPE_SCORING = 'scoring';
    const TYPE_RESULT = 'result';
    const TYPE_PAGE = 'page';
    const TYPE_STORAGE = 'storage';

    /**
     * Return the type of a test module
     */
    public function getType() : string;

    /**
     * Return the class holding the configuration
     * Null means no configuration is needed
     *
     * @return ?string
     */
    public function getConfigClass() : ?string;

    /**
     * returns all commands provided by the module
     *
     * @return string[]
     */
    public function getCommands() : array;

    /**
     * executes a command in the module
     *
     * @param string $command
     */
    public function executeCommand(string $command): void;

    /**
     * Raises an event through the Test event queue
     *
     * @param Event $event
     */
    public function raiseEvent(Event $event) : void;

    /**
     * @param ObjectConfiguration $config
     * @return ITestObject
     */
    public function createObject(ObjectConfiguration $config) : ITestObject;
}