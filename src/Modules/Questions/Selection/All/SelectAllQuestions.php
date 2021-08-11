<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection\All;

use srag\asq\Domain\QuestionDto;
use srag\asq\Test\Domain\Test\Objects\ISelectionObject;
use srag\asq\Test\Modules\Questions\Selection\AbstractQuestionSelection;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class SelectAllQuestions
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SelectAllQuestions extends AbstractQuestionSelection
{
    /**
     * @param ?SelectAllQuestionsConfiguration $config
     * @return SelectAllQuestionsObject
     */
    public function createObject(AbstractValueObject $config = null) : SelectAllQuestionsObject
    {
        return new SelectAllQuestionsObject($this->access->getObject($config->getSourceKey()));
    }
}