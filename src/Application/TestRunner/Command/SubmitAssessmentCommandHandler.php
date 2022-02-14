<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\TestRunner\Command;

use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;
use Fluxlabs\CQRS\Command\CommandContract;
use Fluxlabs\CQRS\Command\CommandHandlerContract;
use Fluxlabs\Assessment\Test\Domain\Result\Model\AssessmentResultRepository;

/**
 * Class SubmitAssessmentCommandHandler
 *
 * @package Fluxlabs\Assessment\Test
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
        $assessment_result->submitResult();

        $repo->save($assessment_result);

        return new Ok(null);
    }
}
