<?php

namespace srag\asq\Test\Application\Section\Command;

use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\Domain\Section\Model\AssessmentSection;
use srag\asq\Test\Domain\Section\Model\AssessmentSectionRepository;
use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;

/**
 * Class StartAssessmentCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class CreateSectionCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command CreateSectionCommand
     */
    public function handle(CommandContract $command) : Result
    {
        $section = AssessmentSection::create(
            $command->getId(),
            $command->getIssuingUserId()
        );

        AssessmentSectionRepository::getInstance()->save($section);

        return new Ok(null);
    }
}
