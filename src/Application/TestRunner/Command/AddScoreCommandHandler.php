<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\TestRunner\Command;

use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use Fluxlabs\Assessment\Test\Domain\Result\Model\AssessmentResultRepository;

/**
 * Class AddScoreCommandHandler
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
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
