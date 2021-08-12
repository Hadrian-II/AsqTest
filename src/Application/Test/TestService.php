<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\Test;

use ILIAS\Data\UUID\Factory;
use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\CommandBus;
use srag\CQRS\Command\CommandConfiguration;
use srag\CQRS\Command\Access\OpenAccess;
use srag\asq\Application\Service\ASQService;
use srag\asq\Test\Application\Test\Command\AddSectionCommand;
use srag\asq\Test\Application\Test\Command\AddSectionCommandHandler;
use srag\asq\Test\Application\Test\Command\CreateTestCommand;
use srag\asq\Test\Application\Test\Command\CreateTestCommandHandler;
use srag\asq\Test\Domain\Test\Model\AssessmentTest;
use srag\asq\Test\Domain\Test\Model\AssessmentTestRepository;
use srag\asq\Test\Domain\Test\Model\AssessmentTestDto;
use srag\asq\Test\Domain\Test\Model\TestData;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class TestService
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */

class TestService extends ASQService
{
    private CommandBus $command_bus;

    private AssessmentTestRepository $repo;

    public function __construct()
    {
        $this->command_bus = new CommandBus();

        $this->command_bus->registerCommand(new CommandConfiguration(
            AddSectionCommand::class,
            new AddSectionCommandHandler(),
            new OpenAccess()
        ));

        $this->command_bus->registerCommand(new CommandConfiguration(
            CreateTestCommand::class,
            new CreateTestCommandHandler(),
            new OpenAccess()
        ));

        $this->repo = new AssessmentTestRepository();
    }

    public function createTest() : Uuid
    {
        $uuid_factory = new Factory();
        $uuid = $uuid_factory->uuid4();

        // CreateQuestion.png
        $this->command_bus->handle(
            new CreateTestCommand(
                $uuid,
                $this->getActiveUser()
            )
        );

        return $uuid;
    }

    public function addSection(Uuid $id, Uuid $section_id) : void
    {
        $this->command_bus->handle(
            new AddSectionCommand(
                $id,
                $section_id,
                $this->getActiveUser()
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
            $stored->setTestData($test->getTestData(), $this->getActiveUser());
        }

        foreach ($test->getConfigurations() as $configuration_for => $configuration) {
            if (!AbstractValueObject::isNullableEqual($stored->getConfiguration($configuration_for), $configuration)) {
                $stored->setConfiguration($configuration, $configuration_for, $this->getActiveUser());
            }
        }

        foreach (array_diff(
                array_keys($stored->getConfigurations()),
                array_keys($test->getConfigurations())) as $deleted)
        {
            $stored->removeConfiguration($deleted, $this->getActiveUser());
        }

        foreach (array_diff($test->getSections(), $stored->getSections()) as $new_section) {
            $stored->addSection($new_section, $this->getActiveUser());
        }

        foreach (array_diff($stored->getSections(), $test->getSections()) as $removed_section) {
            $stored->removeSection($removed_section, $this->getActiveUser());
        }

        if (count($stored->getRecordedEvents()->getEvents()) > 0) {
            $this->repo->save($stored);
        }
    }
}
