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

/**
 * Class TestService
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */

class TestService extends ASQService
{
    /**
     * @var CommandBus
     */
    private $command_bus;

    private function getCommandBus() : CommandBus
    {
        if (is_null($this->command_bus)) {
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
        }

        return $this->command_bus;
    }

    /**
     * @return Uuid
     */
    public function createTest() : Uuid
    {
        $uuid_factory = new Factory();
        $uuid = $uuid_factory->uuid4();

        // CreateQuestion.png
        $this->getCommandBus()->handle(
            new CreateTestCommand(
                $uuid,
                $this->getActiveUser()
            )
        );

        return $uuid;
    }

    /**
     * @param Uuid $id
     * @param Uuid $section_id
     */
    public function addSection(Uuid $id, Uuid $section_id) : void
    {
        $this->getCommandBus()->handle(
            new AddSectionCommand(
                $id,
                $section_id,
                $this->getActiveUser()
            )
        );
    }

    /**
     * @param Uuid $test_id
     * @return AssessmentTest
     */
    public function getTest(Uuid $test_id) : array
    {
        AssessmentTestRepository::getInstance()->getAggregateRootById($test_id);
    }

    /**
     * @param AssessmentTest $test
     */
    public function saveTest(AssessmentTest $test) : void
    {
        if (count($test->getRecordedEvents()->getEvents()) > 0) {
            AssessmentTestRepository::getInstance()->save($test);
        }
    }
}
