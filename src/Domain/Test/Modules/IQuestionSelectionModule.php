<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Modules;

use srag\asq\Domain\QuestionDto;
use srag\asq\Test\Domain\Test\Objects\ISelectionObject;
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
     * Const for source key Query Param
     */
    const PARAM_SOURCE_KEY = 'sectionUuid';

    /**
     * Gets the command that is executed to create a new QuestionSource
     *
     * @return string
     */
    public function getInitializationCommand() : string;

    /**
     * Renders a question to display on QuestionPage
     *
     * @param QuestionDto $question
     * @return string
     */
    public function renderQuestionListItem(QuestionDto $question) : string;
}