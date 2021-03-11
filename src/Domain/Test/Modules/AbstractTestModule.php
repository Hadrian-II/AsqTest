<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Modules;

use ILIAS\Data\Result;

/**
 * Abstract class AbstractTestModule
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
abstract class AbstractTestModule implements  ITestModule
{
    /**
     * {@inheritDoc}
     * @see ITestModule::getConfigClass()
     */
    public function getConfigClass() : ?string
    {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Modules\ITestModule::processEvent()
     */
    public function processEvent(object $event): Result
    {

    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Modules\ITestModule::raiseEvent()
     */
    public function raiseEvent(): object
    {

    }
}