<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject;

use Fluxlabs\Assessment\Test\Application\Test\Event\StoreTestDataEvent;
use Fluxlabs\Assessment\Test\Application\TestRunner\TestRunnerService;
use Fluxlabs\Assessment\Test\Domain\Result\Model\AssessmentResultContext;
use Fluxlabs\Assessment\Test\Domain\Section\Model\SectionPart;
use Fluxlabs\Assessment\Tools\Domain\IObjectAccess;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\IStorageModule;
use Fluxlabs\Assessment\Tools\Event\IEventQueue;
use ILIAS\Data\UUID\Factory;
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
    protected TestRunnerService $runner_service;

    protected ?AssessmentTestDto $test_data = null;

    protected Factory $factory;

    public function __construct(IEventQueue $event_queue, IObjectAccess $access, Uuid $test_id)
    {
        $this->test_id = $test_id;
        $this->section_service = new SectionService();
        $this->test_service = new TestService();
        $this->runner_service = new TestRunnerService();
        $this->factory = new Factory();

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

    public function getPlayerContext(?Uuid $current_question = null) : AssessmentTestContext
    {
        global $DIC;
        $user = $DIC->user->$DIC->user()->getId();
        $key = "current_result_" . $user;

        if ($_SESSION[$key] === null) {
            $uuid = $this->runner_service->createTestRun(
                $this->createResultContext(),
                $this->getTestQuestions()
            );
            $_SESSION[$key] = $uuid->toString();
        }
        else
        {
            $uuid = $this->factory->fromString($_SESSION[$key]);
        }

        return new AssessmentTestContext($uuid, $current_question, $this->runner_service);
    }

    private function createResultContext(int $user_id) : AssessmentResultContext
    {
        return new AssessmentResultContext(
            $user_id,
            $this->currentTestData()->getTestData()->getTitle(),
            1,
            $this->currentTestData()->getId()
        );
    }

    private function getTestQuestions() : array
    {
        $questions = [];

        foreach ($this->currentTestData()->getSections() as $section_id)
        {
            $questions = array_merge($questions, $this->readSection($section_id));
        }

        return $questions;
    }

    private function readSection(Uuid $section_id) : array
    {
        $section = $this->section_service->getSection($section_id);
        $questions = [];

        foreach ($section->getItems() as $item) {
            switch ($item->getType()) {
                case SectionPart::TYPE_QUESTION:
                    $questions[] = $item->getId();
                    break;
                case SectionPart::TYPE_SECTION:
                    $questions = array_merge($questions, $this->readSection($item->getId()));
                    break;
            }
        }

        return $questions;
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