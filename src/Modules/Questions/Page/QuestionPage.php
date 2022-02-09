<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Page;

use Fluxlabs\Assessment\Test\Domain\Result\Model\QuestionDefinition;
use Fluxlabs\Assessment\Test\Domain\Section\Model\AssessmentSectionData;
use Fluxlabs\Assessment\Test\Infrastructure\Setup\lang\SetupAsqTestLanguages;
use Fluxlabs\Assessment\Test\Modules\Questions\Selection\SelectionChosenEvent;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event\SectionDefinition;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event\StoreSectionsEvent;
use Fluxlabs\Assessment\Test\Modules\Storage\RunManager\Event\CreateInstanceEvent;
use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use Fluxlabs\Assessment\Tools\DIC\KitchenSinkTrait;
use Fluxlabs\Assessment\Tools\DIC\LanguageTrait;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\IModuleDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\IPageModule;
use Fluxlabs\Assessment\Tools\Event\Standard\ExecuteCommandEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\ForwardToCommandEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\RemoveObjectEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\SetUIEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\StoreObjectEvent;
use Fluxlabs\Assessment\Tools\UI\System\UIData;
use Fluxlabs\CQRS\Aggregate\RevisionId;
use ILIAS\Data\UUID\Uuid;
use ILIAS\DI\Exceptions\Exception;
use ilTemplate;
use ilUtil;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Application\Service\AsqServices;
use srag\asq\Domain\QuestionDto;
use srag\asq\Infrastructure\Helpers\PathHelper;
use Fluxlabs\Assessment\Test\Application\Test\Module\IQuestionSelectionModule;
use Fluxlabs\Assessment\Test\Application\Test\Module\IQuestionSourceModule;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISelectionObject;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISourceObject;
use srag\asq\UserInterface\Web\PostAccess;

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
    use PostAccess;

    const CMD_SHOW_QUESTIONS = 'qpShow';
    const CMD_REMOVE_SOURCE = 'qpRemoveSource';
    const CMD_INITIALIZE_TEST = 'qpInitialzeTest';
    const CMD_SELECT_QUESTIONS = 'qpSaveQuestions';

    const PARAM_SOURCE_KEY = 'sourceKey';

    const SELECT_REVISION_KEY = 'revision';
    const NO_REVISION = 'no_revision';

    const QUESTION_TAB = 'question_tab';

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

    protected function initialize() : void
    {
        global $ASQDIC;
        $this->asq = $ASQDIC->asq();
    }

    protected function qpShow() : void
    {
        $this->getAvailableModules();

        $this->raiseEvent(new SetUIEvent($this, new UIData(
            $this->access->getStorage()->getTestData()->getTitle(),
            $this->renderContent(),
            null,
            $this->renderToolbar()
        )));
    }

    private function getAvailableModules() : void
    {
        $this->available_sources = $this->access->getModulesOfType(IQuestionSourceModule::class);
        $this->available_selections = $this->access->getModulesOfType(IQuestionSelectionModule::class);

        foreach ($this->access->getObjectsOfModules($this->available_sources) as $source) {
            $this->source_objects[$source->getKey()] = $source;
        }

        foreach ($this->access->getObjectsOfModules($this->available_selections) as $selection) {
            $this->selection_objects[$selection->getSource()->getKey()] = $selection;
        }
    }

    private function renderToolbar() : array
    {
        $buttons = [];

        $sources = array_map(function (IQuestionSourceModule $module) {
            return $this->getKSFactory()->button()->shy(
                $this->txt($module->getTitleKey()),
                $this->getCommandLink($module->getInitializationCommand())
            );
        }, $this->available_sources);

        $buttons[] = $this->getKSFactory()->dropdown()->standard($sources)->withLabel('Add Source');

        $buttons[] = $this->getKSFactory()->button()->standard(
            $this->txt('asqt_init_test'),
            $this->getCommandLink(self::CMD_INITIALIZE_TEST));

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
        }

        $this->renderQuestions($tpl, $source);

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
        $tpl->setVariable('SELECT_QUESTIONS', $this->renderSelectionButton($source->getKey()));
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
                $this->txt($module->getTitleKey()),
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
            $this->getCommandLink(self::CMD_REMOVE_SOURCE)
        );

        return $this->renderKSComponent($button);
    }

    private function renderSelectionButton(string $key)
    {
        $this->setLinkParameter(self::PARAM_SOURCE_KEY, $key);

        return sprintf(
            '<button class="btn btn-default" type="submit" formmethod="post" formaction="%s">%s</button>',
            $this->getCommandLink(self::CMD_SELECT_QUESTIONS),
            $this->txt('asqt_select_questions')
        );
    }

    private function renderQuestions(ilTemplate $tpl, ISourceObject $source) : void
    {
        $selections = [];
        foreach($source->getQuestions() as $definition)
        {
            $selections[$definition->getQuestionId()->toString()] = $definition;
        }

        foreach ($source->getAllQuestions() as $question_id) {
            $question = $this->asq->question()->getQuestionByQuestionId($question_id);

            $definition = array_key_exists($question_id->toString(), $selections) ?
                $selections[$question_id->toString()] : null;

            $tpl->setCurrentBlock('question');
            $tpl->setVariable('QUESTION_CONTENT', $this->renderQuestionListItem($question, $definition, $source->getKey()));
            $tpl->parseCurrentBlock();
        }
    }

    private function renderQuestionListItem(QuestionDto $question, ?QuestionDefinition $definition, string $key) : string
    {
        return sprintf(
            '<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>',
            $this->renderSelectionCheckbox($question, !is_null($definition), $key),
            $question->getData()->getTitle(),
            $this->renderRevisions($question, $key, $definition ? $definition->getRevisionName() : null),
            $question->getType()->getTitleKey(),
            $question->isComplete() ? $this->asq->answer()->getMaxScore($question) : 'Incomplete'
        );
    }

    private function renderRevisions(QuestionDto $question, string $key, ?string $current_revision) : string
    {
        $options = array_reduce(
            $question->getRevisions() ?? [],
            function(string $options, RevisionId $revision)  use ($current_revision) {
                return $options . sprintf('<option value="%1$s" %2$s>%1$s</option>',
                                                $revision->getName(),
                                                $revision->getName() === $current_revision ? 'selected="selected"' : ''
                    );
            },
            sprintf('<option value="">%s</option>',
                $this->txt('asqt_no_revision')
            )
        );

        return sprintf('<select name="%s">%s</select>',
            $this->getRevisionKey($question->getId(), $key),
            $options
        );
    }

    private function getRevisionKey(Uuid $question_id, string $key) : string
    {
        return $question_id->toString() . $key . self::SELECT_REVISION_KEY;
    }

    private function renderSelectionCheckbox(QuestionDto $question, bool $selected, string $key) : string
    {
        return sprintf(
            '<input type="checkbox" name="%s" %s/>',
            $key . $question->getId()->toString(),
            $selected ? 'checked="checked"' : ''
        );
    }

    public function qpSaveQuestions() : void
    {
        $source_key = $this->getLinkParameter(self::PARAM_SOURCE_KEY);
        /** @var ISourceObject $source */
        $source = $this->access->getObject($source_key);

        $selected_questions = [];

        foreach ($source->getAllQuestions() as $question_id) {
            if ($this->isPostVarSet($source_key . $question_id->toString())) {
                $revision = $this->getPostValue($this->getRevisionKey($question_id, $source_key));

                $selected_questions[] = QuestionDefinition::create($question_id, $revision === self::NO_REVISION? null : $revision);
            }
        }

        $source->setSelections($selected_questions);

        $this->raiseEvent(new StoreObjectEvent(
            $this,
            $source
        ));

        $this->raiseEvent(new ForwardToCommandEvent(
            $this,
            QuestionPage::CMD_SHOW_QUESTIONS
        ));
    }

    public function qpInitialzeTest() : void
    {
        $this->getAvailableModules();

        $sections = [];

        if (count($this->source_objects) !== count($this->selection_objects)) {
            ilUtil::sendFailure($this->txt('asqt_missing_selection'));
            $this->raiseEvent(new ExecuteCommandEvent($this, self::CMD_SHOW_QUESTIONS));
            return;
        }

        foreach (array_merge($this->source_objects, $this->selection_objects) as $object)
        {
            if (!$object->isValid()) {
                ilUtil::sendFailure($this->txt('asqt_test_impossible'));
                $this->raiseEvent(new ExecuteCommandEvent($this,self::CMD_SHOW_QUESTIONS));
                return;
            }
        }

        foreach ($this->selection_objects as $selection_object) {
            $section_data = new AssessmentSectionData($selection_object->getKey());
            $section_data->addClass(ISelectionObject::class, $selection_object->getConfiguration());

            $sections[] =
                new SectionDefinition(
                    $section_data,
                    $selection_object->getSource()->getQuestions());
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

        $this->raiseEvent(new ForwardToCommandEvent($this, self::CMD_SHOW_QUESTIONS));
    }

    public function qpRemoveSource() : void
    {
        $source_key = $this->getLinkParameter(self::PARAM_SOURCE_KEY);

        if (array_key_exists($source_key, $this->selection_objects)) {
            $this->raiseEvent(new RemoveObjectEvent($this, $this->selection_objects[$source_key]));
        }

        $this->raiseEvent(new RemoveObjectEvent($this, $this->source_objects[$source_key]));

        $this->raiseEvent(new ForwardToCommandEvent($this, self::CMD_SHOW_QUESTIONS));
    }

    public function processEvent(object $event): void
    {
        if (get_class($event) === SelectionChosenEvent::class) {
            $this->processSelectionChosen($event->getSource(), $event->getSelection());
        }
    }

    private function processSelectionChosen(ISourceObject $source, ISelectionObject $selection) : void
    {
        $this->getAvailableModules();

        $current_selection = $this->getCurrentSelectionOfSource($source);

        // no action required if already using current selection type
        if ($current_selection !== null && (get_class($current_selection) === get_class($selection))) {
            return;
        }

        // delete current selection to be replaced
        if ($current_selection !== null && (get_class($current_selection) !== get_class($selection))) {
            $this->raiseEvent(new RemoveObjectEvent(
                $this,
                $selection
            ));
        }

        $this->raiseEvent(new StoreObjectEvent(
            $this,
            $selection
        ));
    }

    private function getCurrentSelectionOfSource(ISourceObject $source) : ?ISelectionObject
    {
        foreach($this->selection_objects as $selection) {
            if ($selection->getSource()->getKey() === $source->getKey()) {
                return $selection;
            }
        }

        return null;
    }

    public function getModuleDefinition(): IModuleDefinition
    {
        return new QuestionPageModuleDefinition();
    }
}