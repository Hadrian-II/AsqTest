<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Availability\Basic;

use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\IAvailabilityModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;

/**
 * Class BasicAvailability
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class BasicAvailability extends AbstractTestModule implements IAvailabilityModule
{
    /**
     * {@inheritDoc}
     * @see ITestModule::getType()
     */
    public function getType(): string
    {
        return ITestModule::TYPE_AVAILABILITY;
    }

    /**
     * {@inheritDoc}
     * @see ITestModule::getConfigClass()
     */
    public function getConfigClass() : ?string
    {
        return BasicAvailabilityConfigurationFactory::class;
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Modules\IAvailabilityModule::isAvailable()
     */
    public function isAvailable(): bool
    {

    }
}