<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Object;

use ILIAS\Data\UUID\Uuid;

/**
 * Interface ISourceObject
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface ISourceObject extends IQuestionObject
{
    /**
     * @return Uuid[]
     */
    public function getQuestionIds() : array;
}