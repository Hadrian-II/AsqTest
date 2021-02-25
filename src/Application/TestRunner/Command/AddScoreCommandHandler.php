<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\TestRunner\Command;

use ILIAS\Data\Result;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\Domain\Result\Model\AssessmentResult;
use srag\asq\Test\Domain\Result\Model\AssessmentResultRepository;
use ILIAS\Data\Result\Ok;

/**
 * Class AddScoreCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AddScoreCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command AddScoreCommand
     */
    public function handle(CommandContract $command) : Result
    {
        /** @var $assessment_result AssessmentResult */
        $assessment_result = AssessmentResultRepository::getInstance()->getAggregateRootById($command->getResultUuid());

        $assessment_result->setScore($command->getQuestionId(), $command->getScore(), $command->getIssuingUserId());

        $repo = new AssessmentResultRepository();
        $repo->save($assessment_result);

        return new Ok(null);
    }
}
