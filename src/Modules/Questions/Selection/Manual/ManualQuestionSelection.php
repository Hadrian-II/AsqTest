<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection\Manual;

use ilCtrl;
use ILIAS\DI\UIServices;
use srag\asq\Domain\QuestionDto;
use srag\asq\Test\Domain\Test\ITestAccess;
use srag\asq\Test\Domain\Test\Modules\IQuestionSelectionModule;
use srag\asq\Test\Domain\Test\Objects\ISelectionObject;
use srag\asq\Test\Domain\Test\Objects\ISourceObject;
use srag\asq\Test\Lib\Event\IEventQueue;
use srag\asq\Test\Lib\Event\Standard\ForwardToCommandEvent;
use srag\asq\Test\Lib\Event\Standard\StoreObjectEvent;
use srag\asq\Test\Modules\Questions\Page\QuestionPage;
use srag\asq\Test\Modules\Questions\Selection\AbstractQuestionSelection;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class ManualQuestionSelection
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class ManualQuestionSelection extends AbstractQuestionSelection
{
    const CMD_INITIALIZE = 'initManualQuestions';
    const CMD_SAVE_SELECTION = 'saveManualSelection';

    const PARAM_SELECTION_KEY = 'selectionKey';

    private UIServices $ui;

    private ilCtrl $ctrl;

    public function __construct(IEventQueue $event_queue, ITestAccess $access)
    {
        global $DIC;
        $this->ui = $DIC->ui();
        $this->ctrl = $DIC->ctrl();

        parent::__construct($event_queue, $access);
    }

    /**
     * @param ?ManualQuestionSelectionConfiguration $config
     * @return ManualQuestionSelectionObject
     */
    public function createObject(AbstractValueObject $config = null) : ManualQuestionSelectionObject
    {
        return new ManualQuestionSelectionObject($this->access->getObject($config->getSourceKey()), $config->getSelectedQuestions());
    }

    public function initManualQuestions() : void
    {
        $source_object = $this->readSource();

        $selection = new ManualQuestionSelectionObject($source_object, []);

        $this->storeAndReturn($selection);
    }

    public function saveManualSelection() : void
    {
        $selection_key = $this->http->request()->getQueryParams()[self::PARAM_SELECTION_KEY];
        /** @var ISelectionObject $selection */
        $selection = $this->access->getObject($selection_key);
        /** @var ISourceObject $source */
        $source = $this->access->getObject($selection->getSource()->getKey());

        $selected_questions = [];

        foreach ($source->getQuestionIds() as $question_id) {
            if (in_array($question_id->toString(), array_keys($this->http->request()->getParsedBody()))) {
                $selected_questions[] = $question_id;
            }
        }

        $selection = new ManualQuestionSelectionObject($source, $selected_questions);

        $this->raiseEvent(new StoreObjectEvent(
            $this,
            $selection
        ));

        $this->raiseEvent(new ForwardToCommandEvent(
            $this,
            QuestionPage::SHOW_QUESTIONS
        ));
    }

    public function renderQuestionListItem(ISelectionObject $object, QuestionDto $question) : string
    {
        return sprintf(
            '<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>',
            $question->getData()->getTitle(),
            $question->getRevisionId() !== null ? $question->getRevisionId()->getName() : 'Unrevised',
            $question->getType()->getTitleKey(),
            $question->isComplete() ? $this->asq->answer()->getMaxScore($question) : 'Incomplete',
            $this->renderSelectionCheckbox($object, $question)
        );
    }

    private function renderSelectionCheckbox(ISelectionObject $object, QuestionDto $question) : string
    {
        return sprintf(
            '<input type="checkbox" name="%s" %s/>',
            $question->getId(),
            in_array($question->getId(), $object->getSelectedQuestionIds()) ? 'checked="checked"' : ''
        );
    }

    public function getCommands() : array
    {
        return [
            self::CMD_INITIALIZE,
            self::CMD_SAVE_SELECTION
        ];
    }

    public function getInitializationCommand() : string
    {
        return self::CMD_INITIALIZE;
    }

    public function getQuestionPageActions(ISelectionObject $object) : string
    {
        $current_class = $this->ctrl->getCmdClass();

        $this->ctrl->setParameterByClass(
            $current_class,
            self::PARAM_SELECTION_KEY,
            $object->getKey());

        $target =
            $this->ctrl->getLinkTargetByClass(
                $this->ctrl->getCmdClass(),
                self::CMD_SAVE_SELECTION
            );

        return sprintf(
            '<button class="btn btn-default" type="submit" formmethod="post" formaction="%s">%s</button>',
            $target,
            'TODO Select Questions'
        );
    }
}