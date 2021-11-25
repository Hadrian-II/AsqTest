<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player;

use Fluxlabs\Assessment\Test\Modules\Player\Page\TestOverview\OverviewState;

/**
 * Class IOverviewProvider
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IOverviewProvider
{
    /**
     * Gets the State of the Current Test
     *
     * @return OverviewState[]
     */
    public function getOverview() : array;
}