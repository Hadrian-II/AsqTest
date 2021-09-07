<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Module;

use Fluxlabs\Assessment\Tools\Domain\Modules\IAsqModule;
use srag\asq\Domain\QuestionDto;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISelectionObject;

/**
 * Interface IQuestionSelectionModule
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IQuestionSelectionModule extends IAsqModule
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
    public function renderQuestionListItem(ISelectionObject $object, QuestionDto $question) : string;

    /**
     * Gets Actions that can be performed on the question Page
     *
     * @param ISelectionObject $object
     * @return string
     */
    public function getQuestionPageActions(ISelectionObject $object) : string;
}