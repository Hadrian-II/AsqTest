<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject;

use Fluxlabs\Assessment\Test\Application\Test\Event\StoreTestDataEvent;
use Fluxlabs\Assessment\Test\Application\TestRunner\TestRunnerService;
use Fluxlabs\Assessment\Test\Domain\Result\Model\AssessmentResultContext;
use Fluxlabs\Assessment\Test\Domain\Section\Model\AssessmentSection;
use Fluxlabs\Assessment\Test\Domain\Section\Model\AssessmentSectionData;
use Fluxlabs\Assessment\Test\Domain\Section\Model\AssessmentSectionDto;
use Fluxlabs\Assessment\Test\Domain\Section\Model\SectionPart;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event\SectionDefinition;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event\StoreAnswerEvent;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event\StoreSectionsEvent;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event\SubmitTestEvent;
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

    public function getTestQuestions() : array
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

        if (get_class($event) === StoreSectionsEvent::class) {
            $this->processStoreSectionEvent($event->getSections());
        }
    }

    private function processStoreTestDataEvent(TestData $data) : void
    {
        $this->currentTestData()->setTestData($data);
        $this->test_service->saveTest($this->test_data);
    }

    /**
     * @param SectionDefinition[] $sections
     */
    private function processStoreSectionEvent(array $sections) : void
    {
        $current_sections = [];

        foreach ($this->currentTestData()->getSections() as $id) {
            $section = $this->section_service->getSection($id);

            $current_sections[$section->getData()->getTitle()] = $section;
        }

        $new_sections = [];

        foreach ($sections as $section) {
            $new_sections[$section->getName()] = $section->getQuestions();
        }

        $existing_sections = array_intersect(array_keys($current_sections), array_keys($new_sections));
        $created_sections = array_diff(array_keys($new_sections), $existing_sections);
        $removed_sections =  array_diff(array_keys($current_sections), $existing_sections);

        foreach ($created_sections as $created_section) {
            $this->createNewSection($created_section, $new_sections[$created_section]);
        }

        foreach ($existing_sections as $existing_section) {
            $this->updateSection(
                $current_sections[$existing_section],
                $new_sections[$existing_section]);
        }

        foreach ($removed_sections as $removed_section) {
            $this->test_service->removeSection($current_sections[$removed_section]->getId());
        }
    }

    private function createNewSection(string $title, array $questions) : void
    {
        $section_id = $this->section_service->createSection();

        $this->test_service->addSection($this->test_id, $section_id);

        $this->section_service->setSectionData($section_id, new AssessmentSectionData($title));

        foreach ($questions as $question) {
            $this->section_service->addQuestion($section_id, $question);
        }
    }

    private function updateSection(AssessmentSectionDto $section, array $new_questions) : void
    {
        $current_questions = [];

        foreach ($section->getItems() as $item) {
            $current_questions[] = $item->getId();
        }

        $existing_questions = array_intersect($current_questions, $new_questions);
        $created_questions = array_diff($new_questions, $existing_questions);
        $deleted_questions = array_diff($current_questions, $existing_questions);

        foreach ($created_questions as $created_question) {
            $this->section_service->addQuestion($section->getId(), $created_question);
        }

        foreach ($deleted_questions as $deleted_question) {
            $this->section_service->removeQuestion($section->getId(), $deleted_question);
        }
    }
}