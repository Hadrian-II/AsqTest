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
     * Gets the key of the source object this selection is based on
     *
     * @return string
     */
    public function getSourceKey() : string;

    /**
     * @return Uuid[]
     */
    public function getSelectedQuestionIds() : array;
}