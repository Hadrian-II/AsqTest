<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Objects;


use ILIAS\Data\UUID\Uuid;

/**
 * Interface ISourceObject
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
eITestObjectIIT{
    /**
     * @return Uuid[]
     */
    public function getQuestionIds() : array;
}