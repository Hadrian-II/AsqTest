<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\TestRunner\Command;

use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\Domain\Result\Model\AssessmentResultRepository;

/**
 * Class HintReceivedCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class HintReceivedCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command HintReceivedCommand
     */
    public function handle(CommandContract $command) : Result
    {
        $repo = new AssessmentResultRepository();

        $assessment_result = AssessmentResultRepository::getInstance()->getAggregateRootById($command->getResultUuid());
        $assessment_result->addHint($command->getQuestionId(), $command->getHint(), $command->getIssuingUserId());

        $repo->save($assessment_result);

        return new Ok(null);
    }
}
