<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection\Random;

use srag\asq\Domain\QuestionDto;
use Fluxlabs\Assessment\Test\Application\Test\Module\IQuestionSelectionModule;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISelectionObject;
use Fluxlabs\Assessment\Test\Lib\Event\Standard\ForwardToCommandEvent;
use Fluxlabs\Assessment\Test\Lib\Event\Standard\StoreObjectEvent;
use Fluxlabs\Assessment\Test\Modules\Questions\Page\QuestionPage;
use Fluxlabs\Assessment\Test\Modules\Questions\Selection\AbstractQuestionSelection;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;

/**
 * Class RandomQuestionSelection
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RandomQuestionSelection extends AbstractQuestionSelection
{
    const CMD_INITIALIZE = 'initAllQuestions';

    /**
     * @param ?RandomQuestionSelectionConfiguration $config
     * @return RandomQuestionSelectionObject
     */
    public function createObject(AbstractValueObject $config = null) : RandomQuestionSelectionObject
    {
        return new RandomQuestionSelectionObject($this->access->getObject($config->getSourceKey()));
    }

    public function initAllQuestions() : void
    {
        $source_object = $this->readSource();

        $selection = new RandomQuestionSelectionObject($source_object);

        $this->storeAndReturn($selection);
    }

    public function renderQuestionListItem(ISelectionObject $object, QuestionDto $question): string
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