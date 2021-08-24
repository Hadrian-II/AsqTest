<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Objects;

use ILIAS\Data\UUID\Uuid;

/**
 * Interface ISelectionObject
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface ISelectionObject extends ITestObject
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