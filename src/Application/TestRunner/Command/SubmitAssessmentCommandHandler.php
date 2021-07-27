<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\TestRunner\Command;

use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\Domain\Result\Model\AssessmentResultRepository;

/**
 * Class SubmitAssessmentCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SubmitAssessmentCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command SubmitAssessmentCommand
     */
    public function handle(CommandContract $command) : Result
    {
        $repo = new AssessmentResultRepository();

        $assessment_result = $repo->getAggregateRootById($command->getResultUuid());
        $assessment_result->submitResult($command->getIssuingUserId());

        $repo->save($assessment_result);

        return new Ok(null);
    }
}
