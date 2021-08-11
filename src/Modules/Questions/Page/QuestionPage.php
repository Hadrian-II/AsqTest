<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Page;

use ILIAS\Data\UUID\Uuid;
use ILIAS\DI\UIServices;
use ilTemplate;
use srag\asq\Infrastructure\Helpers\PathHelper;
use srag\asq\Test\Application\Section\SectionService;
use srag\asq\Test\Domain\Section\Model\AssessmentSectionDto;
use srag\asq\Test\Domain\Test\Model\AssessmentTestDto;
use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\IPageModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSelectionModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
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

    private AssessmentTestDto $test_data;

    private UIServices $ui;

    private ilCtrl $ctrl;

    private SectionService $sectionService;

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
        AssessmentTestDto $test_data,
        array $available_sources,
        array $available_selections)
    {
        parent::__construct($event_queue);

        global $DIC;
        $this->ui = $DIC->ui();
        $this->ctrl = $DIC->ctrl();
        $this->sectionService = new SectionService();

        $this->test_data = $test_data;
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

        foreach($this->test_data->getSections() as $section_id) {
            $this->renderSection($tpl, $this->sectionService->getSection($section_id));
        }

        return $tpl->get();
    }

    private function renderSection(ilTemplate $tpl, AssessmentSectionDto $section) {
        $tpl->setCurrentBlock('section');
        $tpl->setVariable("QUESTION_TITLE", 'TODO_Titel');
        $tpl->setVariable("QUESTION_VERSION", 'TODO_Version');
        $tpl->setVariable("QUESTION_TYPE", 'TODO_Type');
        $tpl->setVariable("QUESTION_POINTS", 'TODO_Points');
        $tpl->setVariable("SELECTION_TYPE", $this->renderSelectionTypeSelection($section->getId()));
        $tpl->parseCurrentBlock();
    }

    private function renderSelectionTypeSelection(Uuid $section_id) : string
    {
        $key = 'selection_type_' . $section_id;

        return sprintf(
            '<select name="%1$s" id="%1$s" class="btn btn-default dropdown-toggle">%2$s</select>',
            $key,
            implode(array_map(function($source) {
                return sprintf('<option value="%s">%s</option>', get_class($source), $source->getType());
            }, $this->available_selections))
        );
    }
}