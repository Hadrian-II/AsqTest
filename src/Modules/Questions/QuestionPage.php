<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions;

use ILIAS\DI\UIServices;
use srag\asq\Test\Domain\Test\Model\AssessmentTestDto;
use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\IPageModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Lib\Event\IEventQueue;
use srag\asq\Test\UI\System\AddTabEvent;
use srag\asq\Test\UI\System\SetUIEvent;
use srag\asq\Test\UI\System\TabDefinition;
use srag\asq\Test\UI\System\UIData;
use ilCtrl;

/**
 * Class QuestionPAge
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPage extends AbstractTestModule implements IPageModule
{
    const SHOW_QUESTIONS = 'qpShow';

    private AssessmentTestDto $test_data;

    private UIServices $ui;

    private ilCtrl $ctrl;

    /**
     * @var IQuestionSourceModule[]
     */
    private array $available_sources;

    public function __construct(IEventQueue $event_queue, AssessmentTestDto $test_data, array $available_sources)
    {
        parent::__construct($event_queue);

        global $DIC;
        $this->ui = $DIC->ui();
        $this->ctrl = $DIC->ctrl();

        $this->test_data = $test_data;
        $this->available_sources = $available_sources;

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

        $this->raiseEvent(new SetUIEvent($this, new UIData(
            'Questions',
            'SHOW Questions',
            null,
            [ $src ]
        )));
    }
}