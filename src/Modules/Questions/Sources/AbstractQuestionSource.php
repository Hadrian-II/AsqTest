<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Sources;

use Fluxlabs\Assessment\Test\Application\Test\Module\IQuestionSourceModule;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISourceObject;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;

/**
 * Abstract Class AbstractQuestionSource
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
abstract class AbstractQuestionSource extends AbstractAsqModule implements IQuestionSourceModule
{
    public function getQuestionPageActions(ISourceObject $object): string
    {
        //no actions
        return '';
    }
}