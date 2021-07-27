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
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class BasicAvailability extends AbstractTestModule implements IAvailabilityModule
{
    public function getType(): string
    {
        return ITestModule::TYPE_AVAILABILITY;
    }

    public function getConfigClass() : ?string
    {
        return BasicAvailabilityConfigurationFactory::class;
    }

    public function isAvailable(): bool
    {

    }
}