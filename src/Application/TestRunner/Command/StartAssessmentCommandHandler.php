<?php

namespace srag\asq\Test\Application\TestRunner\Command;

use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\Domain\Result\Model\AssessmentResult;
use srag\asq\Test\Domain\Result\Model\AssessmentResultRepository;

/**
 * Class StartAssessmentCommandHandler
 *
 * @package srag\asq\Test
 *
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