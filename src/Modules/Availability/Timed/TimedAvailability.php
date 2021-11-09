<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Availability\Timed;

use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Test\Application\Test\Module\IAvailabilityModule;
use srag\asq\UserInterface\Web\Form\Factory\AbstractObjectFactory;
use function ILIAS\UI\examples\Symbol\Glyph\Language\language;

/**
 * Class TimedAvailability
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TimedAvailability extends AbstractAsqModule implements IAvailabilityModule
{
    public function getConfigFactory() : ?AbstractObjectFactory
    {
        global $DIC, $ASQDIC;

        return new TimedAvailabilityConfigurationFactory($DIC->language(), $DIC->ui(), $ASQDIC->asq()->ui());
    }

    public function isAvailable(): bool
    {

    }
}