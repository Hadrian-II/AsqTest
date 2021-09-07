<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Availability\Basic;

use Fluxlabs\Assessment\Test\Application\Test\Module\IAvailabilityModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;

/**
 * Class BasicAvailability
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class BasicAvailability extends AbstractAsqModule implements IAvailabilityModule
{
    public function getConfigClass() : ?string
    {
        return BasicAvailabilityConfigurationFactory::class;
    }

    public function isAvailable(): bool
    {

    }
}