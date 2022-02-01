<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject;

use Fluxlabs\Assessment\Test\Application\Test\Event\StoreTestDataEvent;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISelectionObject;
use Fluxlabs\Assessment\Test\Application\TestRunner\TestRunnerService;
use Fluxlabs\Assessment\Test\Domain\Result\Model\QuestionDefinition;
use Fluxlabs\Assessment\Test\Domain\Section\Model\AssessmentSectionDto;
use Fluxlabs\Assessment\Test\Domain\Section\Model\SectionPart;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event\SectionDefinition;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event\StoreSectionsEvent;
use Fluxlabs\Assessment\Tools\Domain\IObjectAccess;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\ModuleDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\IModuleDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\IStorageModule;
use Fluxlabs\Assessment\Tools\Domain\Objects\ObjectConfiguration;
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

    protected function initialize(): void
    {
        $this->test_id = $this->access->getReference()->getId();
        $this->section_service = new SectionService();
        $this->test_service = new TestService();
        $this->runner_service = new TestRunnerService();
        $this->factory = new Factory();
    }

    private function currentTestData() : AssessmentTestDto
    {
        if ($this->test_data === null) {
            $this->test_data = $this->test_service->getTest($this->test_id);
        }

        return $this->test_data;
    }

    public function getTestData() : TestData
    {
        return $this->currentTestData()->getTestData();
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

    /**
     * @return QuestionDefinition[]
     */
    public function getQuestionsForNewRun() : array
    {
        $questions = [];

        foreach ($this->currentTestData()->getSections() as $section_id)
        {
            $questions = array_merge($questions, $this->readSection($section_id));
        }

        return $questions;
    }

    /**
     * @param Uuid $section_id
     * @return QuestionDefinition[]
     */
    private function readSection(Uuid $section_id) : array
    {
        $section = $this->section_service->getSection($section_id);
        $questions = [];

        foreach ($section->getItems() as $item) {
            switch ($item->getType()) {
                case SectionPart::TYPE_QUESTION:
                    $questions[] = QuestionDefinition::create($item->getId(), $item->getRevisionName());
                    break;
                case SectionPart::TYPE_SECTION:
                    $questions = array_merge($questions, $this->readSection($item->getId()));
                    break;
            }
        }

        /** @var ObjectConfiguration $selection_configuration */
        $selection_configuration = $section->getData()->getData(ISelectionObject::class);
        $selection_module = $this->access->getModule($selection_configuration->moduleName());

        /** @var ISelectionObject $selection_object */
        $selection_object = $selection_module->createObject($selection_configuration);

        return $selection_object->selectQuestionsForRun($questions);
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
            $new_sections[$section->getData()->getTitle()] = $section;
        }

        $existing_sections = array_intersect(array_keys($current_sections), array_keys($new_sections));
        $created_sections = array_diff(array_keys($new_sections), $existing_sections);
        $removed_sections =  array_diff(array_keys($current_sections), $existing_sections);

        foreach ($created_sections as $created_section) {
            $this->createNewSection($new_sections[$created_section]);
        }

        foreach ($existing_sections as $existing_section) {
            $this->updateSection(
                $current_sections[$existing_section],
                $new_sections[$existing_section]);
        }

        foreach ($removed_sections as $removed_section) {
            $this->test_service->removeSection($this->test_id, $current_sections[$removed_section]->getId());
        }
    }

    private function createNewSection(SectionDefinition $definition) : void
    {
        $section_id = $this->section_service->createSection();

        $this->test_service->addSection($this->test_id, $section_id);

        $this->section_service->setSectionData($section_id, $definition->getData());

        foreach ($definition->getQuestions() as $question) {
            $this->section_service->addQuestion($section_id, $question->getQuestionId(), $question->getRevisionName());
        }
    }

    private function updateSection(AssessmentSectionDto $section, SectionDefinition $new_definition) : void
    {
        $current_questions = [];

        foreach ($section->getItems() as $item) {
            $current_questions[$item->getId() . $item->getRevisionName()] = $item;
        }

        $new_questions = [];

        foreach ($new_definition->getQuestions() as $question) {
            $new_questions[$question->getQuestionId() . $question->getRevisionName()] = $question;
        }

        $existing_questions = array_intersect(array_keys($current_questions), array_keys($new_questions));
        $created_questions = array_diff(array_keys($new_questions), array_keys($existing_questions));
        $deleted_questions = array_diff(array_keys($current_questions), array_keys($existing_questions));

        foreach ($deleted_questions as $deleted_question) {
            $this->section_service->removeQuestion($section->getId(), $current_questions[$deleted_question]->getId());
        }

        foreach ($created_questions as $created_question) {
            $this->section_service->addQuestion(
                $section->getId(),
                $new_questions[$created_question]->getQuestionId(),
                $new_questions[$created_question]->getRevisionName());
        }

        if (!$section->getData()->equals($new_definition->getData())) {
            $this->section_service->setSectionData($section->getId(), $new_definition->getData());
        }
    }
}