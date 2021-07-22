<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\Section;

use srag\asq\Test\Application\Section\Command\SetDataCommand;
use srag\asq\Test\Application\Section\Command\SetDataCommandHandler;
use srag\asq\Test\Domain\Section\Model\AssessmentSectionData;
use srag\CQRS\Command\CommandBus;
use srag\CQRS\Command\CommandConfiguration;
use srag\CQRS\Command\Access\OpenAccess;
use srag\asq\Application\Service\ASQService;
use srag\asq\Test\Application\Section\Command\AddItemCommand;
use srag\asq\Test\Application\Section\Command\AddItemCommandHandler;
use srag\asq\Test\Application\Section\Command\CreateSectionCommand;
use srag\asq\Test\Application\Section\Command\CreateSectionCommandHandler;
use srag\asq\Test\Application\Section\Command\RemoveItemCommand;
use srag\asq\Test\Application\Section\Command\RemoveItemCommandHandler;
use srag\asq\Test\Domain\Section\Model\AssessmentSectionDto;
use srag\asq\Test\Domain\Section\Model\AssessmentSectionRepository;
use srag\asq\Test\Domain\Section\Model\SectionPart;
use ILIAS\Data\UUID\Factory;
use ILIAS\Data\UUID\Uuid;

/**
 * Class SectionService
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */

class SectionService extends ASQService
{
    private CommandBus $command_bus;

    private AssessmentSectionRepository $repo;

    public function __construct()
    {
        $this->command_bus = new CommandBus();

        $this->command_bus->registerCommand(new CommandConfiguration(
            AddItemCommand::class,
            new AddItemCommandHandler(),
            new OpenAccess()
        ));

        $this->command_bus->registerCommand(new CommandConfiguration(
            CreateSectionCommand::class,
            new CreateSectionCommandHandler(),
            new OpenAccess()
        ));

        $this->command_bus->registerCommand(new CommandConfiguration(
            RemoveItemCommand::class,
            new RemoveItemCommandHandler(),
            new OpenAccess()
        ));

        $this->command_bus->registerCommand(new CommandConfiguration(
            SetDataCommand::class,
            new SetDataCommandHandler(),
            new OpenAccess()
        ));

        $this->repo = new AssessmentSectionRepository();
    }

    public function createSection() : Uuid
    {
        $uuid_factory = new Factory();
        $uuid = $uuid_factory->uuid4();

        // CreateQuestion.png
        $this->command_bus->handle(
            new CreateSectionCommand(
                $uuid,
                $this->getActiveUser()
            )
        );

        return $uuid;
    }

    public function addQuestion(Uuid $section_id, Uuid $question_id, ?string $question_revision = null) : void
    {
        $this->command_bus->handle(
            new AddItemCommand(
                $section_id,
                $this->getActiveUser(),
                new SectionPart(
                    SectionPart::TYPE_QUESTION,
                    $question_id,
                    $question_revision
                )
            )
        );
    }

    public function removeQuestion(Uuid $section_id, Uuid $question_id, ?string $question_revision = null) : void
    {
        $this->command_bus->handle(
            new RemoveItemCommand(
                $section_id,
                $this->getActiveUser(),
                new SectionPart(
                    SectionPart::TYPE_QUESTION,
                    $question_id,
                    $question_revision
                )
            )
        );
    }

    public function setSectionData(Uuid $section_id, AssessmentSectionData $data) : void
    {
        $this->command_bus->handle(
            new SetDataCommand(
                $section_id,
                $this->getActiveUser(),
                $data
            )
        );
    }

    public function getSection(Uuid $section_id) : AssessmentSectionDto
    {
        return AssessmentSectionDto::Create(
            $this->repo->getAggregateRootById($section_id)
        );
    }
}
