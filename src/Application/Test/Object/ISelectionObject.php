<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Object;

use Fluxlabs\Assessment\Test\Domain\Result\Model\QuestionDefinition;

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
     * Gets the source of the selection
     *
     * @return ISourceObject
     */
    public function getSource() : ISourceObject;

    /**
     * Performs selection of questions for run (ex. random selection for random selection module)
     *
     * @param QuestionDefinition[] $questions
     * @return QuestionDefinition[]
     */
    public function selectQuestionsForRun(array $questions) : array;
}