<?php

namespace srag\asq\Test\Command;

use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\DomainModel\AssessmentResult;
use srag\asq\Test\DomainModel\AssessmentResultRepository;

/**
 * Class StartAssessmentCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class StartAssessmentCommandHandler implements CommandHandlerContract {
    /**
     * @param $command StartAssessmentCommand
     */
    public function handle(CommandContract $command)
    {
        $assessment_result = AssessmentResult::create(
            new DomainObjectId($command->getUuid()),
            $command->getContext(),
            $command->getQuestionIds(),
            $command->getIssuingUserId());
        
        AssessmentResultRepository::getInstance()->save($assessment_result);
    }
}