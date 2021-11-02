<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test;

use Fluxlabs\Assessment\Test\Application\Test\Command\RemoveSectionCommand;
use ILIAS\Data\UUID\Factory;
use ILIAS\Data\UUID\Uuid;
use Fluxlabs\CQRS\Command\CommandBus;
use Fluxlabs\CQRS\Command\CommandConfiguration;
use Fluxlabs\CQRS\Command\Access\OpenAccess;
use srag\asq\Application\Service\ASQService;
use Fluxlabs\Assessment\Test\Application\Test\Command\AddSectionCommand;
use Fluxlabs\Assessment\Test\Application\Test\Command\AddSectionCommandHandler;
use Fluxlabs\Assessment\Test\Application\Test\Command\CreateTestCommand;
use Fluxlabs\Assessment\Test\Application\Test\Command\CreateTestCommandHandler;
use Fluxlabs\Assessment\Test\Domain\Test\Model\AssessmentTest;
use Fluxlabs\Assessment\Test\Domain\Test\Model\AssessmentTestRepository;
use Fluxlabs\Assessment\Test\Domain\Test\Model\AssessmentTestDto;
use Fluxlabs\Assessment\Test\Domain\Test\Model\TestData;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use srag\asq\QuestionPool\Application\Command\RemoveQuestionCommandHandler;

/**
 * Class TestService
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */

class TestService
{
    private CommandBus $command_bus;

    private AssessmentTestRepository $repo;

    public function __construct()
    {
        $this->command_bus = new CommandBus();

        $this->command_bus->registerCommand(new CommandConfiguration(
            CreateTestCommand::class,
            new CreateTestCommandHandler(),
            new OpenAccess()
        ));

        $this->command_bus->registerCommand(new CommandConfiguration(
            AddSectionCommand::class,
            new AddSectionCommandHandler(),
            new OpenAccess()
        ));

        $this->command_bus->registerCommand(new CommandConfiguration(
            RemoveSectionCommand::class,
            new RemoveQuestionCommandHandler(),
            new OpenAccess()
        ));

        $this->repo = new AssessmentTestRepository();
    }

    public function createTest(Uuid $uuid = null) : Uuid
    {
        if ($uuid === null) {
            $uuid_factory = new Factory();
            $uuid = $uuid_factory->uuid4();
        }

        // CreateQuestion.png
        $this->command_bus->handle(
            new CreateTestCommand(
                $uuid
            )
        );

        return $uuid;
    }

    public function addSection(Uuid $id, Uuid $section_id) : void
    {
        $this->command_bus->handle(
            new AddSectionCommand(
                $id,
                $section_id
            )
        );
    }

    public function removeSection(Uuid $id, Uuid $section_id) : void
    {
        $this->command_bus->handle(
            new RemoveSectionCommand(
                $id,
                $section_id
            )
        );
    }

    public function getTest(Uuid $test_id) : AssessmentTestDto
    {
        return new AssessmentTestDto(
            $this->repo->getAggregateRootById($test_id)
        );
    }

    public function saveTest(AssessmentTestDto $test) : void
    {
        /** @var $stored AssessmentTest */
        $stored = $this->repo->getAggregateRootById($test->getId());

        if (!TestData::isNullableEqual($test->getTestData(), $stored->getTestData())) {
            $stored->setTestData($test->getTestData());
        }

        foreach ($test->getConfigurations() as $configuration_for => $configuration) {
            if (!AbstractValueObject::isNullableEqual($stored->getConfiguration($configuration_for), $configuration)) {
                $stored->setConfiguration($configuration, $configuration_for);
            }
        }

        foreach (array_diff(
                array_keys($stored->getConfigurations()),
                array_keys($test->getConfigurations())) as $deleted)
        {
            $stored->removeConfiguration($deleted);
        }

        foreach (array_diff($test->getSections(), $stored->getSections()) as $new_section) {
            $stored->addSection($new_section);
        }

        foreach (array_diff($stored->getSections(), $test->getSections()) as $removed_section) {
            $stored->removeSection($removed_section);
        }

        if (count($stored->getRecordedEvents()->getEvents()) > 0) {
            $this->repo->save($stored);
        }
    }
}
