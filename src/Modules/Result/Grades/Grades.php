<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Result\Grades;

use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Test\Application\Test\Module\IResultModule;

/**
 * Class Grades
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class Grades extends AbstractAsqModule implements IResultModule
{
    public function getConfigClass() : ?string
    {
        return GradesConfigurationFactory::class;
    }
}