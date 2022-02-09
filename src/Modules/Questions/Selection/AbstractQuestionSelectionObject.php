<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection;

use Fluxlabs\Assessment\Test\Domain\Result\Model\QuestionDefinition;
use Fluxlabs\Assessment\Test\Modules\Questions\AbstractQuestionObject;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISelectionObject;
/**
 * Abstract Class AbstractQuestionSelectionObject
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
abstract class AbstractQuestionSelectionObject extends AbstractQuestionObject implements ISelectionObject
{
    /**
     * Default returns all questions selected in question page for run
     *
     * @param QuestionDefinition[] $questions
     * @return QuestionDefinition[]
     */
    public function selectQuestionsForRun(array $questions): array
    {
        return $questions;
    }
}