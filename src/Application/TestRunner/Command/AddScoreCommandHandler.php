<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\TestRunner\Command;

use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\Domain\Result\Model\AssessmentResultRepository;

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
        $repo = new AssessmentResultRepository();

        $assessment_result = $repo->getAggregateRootById($command->getResultUuid());

        $assessment_result->setScore($command->getQuestionId(), $command->getScore(), $command->getIssuingUserId());

        $repo->save($assessment_result);

        return new Ok(null);
    }
}
