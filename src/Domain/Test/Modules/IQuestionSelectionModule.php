<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Modules;

use srag\asq\Domain\QuestionDto;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Interface IQuestionSelectionModule
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IQuestionSelectionModule extends ITestModule
{
    /**
     * @param ?AbstractValueObject $config
     * @return array
     */
    public function createSelectionObject(?AbstractValueObject $config = null) : array;
}