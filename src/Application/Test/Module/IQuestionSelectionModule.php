<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Module;

use srag\asq\Domain\QuestionDto;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISelectionObject;

/**
 * Interface IQuestionSelectionModule
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IQuestionSelectionModule extends IQuestionModule
{
    /**
     * Const for source key Query Param
     */
    const PARAM_SOURCE_KEY = 'sectionUuid';

    /**
     * Renders a question to display on QuestionPage
     *
     * @param QuestionDto $question
     * @return string
     */
    public function renderQuestionListItem(ISelectionObject $object, QuestionDto $question) : string;
}