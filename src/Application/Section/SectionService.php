<?php

namespace srag\asq\Test\Application\Section;

use srag\CQRS\Aggregate\Guid;
use srag\CQRS\Command\CommandBusBuilder;
use srag\asq\Application\Service\ASQService;
use srag\asq\Test\Application\Section\Command\CreateSectionCommand;
use srag\asq\Test\Application\Section\Command\AddItemCommand;
use srag\asq\Test\Domain\Section\Model\SectionPart;
use srag\asq\Test\Application\Section\Command\RemoveItemCommand;
use srag\asq\Test\Domain\Section\Model\AssessmentSectionDto;
use srag\asq\Test\Domain\Section\Model\AssessmentSectionRepository;
use srag\CQRS\Aggregate\DomainObjectId;

/**
 * Class SectionService
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */

class SectionService extends ASQService {
    /**
     * @return string
     */
    public function createSection() : string {
        $uuid = Guid::create();
        
        // CreateQuestion.png
        CommandBusBuilder::getCommandBus()->handle(
            new CreateSectionCommand(
                $uuid,
                $this->getActiveUser()));
        
        return $uuid;
    }
    
    /**
     * @param string $section_id
     * @param string $question_id
     * @param string $question_revision
     */
    public function addQuestion(string $section_id, string $question_id, ?string $question_revision = null) {
        CommandBusBuilder::getCommandBus()->handle(
            new AddItemCommand(
                $section_id, 
                $this->getActiveUser(), 
                SectionPart::create(
                    SectionPart::TYPE_QUESTION,
                    $question_id,
                    $question_revision
                )
            ));
    }
    
    /**
     * @param string $section_id
     * @param string $question_id
     * @param string $question_revision
     */
    public function removeQuestion(string $section_id, string $question_id, ?string $question_revision = null) {
        CommandBusBuilder::getCommandBus()->handle(
            new RemoveItemCommand(
                $section_id,
                $this->getActiveUser(), 
                SectionPart::create(
                    SectionPart::TYPE_QUESTION,
                    $question_id,
                    $question_revision
                    )
                ));
    }
    
    /**
     * @param string $section_id
     * @return AssessmentSectionDto
     */
    public function getSection(string $section_id) : AssessmentSectionDto {
        return AssessmentSectionDto::Create(
            AssessmentSectionRepository::getInstance()->getAggregateRootById(new DomainObjectId($section_id))
        );
    }
}