<?php

namespace srag\asq\Test\Application\TestRunner\Command;

use ILIAS\Data\Result;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\CQRS\Aggregate\DomainObjectId;
use srag\asq\Test\Domain\Result\Model\AssessmentResult;
use srag\asq\Test\Domain\Result\Model\AssessmentResultRepository;
use ILIAS\Data\Result\Ok;

/**
 * Class SubmitAssessmentCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class SubmitAssessmentCommandHandler implements CommandHandlerContract {
    /**
     * @param $command SubmitAssessmentCommand
     */
    public function handle(CommandContract $command) : Result
    {
        /** @var $assessment_result AssessmentResult */
        $assessment_result = AssessmentResultRepository::getInstance()->getAggregateRootById(new DomainObjectId($command->getResultUuid()));

        $assessment_result->submitResult($command->getIssuingUserId());
        
        AssessmentResultRepository::getInstance()->save($assessment_result);
        
        return new Ok(null);
    }
}