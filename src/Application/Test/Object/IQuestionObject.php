<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Object;

use Fluxlabs\Assessment\Tools\Domain\Objects\IAsqObject;

/**
 * Interface IQuestionObject
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IQuestionObject extends IAsqObject
{
    /**
     * Things that need to be displayed in the table header
     *
     * @return string
     */
    public function getOverallDisplay() : string;

    /**
     * Returns true if object has overall display
     *
     * @return bool
     */
    public function hasOverallDisplay() : bool;
}