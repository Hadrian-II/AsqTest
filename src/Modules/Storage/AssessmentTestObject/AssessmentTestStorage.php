<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject;

use Fluxlabs\Assessment\Tools\Domain\IObjectAccess;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\IStorageModule;
use Fluxlabs\Assessment\Tools\Event\IEventQueue;
use Fluxlabs\Assessment\Tools\Event\Standard\StoreTestDataEvent;
use ILIAS\Data\UUID\Uuid;
use Fluxlabs\Assessment\Test\Application\Section\SectionService;
use Fluxlabs\Assessment\Test\Application\Test\TestService;
use Fluxlabs\Assessment\Test\Domain\Test\Model\AssessmentTestDto;
use Fluxlabs\Assessment\Test\Domain\Test\Model\TestData;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;

/**
 * Class AssessmentTestStorage
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentTestStorage extends AbstractAsqModule implements IStorageModule
{
    private Uuid $test_id;

    protected SectionService $section_service;

    protected TestService $test_service;

    protected ?AssessmentTestDto $test_data = null;

    public function __construct(IEventQueue $event_queue, IObjectAccess $access, Uuid $test_id)
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