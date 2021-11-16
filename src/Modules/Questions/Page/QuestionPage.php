<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Page;

use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event\SectionDefinition;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event\StoreSectionsEvent;
use Fluxlabs\Assessment\Test\Modules\Storage\RunManager\Event\CreateInstanceEvent;
use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use Fluxlabs\Assessment\Tools\DIC\KitchenSinkTrait;
use Fluxlabs\Assessment\Tools\DIC\LanguageTrait;
use Fluxlabs\Assessment\Tools\Domain\IObjectAccess;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\IPageModule;
use Fluxlabs\Assessment\Tools\Event\IEventQueue;
use Fluxlabs\Assessment\Tools\Event\Standard\AddTabEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\ForwardToCommandEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\RemoveObjectEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\SetUIEvent;
use Fluxlabs\Assessment\Tools\UI\System\TabDefinition;
use Fluxlabs\Assessment\Tools\UI\System\UIData;
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
    use KitchenSinkTrait;
    use LanguageTrait;

    const SHOW_QUESTIONS = 'qpShow';
    const REMOVE_SOURCE = 'qpRemoveSource';
    const INITIALIZE_TEST = 'qpInitialzeTest';

    const PARAM_SOURCE_KEY = 'sourceKey';

    private AsqServices $asq;

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
            new TabDefinition(self::class, $this->txt('asqt_questions'), self::SHOW_QUESTIONS)
        ));
    }

    public function getCommands(): array
    {
        return [
            self::SHOW_QUESTIONS,
            self::REMOVE_SOURCE,
            self::INITIALIZE_TEST
        ];
    }

    protected function qpShow() : void
    {
        $this->raiseEvent(new SetUIEvent($this, new UIData(
            $this->txt('asqt_questions'),
            $this->renderContent(),
            null,
            $this->renderToolbar()
        )));
    }

    private function renderToolbar() : array
    {
        $buttons = [];

        $sources = array_map(function (IQuestionSourceModule $module) {
            return $this->getKSFactory()->button()->shy(
                get_class($module),
                $this->getCommandLink($module->getInitializationCommand())
            );
        }, $this->available_sources);

        $buttons[] = $this->getKSFactory()->dropdown()->standard($sources)->withLabel('Add Source');

        $buttons[] = $this->getKSFactory()->button()->standard(
            $this->txt('asqt_init_test'),
            $this->getCommandLink(self::INITIALIZE_TEST));

        return $buttons;
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

        if ($source->hasOverallDisplay()) {
            $tpl->setCurrentBlock('overall');
            $tpl->setVariable('CONTENT', $source->getOverallDisplay());
            $tpl->parseCurrentBlock();
        }

        if ($selection !== null) {
            if ($selection->hasOverallDisplay()) {
                $tpl->setCurrentBlock('overall');
                $tpl->setVariable('CONTENT', $selection->getOverallDisplay());
                $tpl->parseCurrentBlock();
            }

            $this->renderQuestions($tpl, $source, $selection);
        }

        $tpl->setCurrentBlock('source');
        $tpl->setVariable('SELECTION_TYPE', $this->renderSelectionTypeSelection($source->getKey()));

        if ($selection !== null) {
            $tpl->setVariable('CURRENT_SELECTION', $selection->getKey());
        }

        $tpl->setVariable('QUESTION_TITLE', $this->txt('asqt_title'));
        $tpl->setVariable('QUESTION_VERSION',  $this->txt('asqt_version'));
        $tpl->setVariable('QUESTION_TYPE',  $this->txt('asqt_type'));
        $tpl->setVariable('QUESTION_POINTS',  $this->txt('asqt_points'));

        $tpl->setVariable('REMOVE_SOURCE', $this->renderRemoveButton($source->getKey()));
        $tpl->setVariable('SOURCE_ACTIONS', $this->available_sources[$source->getConfiguration()->moduleName()]->getQuestionPageActions($source));
        if ($selection !== null) {
            $tpl->setVariable('SELECTION_ACTIONS',
                $this->available_selections[$selection->getConfiguration()->moduleName()]->getQuestionPageActions($selection));
        }
        $tpl->parseCurrentBlock();
    }

    private function renderSelectionTypeSelection(string $source_key) : string
    {
        $this->setLinkParameter(IQuestionSelectionModule::PARAM_SOURCE_KEY, $source_key);

        $sources = array_map(function (IQuestionSelectionModule $module) {
            return $this->getKSFactory()->button()->shy(
                get_class($module),
                $this->getCommandLink($module->getInitializationCommand())
            );
        }, $this->available_selections);

        $selection = $this->getKSFactory()->dropdown()->standard($sources)->withLabel($this->txt('asqt_select'));

        return $this->renderKSComponent($selection);
    }

    private function renderRemoveButton(string $source_key) : string
    {
        $this->setLinkParameter(self::PARAM_SOURCE_KEY, $source_key);

        $button = $this->getKSFactory()->button()->standard(
            $this->txt('asqt_remove'),
            $this->getCommandLink(self::REMOVE_SOURCE)
        );

        return $this->renderKSComponent($button);
    }

    private function renderQuestions(ilTemplate $tpl, ISourceObject $source, ISelectionObject $selection) : void
    {
        $selection_module = $this->available_selections[$selection->getConfiguration()->moduleName()];

        foreach ($source->getQuestionIds() as $question_id) {
            $question = $this->asq->question()->getQuestionByQuestionId($question_id);

            $tpl->setCurrentBlock('question');
            $tpl->setVariable('QUESTION_CONTENT', $selection_module->renderQuestionListItem($selection, $question));
            $tpl->parseCurrentBlock();
        }
    }

    public function qpInitialzeTest() : void
    {
        $sections = [];

        foreach ($this->selection_objects as $selection_object) {
            $sections[] =
                new SectionDefinition(
                    $selection_object->getKey(),
                    $selection_object->getSelectedQuestionIds());
        }

        $this->raiseEvent(
            new StoreSectionsEvent(
                $this,
                $sections
            )
        );

        $this->raiseEvent(
            new CreateInstanceEvent($this)
        );

        $this->raiseEvent(new ForwardToCommandEvent($this, self::SHOW_QUESTIONS));
    }

    public function qpRemoveSource() : void
    {
        $source_key = $this->getLinkParameter(self::PARAM_SOURCE_KEY);

        if (array_key_exists($source_key, $this->selection_objects)) {
            $this->raiseEvent(new RemoveObjectEvent($this, $this->selection_objects[$source_key]));
        }

        $this->raiseEvent(new RemoveObjectEvent($this, $this->source_objects[$source_key]));

        $this->raiseEvent(new ForwardToCommandEvent($this, self::SHOW_QUESTIONS));
    }
}