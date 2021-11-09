<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Availability\Basic;

use Fluxlabs\Assessment\Test\Application\Test\Module\IAvailabilityModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use srag\asq\UserInterface\Web\Form\Factory\AbstractObjectFactory;

/**
 * Class BasicAvailability
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class BasicAvailability extends AbstractAsqModule implements IAvailabilityModule
{
    public function getConfigFactory() : ?AbstractObjectFactory
    {
        global $DIC, $ASQDIC;

        return new BasicAvailabilityConfigurationFactory($DIC->language(), $DIC->ui(), $ASQDIC->asq()->ui());
    }

    public function isAvailable(): bool
    {

    }
}