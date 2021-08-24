<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection\Manual;

use ilCtrl;
use ILIAS\DI\UIServices;
use srag\asq\Domain\QuestionDto;
use srag\asq\Test\Domain\Test\ITestAccess;
use srag\asq\Test\Domain\Test\Objects\ISelectionObject;
use srag\asq\Test\Lib\Event\IEventQueue;
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

    }

    public function renderQuestionListItem(QuestionDto $question): string
    {
        return sprintf(
            '<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>MANUAL</td>',
            $question->getData()->getTitle(),
            $question->getRevisionId() !== null ? $question->getRevisionId()->getName() : 'Unrevised',
            $question->getType()->getTitleKey(),
            $question->isComplete() ? $this->asq->answer()->getMaxScore($question) : 'Incomplete'
        );
    }

    public function getCommands(): array
    {
        return [
            self::CMD_INITIALIZE,
            self::CMD_SAVE_SELECTION
        ];
    }

    public function getInitializationCommand(): string
    {
        return self::CMD_INITIALIZE;
    }

    public function getQuestionPageActions(ISelectionObject $object): string
    {
        $current_class = $this->ctrl->getCmdClass();

        $this->ctrl->setParameterByClass(
            $current_class,
            self::PARAM_SOURCE_KEY,
            $object->getKey());

        $button = $this->ui->factory()->button()->standard(
            'TODO Select Questions',
            $this->ctrl->getLinkTargetByClass(
                $this->ctrl->getCmdClass(),
                self::CMD_SAVE_SELECTION
            )
        );

        return $this->ui->renderer()->render($button);
    }
}