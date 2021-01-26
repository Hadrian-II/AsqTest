<?php

namespace srag\asq\Test\Modules\Availability\Basic;

use srag\asq\Test\Domain\Test\Model\AbstractTestModule;
use srag\asq\Test\Domain\Test\Model\ITestModule;
/**
 * Class BasicAvailability
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class BasicAvailability extends AbstractTestModule
{
    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Model\ITestModule::getType()
     */
    public function getType(): int
    {
        return ITestModule::TYPE_AVAILABILITY;
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Model\ITestModule::getConfigClass()
     */
    public function getConfigClass() : ?string
    {
        return BasicAvailabilityConfiguration::class;
    }
}