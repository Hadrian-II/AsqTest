<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Scoring\Automatic;

use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Test\Application\Test\Module\IScoringModule;

/**
 * Class AutomaticScoring
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AutomaticScoring extends AbstractAsqModule implements IScoringModule
{
    public function getConfigClass() : ?string
    {
        return AutomaticScoringConfigurationFactory::class;
    }
}