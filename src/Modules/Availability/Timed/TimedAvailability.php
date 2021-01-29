<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Availability\Timed;

use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\IAvailabilityModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;

/**
 * Class TimedAvailability
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class TimedAvailability extends AbstractTestModule implements IAvailabilityModule
{
    /**
     * {@inheritDoc}
     * @see ITestModule::getType()
     */
    public function getType(): int
    {
        return ITestModule::TYPE_AVAILABILITY;
    }

    /**
     * {@inheritDoc}
     * @see ITestModule::getConfigClass()
     */
    public function getConfigClass() : ?string
    {
        return TimedAvailabilityConfiguration::class;
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {

    }
}