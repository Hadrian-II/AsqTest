<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Page;

use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use Fluxlabs\Assessment\Tools\Domain\IObjectAccess;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\IAsqModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\IPageModule;
use Fluxlabs\Assessment\Tools\Event\IEventQueue;
use Fluxlabs\Assessment\Tools\Event\Standard\AddTabEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\ForwardToCommandEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\RemoveObjectEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\SetUIEvent;
use Fluxlabs\Assessment\Tools\UI\System\TabDefinition;
use Fluxlabs\Assessment\Tools\UI\System\UIData;
use ILIAS\DI\UIServices;
use ILIAS\HTTP\Services;
use ilTemplate;
use srag\asq\Application\Service\AsqServices;
use srag\asq\Infrastructure\Helpers\PathHelper;
use Fluxlabs\Assessment\Test\Application\Test\Module\IQuestionSelectionModule;
use Fluxlabs\Assessment\Test\Application\Test\Module\IQuestionSourceModule;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISelectionObject;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISourceObject;

/**
 * Class QuestionPage
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPage extends AbstractAsqModule implements IPageModule
{
    use PathHelper;
    use CtrlTrait;

    const SHOW_QUESTIONS = 'qpShow';
    const REMOVE_SOURCE = 'qpRemoveSource';

    const PARAM_SOURCE_KEY = 'sourceKey';

    private UIServices $ui;

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
        IEventQueue   $event_queue,
        IObjectAccess $access,
        array         $available_sources,
        array         $available_selections)
    {
        parent::__construct($event_queue, $access);

        global $DIC;
        $this->ui = $DIC->ui();
        $this->http = $DIC->http();
        global $ASQDIC;
        $this->asq = $ASQDIC->asq();

        $this->available_sources = $available_sources;
        $this->available_selections = $available_selections;

        foreach ($this->access->getObjectsOfModules($available_sources) as $source) {
            $this->source_objects[$source->getKey()] = $source;
        }

        foreach ($this->access->getObjectsOfModules($available_selections) as $selection) {
            $this->selection_objects[$selection->getSource()->getKey()] = $selection;
        }

        $this->raiseEvent(new AddTabEvent(
            $this,
            new TabDefinition(self::class, 'Questions', self::SHOW_QUESTIONS)
        ));
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
                $this->getCommandLink($module->getInitializationCommand())
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
        $this->setLinkParameter(IQuestionSelectionModule::PARAM_SOURCE_KEY, $source_key);

        $sources = array_map(function (IQuestionSelectionModule $module) {
            return $this->ui->factory()->button()->shy(
                get_class($module),
                $this->getCommandLink($module->getInitializationCommand())
            );
        }, $this->available_selections);

        $selection = $this->ui->factory()->dropdown()->standard($sources)->withLabel("Set Selection");

        return $this->ui->renderer()->render($selection);
    }

    private function renderRemoveButton(string $source_key) : string
    {
        $this->setLinkParameter(self::PARAM_SOURCE_KEY, $source_key);

        $button = $this->ui->factory()->button()->standard(
            'TODO Remove',
            $this->getCommandLink(self::REMOVE_SOURCE)
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