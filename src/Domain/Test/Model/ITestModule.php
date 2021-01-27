<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Model;

/**
 * Interface TestModule
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
interface ITestModule
{
    const TYPE_AVAILABILITY = 1;
    const TYPE_PLAYER = 2;
    const TYPE_QUESTION_SOURCE = 3;
    const TYPE_QUESTION_SELECTION = 4;
    const TYPE_SCORING = 5;
    const TYPE_RESULT = 6;

    /**
     * Return the type of a test module
     */
    public function getType() : int;

    /**
     * Return the class holding the configuration
     * Null means no configuration is needed
     *
     * @return string|NULL
     */
    public function getConfigClass() : ?string;

    /**
     * Return a text key if it should display settings in a subtab in the TestSettings
     * If no key is returned, default is used
     *
     * @return string
     */
    public function getConfigType() : ?string;
}