<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\TestRunner\Command;

use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use Fluxlabs\Assessment\Test\Domain\Result\Model\AssessmentResult;
use Fluxlabs\Assessment\Test\Domain\Result\Model\AssessmentResultRepository;
use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;

/**
 * Class StartAssessmentCommandHandler
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class StartAssessmentCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command StartAssessmentCommand
     */
    public function handle(CommandContract $command) : Result
    {
        $assessment_result = AssessmentResult::create(
            $command->getUuid(),
            $command->getContext(),
            $command->getQuestionIds(),
            $command->getIssuingUserId()
        );

        $repo = new AssessmentResultRepository();
        $repo->save($assessment_result);

        return new Ok(null);
    }
}
