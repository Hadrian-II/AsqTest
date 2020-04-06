<?php

namespace srag\asq\Test\Application\Section\Command;

use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\Domain\Section\Model\AssessmentSection;
use srag\asq\Test\Domain\Section\Model\AssessmentSectionRepository;

/**
 * Class StartAssessmentCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class CreateSectionCommandHandler implements CommandHandlerContract {
    /**
     * @param $command CreateSectionCommand
     */
    public function handle(CommandContract $command)
    {
        $section = AssessmentSection::create(
            new DomainObjectId($command->getId()),
            $command->getIssuingUserId()
        );
        
        AssessmentSectionRepository::getInstance()->save($section);
    }
}