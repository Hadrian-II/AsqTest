<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions;

use Fluxlabs\Assessment\Test\Application\Test\Object\IQuestionObject;

/**
 * Class AbstractQuestionObject
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
abstract class AbstractQuestionObject implements IQuestionObject
{
    public function getOverallDisplay() : string
    {
        return '';
    }

    public function hasOverallDisplay(): bool
    {
        return false;
    }
}