<?php

namespace srag\asq\Test\Application\Section;

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

/**
 * Class SectionService
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */

class SectionService extends ASQService {
    /**
     * @var CommandBus
     */
    private $command_bus;

    private function getCommandBus() : CommandBus {
        if (is_null($this->command_bus)) {
            $this->command_bus = new CommandBus();

            $this->command_bus->registerCommand(new CommandConfiguration(
                AddItemCommand::class,
                new AddItemCommandHandler(),
                new OpenAccess()));

            $this->command_bus->registerCommand(new CommandConfiguration(
                CreateSectionCommand::class,
                new CreateSectionCommandHandler(),
                new OpenAccess()));

            $this->command_bus->registerCommand(new CommandConfiguration(
                RemoveItemCommand::class,
                new RemoveItemCommandHandler(),
                new OpenAccess()));
        }

        return $this->command_bus;
    }

    /**
     * @return string
     */
    public function createSection() : string {
        $uuid_factory = new Factory();
        $uuid = $uuid_factory->uuid4AsString();

        // CreateQuestion.png
        $this->getCommandBus()->handle(
            new CreateSectionCommand(
                $uuid,
                $this->getActiveUser()
            )
        );

        return $uuid;
    }

    /**
     * @param string $section_id
     * @param string $question_id
     * @param string $question_revision
     */
    public function addQuestion(string $section_id, string $question_id, ?string $question_revision = null) {
        $this->getCommandBus()->handle(
            new AddItemCommand(
                $section_id,
                $this->getActiveUser(),
                SectionPart::create(
                    SectionPart::TYPE_QUESTION,
                    $question_id,
                    $question_revision
                )
            )
        );
    }

    /**
     * @param string $section_id
     * @param string $question_id
     * @param string $question_revision
     */
    public function removeQuestion(string $section_id, string $question_id, ?string $question_revision = null) {
        $this->getCommandBus()->handle(
            new RemoveItemCommand(
                $section_id,
                $this->getActiveUser(),
                SectionPart::create(
                    SectionPart::TYPE_QUESTION,
                    $question_id,
                    $question_revision
                )
            )
        );
    }

    /**
     * @param string $section_id
     * @return AssessmentSectionDto
     */
    public function getSection(string $section_id) : AssessmentSectionDto {
        return AssessmentSectionDto::Create(
            AssessmentSectionRepository::getInstance()->getAggregateRootById($section_id)
        );
    }
}