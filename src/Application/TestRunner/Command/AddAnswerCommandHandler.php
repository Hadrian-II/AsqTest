<?php

namespace srag\asq\Test\Application\TestRunner\Command;

use ILIAS\Data\Result;
use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\Domain\Result\Model\AssessmentResult;
use srag\asq\Test\Domain\Result\Model\AssessmentResultRepository;
use ILIAS\Data\Result\Ok;

/**
 * Class AddAnswerCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AddAnswerCommandHandler implements CommandHandlerContract {
    /**
     * @param $command AddAnswerCommand
     */
    public function handle(CommandContract $command) : Result
    {
        /** @var $assessment_result AssessmentResult */
        $assessment_result = AssessmentResultRepository::getInstance()->getAggregateRootById(new DomainObjectId($command->getResultUuid()));
        
        $assessment_result->setAnswer($command->getQuestionId(), $command->getAnswer(), $command->getIssuingUserId());
        
        AssessmentResultRepository::getInstance()->save($assessment_result);
        
        return new Ok(null);
    }
}