<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player\TextualInOut;

use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;

/**
 * Class TextualInOut
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TextualInOut extends AbstractAsqModule
{
    public function getConfigClass() : ?string
    {
        return TextualInOutConfigurationFactory::class;
    }
}