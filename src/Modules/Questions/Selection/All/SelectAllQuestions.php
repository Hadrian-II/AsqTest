<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection\All;

use srag\asq\Domain\QuestionDto;
use srag\asq\Test\Domain\Test\Modules\IQuestionSelectionModule;
use srag\asq\Test\Domain\Test\Objects\ISelectionObject;
use srag\asq\Test\Lib\Event\Standard\ForwardToCommandEvent;
use srag\asq\Test\Lib\Event\Standard\StoreObjectEvent;
use srag\asq\Test\Modules\Questions\Page\QuestionPage;
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
    const CMD_INITIALIZE = 'initAllQuestions';

    /**
     * @param ?SelectAllQuestionsConfiguration $config
     * @return SelectAllQuestionsObject
     */
    public function createObject(AbstractValueObject $config = null) : SelectAllQuestionsObject
    {
        return new SelectAllQuestionsObject($this->access->getObject($config->getSourceKey()));
    }

    public function initAllQuestions() : void
    {
        $source_object = $this->readSource();

        $selection = new SelectAllQuestionsObject($source_object);

        $this->storeAndReturn($selection);
    }

    public function renderQuestionListItem(QuestionDto $question): string
    {
        return sprintf(
            '<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td></td>',
            $question->getData()->getTitle(),
            $question->getRevisionId() !== null ? $question->getRevisionId()->getName() : 'Unrevised',
            $question->getType()->getTitleKey(),
            $question->isComplete() ? $this->asq->answer()->getMaxScore($question) : 'Incomplete'
        );
    }

    public function getCommands(): array
    {
        return [
            self::CMD_INITIALIZE
        ];
    }

    public function getInitializationCommand(): string
    {
        return self::CMD_INITIALIZE;
    }
}