<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test;

use ILIAS\Data\Result;
use srag\asq\Test\Domain\Test\Model\AssessmentTestDto;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Lib\Event\EventQueue;
use srag\asq\Test\UI\System\ITestUI;
use srag\asq\Test\UI\System\TestUI;

/**
 * Interface Test
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
abstract class AbstractTest implements ITest
{
    /**
     * @var ITestModule[] $commands
     */
    protected array $commands =[];

    protected EventQueue $event_queue;

    protected AssessmentTestDto $test_data;

    protected TestUI $ui;

    /**
     * @var ITestModule[]
     */
    protected array $modules = [];

    public function __construct(AssessmentTestDto $test_data)
    {
        $this->test_data = $test_data;
        $this->event_queue = new EventQueue();
        $this->ui = new TestUI();
        $this->event_queue->addUser($this->ui);
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
}