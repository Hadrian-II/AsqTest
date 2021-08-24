<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Modules;

use srag\asq\Domain\QuestionDto;
use srag\asq\Test\Domain\Test\Objects\ISourceObject;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Interface IQuestionSourceModule
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IQuestionSourceModule extends ITestModule
{
    /**
     * Gets the command that is executed to create a new QuestionSource
     *
     * @return string
     */
    public function getInitializationCommand() : string;

    /**
     * Gets Actions that can be performed on the question Page
     *
     * @param ISourceObject $object
     * @return string
     */
    public function getQuestionPageActions(ISourceObject $object) : string;
}