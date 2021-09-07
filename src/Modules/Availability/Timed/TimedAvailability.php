<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Availability\Timed;

use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Test\Application\Test\Module\IAvailabilityModule;

/**
 * Class TimedAvailability
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TimedAvailability extends AbstractAsqModule implements IAvailabilityModule
{
    public function getConfigClass() : ?string
    {
        return TimedAvailabilityConfigurationFactory::class;
    }

    public function isAvailable(): bool
    {

    }
}