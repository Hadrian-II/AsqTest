<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Page;

use ILIAS\Data\UUID\Uuid;
use ILIAS\DI\UIServices;
use ilTemplate;
use srag\asq\Infrastructure\Helpers\PathHelper;
use srag\asq\Test\Application\Section\SectionService;
use srag\asq\Test\Domain\Section\Model\AssessmentSectionDto;
use srag\asq\Test\Domain\Test\ITestAccess;
use srag\asq\Test\Domain\Test\Model\AssessmentTestDto;
use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\IPageModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSelectionModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Objects\ISourceObject;
use srag\asq\Test\Lib\Event\IEventQueue;
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
    const SET_SELECTION = 'setSelection';

    const SECTION_UUID = 'sectionUuid';

    private UIServices $ui;

    private ilCtrl $ctrl;

    /**
     * @var IQuestionSourceModule[]
     */
    private array $available_sources;

    /**
     * @var IQuestionSelectionModule[]
     */
    private array $available_selections;

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

        $this->available_sources = $available_sources;
        $this->available_selections = $available_selections;

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
            self::SHOW_QUESTIONS
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

        foreach($this->access->getObjectsOfType(ITestModule::TYPE_QUESTION_SOURCE) as $source) {
            $this->renderSource($tpl, $source);
        }

        return $tpl->get();
    }

    private function renderSource(ilTemplate $tpl, ISourceObject $source) {
        $tpl->setCurrentBlock('section');
        $tpl->setVariable("QUESTION_TITLE", 'TODO_Titel');
        $tpl->setVariable("QUESTION_VERSION", 'TODO_Version');
        $tpl->setVariable("QUESTION_TYPE", 'TODO_Type');
        $tpl->setVariable("QUESTION_POINTS", 'TODO_Points');
        $tpl->setVariable("SELECTION_TYPE", $this->renderSelectionTypeSelection($source->getKey()));
        $tpl->parseCurrentBlock();
    }

    private function renderSelectionTypeSelection(string $source_key) : string
    {
        $current_class = $this->ctrl->getCmdClass();

        $this->ctrl->setParameterByClass(
            $current_class,
            self::SECTION_UUID,
            $source_key);

        $sources = array_map(function (IQuestionSelectionModule $module) {
            return $this->ui->factory()->button()->shy(
                get_class($module),
                $this->ctrl->getLinkTargetByClass(
                    $this->ctrl->getCmdClass(),
                    self::SET_SELECTION
                )
            );
        }, $this->available_selections);

        $selection = $this->ui->factory()->dropdown()->standard($sources)->withLabel("Add Source");

        return $this->ui->renderer()->render($selection);
    }

    public function setSelection() : void
    {
        $this->qpShow();
    }
}