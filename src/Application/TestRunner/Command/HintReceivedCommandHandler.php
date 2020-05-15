<?php

namespace srag\asq\Test\Application\TestRunner\Command;

use ILIAS\Data\Result;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\Domain\Result\Model\AssessmentResult;
use srag\asq\Test\Domain\Result\Model\AssessmentResultRepository;
use ILIAS\Data\Result\Ok;

/**
 * Class HintReceivedCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class HintReceivedCommandHandler implements CommandHandlerContract {
    /**
     * @param $command HintReceivedCommand
     */
    public function handle(CommandContract $command) : Result
    {
        /** @var $assessment_result AssessmentResult */
        $assessment_result = AssessmentResultRepository::getInstance()->getAggregateRootById($command->getResultUuid());

        $assessment_result->addHint($command->getQuestionId(), $command->getHint(), $command->getIssuingUserId());

        AssessmentResultRepository::getInstance()->save($assessment_result);

        return new Ok(null);
    }
}