<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test;

use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use ILIAS\DI\Exceptions\Exception;
use srag\asq\Test\Domain\Test\Modules\IQuestionSelectionModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule;
use srag\asq\Test\Domain\Test\Modules\IStorageModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Objects\ITestObject;
use srag\asq\Test\Domain\Test\Objects\ObjectConfiguration;
use srag\asq\Test\Lib\Event\Event;
use srag\asq\Test\Lib\Event\EventQueue;
use srag\asq\Test\Lib\Event\IEventUser;
use srag\asq\Test\Lib\Event\Standard\ExecuteCommandEvent;
use srag\asq\Test\Lib\Event\Standard\ForwardToCommandEvent;
use srag\asq\Test\Lib\Event\Standard\RemoveObjectEvent;
use srag\asq\Test\Lib\Event\Standard\StoreObjectEvent;
use srag\asq\Test\UI\System\ITestUI;
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
    use CtrlTrait;

    /**
     * @var ITestModule[] $commands
     */
    protected array $commands =[];

    protected EventQueue $event_queue;

    protected TestUI $ui;

    protected ITestAccess $access;

    protected ?IStorageModule $data = null;

    /**
     * @var ITestModule[]
     */
    protected array $modules = [];

    /**
     * @var ITestObject[]
     */
    protected array $objects = [];

    public function __construct()
    {
        global $DIC;

        $this->event_queue = new EventQueue();
        $this->ui = new TestUI();
        $this->access = new TestAccess($this);

        $this->event_queue->addUser($this->ui);
        $this->event_queue->addUser($this);
    }

    protected function addModule(ITestModule $module) : void {
        $class = get_class($module);
        $this->modules[$class] = $module;
        $this->event_queue->addUser($module);

        if ($module instanceof IStorageModule) {
            if ($this->data !== null) {
                throw new Exception('Test object can only have one datasource');
            }

            $this->data = $module;
        }

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

        foreach ($this->modules as $key => $module) {
            if (in_array($class, class_implements($module))) {
                $matches[$key] = $module;
            }
        }

        return $matches;
    }

    public function getObject(string $key): ITestObject
    {
        if (!array_key_exists($key, $this->objects)) {
            /** @var ObjectConfiguration $config */
            $config = $this->data->getConfiguration($key);

            $this->objects[$key] = $this->getModule($config->moduleName())->createObject($config);
        }

        return $this->objects[$key];
    }

    public function getObjectsOfType(string $type) : array
    {
        $modules = [];
        switch ($type) {
            case ITestModule::TYPE_QUESTION_SOURCE:
                $modules = $this->getModulesOfType(IQuestionSourceModule::class);
                break;
            case ITestModule::TYPE_QUESTION_SELECTION:
                $modules = $this->getModulesOfType(IQuestionSelectionModule::class);
                break;
            default:
                throw new Exception('need to implement getObjectsOfType: ' . $type);
        }

        $modules = array_map(function($module) {
            return get_class($module);
        }, $modules);

        $objects = [];
        foreach ($this->data->getConfigurations() as $key => $configuration)
        {
            if (! in_array(ObjectConfiguration::class, class_parents($configuration))) {
                continue;
            }

            /** @var $configuration ObjectConfiguration */
            if (in_array($configuration->moduleName(), $modules)) {
                $objects[] = $this->getObject($key);
            }
        }

        return $objects;
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
        if (get_class($event) === StoreObjectEvent::class) {
            $this->processStoreObjectEvent($event->getData());
        }

        if (get_class($event) === RemoveObjectEvent::class) {
            $this->processRemoveObjectEvent($event->getData());
        }

        if (get_class($event) === ExecuteCommandEvent::class) {
            $this->executeCommand($event->getData());
        }

        if (get_class($event) === ForwardToCommandEvent::class) {
            $this->processForwardToCommandEvent($event->getData());
        }
    }

    private function processForwardToCommandEvent(string $command) : void
    {
        $this->redirectToCommand($command);
    }

    public function processStoreObjectEvent(ITestObject $object) : void
    {
        $this->objects[$object->getKey()] = $object;
        $this->data->setConfiguration($object->getKey(), $object->getConfiguration());
        $this->data->save();
    }

    public function processRemoveObjectEvent(ITestObject $object) : void
    {
        unset($this->objects[$object->getKey()]);
        $this->data->removeConfiguration($object->getKey());
        $this->data->save();
    }

    abstract public static function getInitialCommand() : string;
}