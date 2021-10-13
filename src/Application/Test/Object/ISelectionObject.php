<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Object;

use ILIAS\Data\UUID\Uuid;

/**
 * Interface ISelectionObject
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface ISelectionObject extends IQuestionObject
{
    /**
     * @return Uuid[]
     */
    public function getSelectedQuestionIds() : array;

    /**
     * Gets the source of the selection
     *
     * @return ISourceObject
     */
    public function getSource() : ISourceObject;
}