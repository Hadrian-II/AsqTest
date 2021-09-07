<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Module;

use Fluxlabs\Assessment\Tools\Domain\Modules\IAsqModule;

/**
 * Interface IQuestionModule
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IAvailabilityModule extends IAsqModule
{
    public function isAvailable() : bool;
}