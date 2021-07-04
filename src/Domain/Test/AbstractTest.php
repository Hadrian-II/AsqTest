<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test;

use ILIAS\Data\Result;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Lib\Event\EventQueue;

/**
 * Interface Test
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
abstract class AbstractTest implements ITest
{
    protected EventQueue $eventQueue;

    /**
     * @var ITestModule[]
     */
    protected array $modules;

    public function __construct()
    {
        $this->eventQueue = new EventQueue();
    }

    protected function addModule(ITestModule $module) : void {
        $this->modules[get_class($module)] = $module;
        $this->eventQueue->addUser($module);
    }

    public function getModule(string $class) : ITestModule
    {
        return $this->modules[$class];
    }
}