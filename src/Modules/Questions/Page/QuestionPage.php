<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Page;

use ILIAS\DI\UIServices;
use ILIAS\HTTP\Services;
use ilTemplate;
use srag\asq\Application\Service\AsqServices;
use srag\asq\Infrastructure\Helpers\PathHelper;
use srag\asq\Test\Domain\Test\ITestAccess;
use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\IPageModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSelectionModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Objects\ISelectionObject;
use srag\asq\Test\Domain\Test\Objects\ISourceObject;
use srag\asq\Test\Lib\Event\IEventQueue;
use srag\asq\Test\Lib\Event\Standard\ForwardToCommandEvent;
use srag\asq\Test\Lib\Event\Standard\RemoveObjectEvent;
use srag\asq\Test\UI\System\AddTabEvent;
use srag\asq\Test\UI\System\SetUIEvent;
use srag\asq\Test\UI\System\TabDefinition;
use srag\asq\Test\UI\System\UIData;
use ilCtrl;

/**
 * Class QuestionPage
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPage extends AbstractTestModule implements IPageModule
{
    use PathHelper;

    const SHOW_QUESTIONS = 'qpShow';
    const REMOVE_SOURCE = 'qpRemoveSource';

    const PARAM_SOURCE_KEY = 'sourceKey';

    private UIServices $ui;

    private ilCtrl $ctrl;

    private AsqServices $asq;

    private Services $http;

    /**
     * @var IQuestionSourceModule[]
     */
    private array $available_sources;

    /**
     * @var IQuestionSelectionModule[]
     */
    private array $available_selections;

    /**
     * @var ISourceObject[]
     */
    private array $source_objects = [];

    /**
     * @var ISelectionObject[]
     */
    private array $selection_objects = [];

    public function __construct(
        IEventQueue $event_queue,
        ITestAccess $access,
        array $available_sources,
        array $available_selections)
    {
        parent::__construct($event_queue, $access);

        global $DIC;
        $this->ui = $DIC->ui();
        $this->ctrl = $DIC->ctrl();
        $this->http = $DIC->http();
        global $ASQDIC;
        $this->asq = $ASQDIC->asq();

        $this->available_sources = $available_sources;
        $this->available_selections = $available_selections;

        foreach ($this->access->getObjectsOfType(ITestModule::TYPE_QUESTION_SOURCE) as $source) {
            $this->source_objects[$source->getKey()] = $source;
        }

        foreach ($this->access->getObjectsOfType(ITestModule::TYPE_QUESTION_SELECTION) as $selection) {
            $this->selection_objects[$selection->getSource()->getKey()] = $selection;
        }

        $this->raiseEvent(new AddTabEvent(
            $this,
            new TabDefinition(self::class, 'Questions', self::SHOW_QUESTIONS)
        ));
    }

    public function getType(): string
    {
        return ITestModule::TYPE_PAGE;
    }

    public function getCommands(): array
    {
        return [
            self::SHOW_QUESTIONS,
            self::REMOVE_SOURCE
        ];
    }

    protected function qpShow() : void
    {
        $this->raiseEvent(new SetUIEvent($this, new UIData(
            'Questions',
            $this->renderContent(),
            null,
            $this->renderToolbar()
        )));
    }

    private function renderToolbar() : array
    {
        $sources = array_map(function (IQuestionSourceModule $module) {
            return $this->ui->factory()->button()->shy(
                get_class($module),
                $this->ctrl->getLinkTargetByClass(
                    $this->ctrl->getCmdClass(),
                    $module->getInitializationCommand()
                )
            );
        }, $this->available_sources);

        $src = $this->ui->factory()->dropdown()->standard($sources)->withLabel("Add Source");

        return [ $src ];
    }

    private function renderContent() : string
    {
        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'src/Modules/Questions/Page/QuestionPage.html', true, true);

        foreach($this->source_objects as $source) {
            $this->renderSource($tpl, $source);
        }

        return $tpl->get();
    }

    private function renderSource(ilTemplate $tpl, ISourceObject $source) {
        $selection = $this->selection_objects[$source->getKey()];

        if ($selection !== null) {
            $this->renderQuestions($tpl, $source, $selection);
        }

        $tpl->setCurrentBlock('source');
        $tpl->setVariable("SELECTION_TYPE", $this->renderSelectionTypeSelection($source->getKey()));

        if ($selection !== null) {
            $tpl->setVariable("CURRENT_SELECTION", $selection->getKey());
        }

        $tpl->setVariable("QUESTION_TITLE", 'TODO_Title');
        $tpl->setVariable("QUESTION_VERSION", 'TODO_Version');
        $tpl->setVariable("QUESTION_TYPE", 'TODO_Type');
        $tpl->setVariable("QUESTION_POINTS", 'TODO_Points');

        $tpl->setVariable("REMOVE_SOURCE", $this->renderRemoveButton($source->getKey()));
        $tpl->setVariable("SOURCE_ACTIONS", $this->available_sources[$source->getConfiguration()->moduleName()]->getQuestionPageActions($source));
        if ($selection !== null) {
            $tpl->setVariable("SELECTION_ACTIONS",
                $this->available_selections[$selection->getConfiguration()->moduleName()]->getQuestionPageActions($selection));
        }
        $tpl->parseCurrentBlock();
    }

    private function renderSelectionTypeSelection(string $source_key) : string
    {
        $current_class = $this->ctrl->getCmdClass();

        $this->ctrl->setParameterByClass(
            $current_class,
            IQuestionSelectionModule::PARAM_SOURCE_KEY,
            $source_key);

        $sources = array_map(function (IQuestionSelectionModule $module) {
            return $this->ui->factory()->button()->shy(
                get_class($module),
                $this->ctrl->getLinkTargetByClass(
                    $this->ctrl->getCmdClass(),
                    $module->getInitializationCommand()
                )
            );
        }, $this->available_selections);

        $selection = $this->ui->factory()->dropdown()->standard($sources)->withLabel("Set Selection");

        return $this->ui->renderer()->render($selection);
    }

    private function renderRemoveButton(string $source_key) : string
    {
        $current_class = $this->ctrl->getCmdClass();

        $this->ctrl->setParameterByClass(
            $current_class,
            self::PARAM_SOURCE_KEY,
            $source_key);

        $button = $this->ui->factory()->button()->standard(
            'TODO Remove',
            $this->ctrl->getLinkTargetByClass(
                $this->ctrl->getCmdClass(),
                self::REMOVE_SOURCE
            )
        );

        return $this->ui->renderer()->render($button);
    }

    private function renderQuestions(ilTemplate $tpl, ISourceObject $source, ISelectionObject $selection) : void
    {
        $selection_module = $this->available_selections[$selection->getConfiguration()->moduleName()];

        foreach ($source->getQuestionIds() as $question_id) {
            $question = $this->asq->question()->getQuestionByQuestionId($question_id);

            $tpl->setCurrentBlock('question');
            $tpl->setVariable("QUESTION_CONTENT", $selection_module->renderQuestionListItem($selection, $question));
            $tpl->parseCurrentBlock();
        }
    }

    public function qpRemoveSource() : void
    {
        $source_key = $this->http->request()->getQueryParams()[self::PARAM_SOURCE_KEY];

        if (array_key_exists($source_key, $this->selection_objects)) {
            $this->raiseEvent(new RemoveObjectEvent($this, $this->selection_objects[$source_key]));
        }

        $this->raiseEvent(new RemoveObjectEvent($this, $this->source_objects[$source_key]));

        $this->raiseEvent(new ForwardToCommandEvent($this, self::SHOW_QUESTIONS));
    }
}