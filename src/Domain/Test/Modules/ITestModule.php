<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Modules;

use ILIAS\Data\Result;

/**
 * Interface TestModule
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
interface ITestModule
{
    const TYPE_AVAILABILITY = 'availability';
    const TYPE_PLAYER = 'player';
    const TYPE_QUESTION_SOURCE = 'source';
    const TYPE_QUESTION_SELECTION = 'selection';
    const TYPE_SCORING = 'scoring';
    const TYPE_RESULT = 'result';

    /**
     * Return the type of a test module
     */
    public function getType() : string;

    /**
     * Return the class holding the configuration
     * Null means no configuration is needed
     *
     * @return string|NULL
     */
    public function getConfigClass() : ?string;

    /**
     * @param object $event
     * @return Result
     */
    public function processEvent(object $event) : Result;

    /**
     * Raise an event that is triggered from that module
     *
     * @return object
     */
    public function raiseEvent() : object;
}