<?php

namespace srag\asq\Test\Command;

use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\DomainModel\AssessmentResult;
use srag\asq\Test\DomainModel\AssessmentResultRepository;

/**
 * Class AddAnswerCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AddAnswerCommandHandler implements CommandHandlerContract {
    /**
     * @param $command AddAnswerCommand
     */
    public function handle(CommandContract $command)
    {
        /** @var $assessment_result AssessmentResult */
        $assessment_result = AssessmentResultRepository::getInstance()->getAggregateRootById(new DomainObjectId($command->getResultUuid()));
        
        $assessment_result->setAnswer($command->getQuestionId(), $command->getAnswer(), $command->getIssuingUserId());
        
        AssessmentResultRepository::getInstance()->save($assessment_result);
    }
}