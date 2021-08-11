<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test;

use ilCtrl;
use ILIAS\DI\HTTPServices;
use srag\asq\Test\Application\Section\SectionService;
use srag\asq\Test\Application\Test\TestService;
use srag\asq\Test\Domain\Test\Model\AssessmentTestDto;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Lib\Event\Event;
use srag\asq\Test\Lib\Event\EventQueue;
use srag\asq\Test\Lib\Event\IEventUser;
use srag\asq\Test\Lib\Event\Standard\AddSectionEvent;
use srag\asq\Test\Lib\Event\Standard\ExecuteCommandEvent;
use srag\asq\Test\Lib\Event\Standard\ForwardToCommandEvent;
use srag\asq\Test\UI\System\ITestUI;
use srag\asq\Test\UI\System\SetUIEvent;
use srag\asq\Test\UI\System\TestUI;

/**
 * Interface Test
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
abstract class AbstractTest implements ITest, IEventUser
{
    /**
     * @var ITestModule[] $commands
     */
    protected array $commands =[];

    protected EventQueue $event_queue;

    protected AssessmentTestDto $test_data;

    protected TestUI $ui;

    protected SectionService $section_service;

    protected TestService $test_service;

    protected ilCtrl $ctrl;

    /**
     * @var ITestModule[]
     */
    protected array $modules = [];

    public function __construct(AssessmentTestDto $test_data)
    {
        global $DIC;
        $this->section_service = new SectionService();
        $this->test_service = new TestService();

        $this->test_data = $test_data;
        $this->event_queue = new EventQueue();
        $this->ui = new TestUI();
        $this->ctrl = $DIC->ctrl();

        $this->event_queue->addUser($this->ui);
        $this->event_queue->addUser($this);
    }

    protected function addModule(ITestModule $module) : void {
        $class = get_class($module);
        $this->modules[$class] = $module;
        $this->event_queue->addUser($module);

        foreach ($module->getCommands() as $command) {
            $this->commands[$command] = $module;
        }
    }

    public function getModule(string $class) : ITestModule
    {
        return $this->modules[$class];
    }

    public function getModulesOfType(string $class) : array
    {
        $matches = [];

        foreach ($this->modules as $module) {
            if (in_array($class, class_implements($module))) {
                $matches[] = $module;
            }
        }

        return $matches;
    }

    public function executeCommand(string $command) : void
    {
        if (array_key_exists($command, $this->commands)) {
            $this->commands[$command]->executeCommand($command);
        }
    }

    public function ui() : ITestUI
    {
        return $this->ui;
    }

    public function processEvent(Event $event) : void
    {
        if (get_class($event) === AddSectionEvent::class) {
            $this->processAddSectionEvent($event);
        }

        if (get_class($event) === ExecuteCommandEvent::class) {
            $this->executeCommand($event->getData());
        }

        if (get_class($event) === ForwardToCommandEvent::class) {
            $this->processForwardToCommandEvent($event->getData());
        }
    }

    private function processAddSectionEvent(AddSectionEvent $event) : void
    {
        $section_id = $this->section_service->createSection();
        $this->section_service->setSectionData($section_id, $event->getData());
        $this->test_service->addSection($this->test_data->getId(), $section_id);
    }

    private function processForwardToCommandEvent(string $command) : void
    {
        $target = $this->ctrl->getLinkTargetByClass($this->ctrl->getCmdClass(), $command);
        $this->ctrl->redirectToURL($target);
    }

    abstract public static function getInitialCommand() : string;
}