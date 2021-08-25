<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Storage\AssessmentTestObject;

use ILIAS\Data\UUID\Uuid;
use srag\asq\Test\Application\Section\SectionService;
use srag\asq\Test\Application\Test\TestService;
use srag\asq\Test\Domain\Test\ITestAccess;
use srag\asq\Test\Domain\Test\Model\AssessmentTestDto;
use srag\asq\Test\Domain\Test\Model\TestData;
use srag\asq\Test\Lib\Event\Event;
use srag\asq\Test\Lib\Event\IEventQueue;
use srag\asq\Test\Lib\Event\Standard\StoreTestDataEvent;
use srag\asq\Test\Modules\Storage\AbstractStorageModule;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class AssessmentTestStorage
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentTestStorage extends AbstractStorageModule
{
    private Uuid $test_id;

    protected SectionService $section_service;

    protected TestService $test_service;

    protected ?AssessmentTestDto $test_data = null;

    public function __construct(IEventQueue $event_queue, ITestAccess $access, Uuid $test_id)
    {
        $this->test_id = $test_id;
        $this->section_service = new SectionService();
        $this->test_service = new TestService();


        parent::__construct($event_queue, $access);
    }

    private function currentTestData() : AssessmentTestDto
    {
        if ($this->test_data === null) {
            $this->test_data = $this->test_data = $this->test_service->getTest($this->test_id);
        }

        return $this->test_data;
    }

    public function getConfiguration(string $configuration_for): ?AbstractValueObject
    {
        return $this->currentTestData()->getConfiguration($configuration_for);
    }

    public function getConfigurations(): array
    {
        return $this->currentTestData()->getConfigurations();
    }

    public function setConfiguration(string $configuration_for, AbstractValueObject $config): void
    {
        $this->currentTestData()->setConfiguration($configuration_for, $config);
    }

    public function removeConfiguration(string $configuration_for): void
    {
        $this->currentTestData()->removeConfiguration($configuration_for);
    }

    public function save(): void
    {
        if ($this->test_data === null) {
            return;
        }

        $this->test_service->saveTest($this->test_data);
    }

    public function processEvent(object $event) : void
    {
        if (get_class($event) === StoreTestDataEvent::class) {
            $this->processStoreTestDataEvent($event->getData());
        }
    }

    private function processStoreTestDataEvent(TestData $data) : void
    {
        $this->currentTestData()->setTestData($data);
        $this->test_service->saveTest($this->test_data);
    }
}