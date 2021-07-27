<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\TestRunner\Command;

use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\Domain\Result\Model\AssessmentResultRepository;

/**
 * Class FinishScoringCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class FinishScoringCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command FinishScoringCommand
     */
    public function handle(CommandContract $command) : Result
    {
        $repo = new AssessmentResultRepository();

        $assessment_result = $repo->getAggregateRootById($command->getResultUuid());
        $assessment_result->finishScoring($command->getIssuingUserId());

        $repo->save($assessment_result);

        return new Ok(null);
    }
}
